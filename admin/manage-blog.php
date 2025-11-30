<?php
// admin/manage-blog.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include '../config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$success = '';
$error = '';

// Check if table exists, if not create it
try {
    $table_exists = $db->query("SELECT 1 FROM blog_posts LIMIT 1");
} catch (PDOException $e) {
    // Table doesn't exist, redirect to fix it
    header("Location: ../fix-blog-table.php");
    exit;
}

// Handle form submissions
if ($_POST && isset($_POST['add_post'])) {
    $title = $_POST['title'] ?? '';
    $excerpt = $_POST['excerpt'] ?? '';
    $content = $_POST['content'] ?? '';
    $author = $_POST['author'] ?? 'Admin';
    $status = $_POST['status'] ?? 'draft';
    $read_time = $_POST['read_time'] ?? '5 min read';

    if (!empty($title) && !empty($content)) {
        try {
            $query = "INSERT INTO blog_posts (title, excerpt, content, author, status, read_time) 
                      VALUES (:title, :excerpt, :content, :author, :status, :read_time)";
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':title' => $title,
                ':excerpt' => $excerpt,
                ':content' => $content,
                ':author' => $author,
                ':status' => $status,
                ':read_time' => $read_time
            ]);

            $success = "Blog post added successfully!";
            header("Location: manage-blog.php?success=added");
            exit;
        } catch (PDOException $e) {
            $error = "Error adding blog post: " . $e->getMessage();
        }
    } else {
        $error = "Please fill in title and content.";
    }
}

// Update blog post
if ($_POST && isset($_POST['update_post'])) {
    $id = $_POST['id'] ?? '';
    $title = $_POST['title'] ?? '';
    $excerpt = $_POST['excerpt'] ?? '';
    $content = $_POST['content'] ?? '';
    $author = $_POST['author'] ?? 'Admin';
    $status = $_POST['status'] ?? 'draft';
    $read_time = $_POST['read_time'] ?? '5 min read';

    if (!empty($id) && !empty($title) && !empty($content)) {
        try {
            $query = "UPDATE blog_posts SET title = :title, excerpt = :excerpt, content = :content, 
                      author = :author, status = :status, read_time = :read_time 
                      WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':id' => $id,
                ':title' => $title,
                ':excerpt' => $excerpt,
                ':content' => $content,
                ':author' => $author,
                ':status' => $status,
                ':read_time' => $read_time
            ]);

            $success = "Blog post updated successfully!";
            header("Location: manage-blog.php?success=updated");
            exit;
        } catch (PDOException $e) {
            $error = "Error updating blog post: " . $e->getMessage();
        }
    }
}

// Delete blog post
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    try {
        $query = "DELETE FROM blog_posts WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->execute([':id' => $delete_id]);
        
        $success = "Blog post deleted successfully!";
        header("Location: manage-blog.php?success=deleted");
        exit;
    } catch (PDOException $e) {
        $error = "Error deleting blog post: " . $e->getMessage();
    }
}

// Show success message from redirect
if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'added':
            $success = "Blog post added successfully!";
            break;
        case 'updated':
            $success = "Blog post updated successfully!";
            break;
        case 'deleted':
            $success = "Blog post deleted successfully!";
            break;
    }
}

// Get all blog posts
$posts = [];
try {
    $stmt = $db->query("SELECT * FROM blog_posts ORDER BY created_at DESC");
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching posts: " . $e->getMessage();
}

// Get post for editing
$edit_post = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    try {
        $stmt = $db->prepare("SELECT * FROM blog_posts WHERE id = :id");
        $stmt->execute([':id' => $edit_id]);
        $edit_post = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = "Error fetching post: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Blog - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <style>
        .table-responsive { max-height: 600px; }
        .edit-modal { 
            background: rgba(0,0,0,0.5);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1050;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .edit-modal .modal-dialog {
            margin: 0;
            max-width: 90%;
        }
        
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include 'sidebar.php'; ?>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>Manage Blog Posts</h2>
                        <div>
                            <a href="../fix-blog-table.php" class="btn btn-warning me-2">
                                <i class="fas fa-tools me-1"></i>Fix Database
                            </a>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPostModal">
                                <i class="fas fa-plus me-2"></i>Add New Post
                            </button>
                        </div>
                    </div>

                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?php echo $success; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">All Blog Posts (<?php echo count($posts); ?>)</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($posts)): ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No blog posts found</h5>
                                    <p class="text-muted">Create your first blog post to get started.</p>
                                    <a href="../fix-blog-table.php" class="btn btn-primary">
                                        <i class="fas fa-tools me-1"></i>Setup Sample Posts
                                    </a>
                                </div>
                            <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Author</th>
                                            <th>Status</th>
                                            <th>Read Time</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($posts as $post): ?>
                                        <tr>
                                            <td><?php echo $post['id']; ?></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($post['title']); ?></strong>
                                                <?php if ($post['excerpt']): ?>
                                                <br><small class="text-muted"><?php echo substr(htmlspecialchars($post['excerpt']), 0, 80) . '...'; ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($post['author']); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $post['status'] == 'published' ? 'success' : 'warning'; ?>">
                                                    <?php echo ucfirst($post['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo htmlspecialchars($post['read_time']); ?></td>
                                            <td><?php echo date('M j, Y', strtotime($post['created_at'])); ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="?edit_id=<?php echo $post['id']; ?>" class="btn btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="../blog-single.php?id=<?php echo $post['id']; ?>" target="_blank" class="btn btn-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="?delete_id=<?php echo $post['id']; ?>" class="btn btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this post?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Post Modal -->
    <div class="modal fade" id="addPostModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Blog Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Title *</label>
                            <input type="text" class="form-control" name="title" required placeholder="Enter blog post title">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Excerpt</label>
                            <textarea class="form-control" name="excerpt" rows="3" placeholder="Brief description of the post (appears in blog listing)"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Content *</label>
                            <textarea class="form-control" name="content" rows="10" required placeholder="Write your blog post content here..."></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Author</label>
                                <input type="text" class="form-control" name="author" value="Admin">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="draft">Draft</option>
                                    <option value="published" selected>Published</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Read Time</label>
                                <input type="text" class="form-control" name="read_time" value="5 min read" placeholder="e.g., 5 min read">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_post" class="btn btn-primary">Add Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Post Modal -->
    <?php if ($edit_post): ?>
    <div class="edit-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Blog Post</h5>
                    <a href="manage-blog.php" class="btn-close"></a>
                </div>
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $edit_post['id']; ?>">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Title *</label>
                            <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($edit_post['title']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Excerpt</label>
                            <textarea class="form-control" name="excerpt" rows="3"><?php echo htmlspecialchars($edit_post['excerpt']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Content *</label>
                            <textarea class="form-control" name="content" rows="10" required><?php echo htmlspecialchars($edit_post['content']); ?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Author</label>
                                <input type="text" class="form-control" name="author" value="<?php echo htmlspecialchars($edit_post['author']); ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="draft" <?php echo $edit_post['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
                                    <option value="published" <?php echo $edit_post['status'] == 'published' ? 'selected' : ''; ?>>Published</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Read Time</label>
                                <input type="text" class="form-control" name="read_time" value="<?php echo htmlspecialchars($edit_post['read_time']); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="manage-blog.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" name="update_post" class="btn btn-primary">Update Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>