<?php
// admin/manage-contacts.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include '../config/database.php';
$database = new Database();
$db = $database->getConnection();

// Check if is_read column exists
$column_exists = false;
try {
    $stmt = $db->query("SHOW COLUMNS FROM contacts LIKE 'is_read'");
    $column_exists = $stmt->rowCount() > 0;
} catch (PDOException $e) {
    $column_exists = false;
}

// Get all contact messages
$contacts = $db->query("SELECT * FROM contacts ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

// Mark as read (only if column exists)
if (isset($_GET['mark_read']) && $column_exists) {
    $contact_id = $_GET['mark_read'];
    $stmt = $db->prepare("UPDATE contacts SET is_read = 1 WHERE id = ?");
    $stmt->execute([$contact_id]);
    header("Location: manage-contacts.php");
    exit;
}

// Delete contact
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $db->prepare("DELETE FROM contacts WHERE id = ?");
    $stmt->execute([$delete_id]);
    header("Location: manage-contacts.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Contacts - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar.php'; ?>
            
            <div class="col-md-9 col-lg-10">
                <div class="p-4">
                    <h2>Contact Messages</h2>
                    
                    <?php if (!$column_exists): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        The <code>is_read</code> column is missing. 
                        <a href="update-contacts-table.php" class="alert-link">Click here to update your database</a> to enable read/unread status.
                    </div>
                    <?php endif; ?>
                    
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">All Messages</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Subject</th>
                                            <th>Message</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($contacts as $contact): 
                                            // Safely handle missing is_read column
                                            $is_read = $column_exists ? ($contact['is_read'] ?? 0) : 1;
                                            $status_class = $is_read ? 'bg-success' : 'bg-warning';
                                            $status_text = $is_read ? 'Read' : 'Unread';
                                        ?>
                                        <tr>
                                            <td><?php echo $contact['id']; ?></td>
                                            <td><?php echo htmlspecialchars($contact['name']); ?></td>
                                            <td>
                                                <a href="mailto:<?php echo htmlspecialchars($contact['email']); ?>">
                                                    <?php echo htmlspecialchars($contact['email']); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <?php if (!empty($contact['phone'])): ?>
                                                    <a href="tel:<?php echo htmlspecialchars($contact['phone']); ?>">
                                                        <?php echo htmlspecialchars($contact['phone']); ?>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($contact['subject']); ?></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="modal" data-bs-target="#messageModal<?php echo $contact['id']; ?>">
                                                    View Message
                                                </button>
                                            </td>
                                            <td>
                                                <?php 
                                                if (isset($contact['created_at'])) {
                                                    echo date('M j, Y g:i A', strtotime($contact['created_at']));
                                                } else {
                                                    echo 'N/A';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <span class="badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                                            </td>
                                            <td>
                                                <?php if (!$is_read && $column_exists): ?>
                                                    <a href="?mark_read=<?php echo $contact['id']; ?>" class="btn btn-sm btn-success" title="Mark as Read">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <a href="?delete_id=<?php echo $contact['id']; ?>" class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Are you sure you want to delete this message?')" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>

                                        <!-- Message Modal -->
                                        <div class="modal fade" id="messageModal<?php echo $contact['id']; ?>" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Message from <?php echo htmlspecialchars($contact['name']); ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><strong>Subject:</strong> <?php echo htmlspecialchars($contact['subject']); ?></p>
                                                        <p><strong>Email:</strong> <?php echo htmlspecialchars($contact['email']); ?></p>
                                                        <p><strong>Phone:</strong> <?php echo !empty($contact['phone']) ? htmlspecialchars($contact['phone']) : 'N/A'; ?></p>
                                                        <p><strong>Date:</strong> 
                                                            <?php 
                                                            if (isset($contact['created_at'])) {
                                                                echo date('M j, Y g:i A', strtotime($contact['created_at']));
                                                            } else {
                                                                echo 'N/A';
                                                            }
                                                            ?>
                                                        </p>
                                                        <hr>
                                                        <p><strong>Message:</strong></p>
                                                        <p><?php echo nl2br(htmlspecialchars($contact['message'])); ?></p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <a href="mailto:<?php echo htmlspecialchars($contact['email']); ?>" class="btn btn-primary">
                                                            <i class="fas fa-reply me-2"></i>Reply
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>