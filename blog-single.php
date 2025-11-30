<?php
// blog-single.php
include 'includes/header.php';
include 'config/database.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Get blog post ID from URL
$post_id = $_GET['id'] ?? 0;

// Fetch the specific blog post
$post = null;
if ($post_id) {
    try {
        $query = "SELECT * FROM blog_posts WHERE id = :id AND status = 'published'";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":id", $post_id);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
    }
}

// If post not found, redirect to blog page
if (!$post) {
    header("Location: blog.php");
    exit;
}

// Fetch recent posts for sidebar
$recent_posts = [];
try {
    $query = "SELECT id, title, excerpt, created_at, read_time 
              FROM blog_posts 
              WHERE status = 'published' AND id != :id 
              ORDER BY created_at DESC 
              LIMIT 3";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $post_id);
    $stmt->execute();
    $recent_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
}
?>

<section class="section-padding">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <article class="blog-single">
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="blog.php">Blog</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($post['title']); ?></li>
                        </ol>
                    </nav>

                    <!-- Article Header -->
                    <header class="blog-header mb-4">
                        <h1 class="blog-title display-5 fw-bold text-primary mb-3">
                            <?php echo htmlspecialchars($post['title']); ?>
                        </h1>
                        
                        <div class="blog-meta d-flex flex-wrap align-items-center gap-4 mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user me-2 text-orange"></i>
                                <span class="text-muted">By <?php echo htmlspecialchars($post['author']); ?></span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar me-2 text-orange"></i>
                                <span class="text-muted">
                                    <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                                </span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock me-2 text-orange"></i>
                                <span class="text-muted"><?php echo htmlspecialchars($post['read_time']); ?></span>
                            </div>
                        </div>

                        <?php if ($post['excerpt']): ?>
                        <div class="lead text-muted p-4 bg-light rounded">
                            <?php echo htmlspecialchars($post['excerpt']); ?>
                        </div>
                        <?php endif; ?>
                    </header>

                    <!-- Article Content -->
                    <div class="blog-content">
                        <div class="content-body">
                            <?php 
                            // Convert line breaks to paragraphs for better formatting
                            $content = nl2br(htmlspecialchars($post['content']));
                            echo '<div class="content-text">' . $content . '</div>';
                            ?>
                        </div>
                    </div>

                    <!-- Article Footer -->
                    <footer class="blog-footer mt-5 pt-4 border-top">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="mb-3">Share this article</h5>
                                <div class="social-share">
                                    <a href="#" class="btn btn-outline-primary btn-sm me-2">
                                        <i class="fab fa-facebook-f me-1"></i> Share
                                    </a>
                                    <a href="#" class="btn btn-outline-info btn-sm me-2">
                                        <i class="fab fa-twitter me-1"></i> Tweet
                                    </a>
                                    <a href="#" class="btn btn-outline-danger btn-sm">
                                        <i class="fab fa-linkedin-in me-1"></i> Share
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <p class="text-muted mb-0">
                                    Last updated: <?php echo date('F j, Y', strtotime($post['updated_at'])); ?>
                                </p>
                            </div>
                        </div>
                    </footer>
                </article>

                <!-- Related Articles Section -->
                <?php if ($recent_posts): ?>
                <section class="related-articles mt-5">
                    <h3 class="section-title h4 mb-4">Related Articles</h3>
                    <div class="row g-4">
                        <?php foreach ($recent_posts as $recent_post): ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="blog-card h-100">
                                <div class="blog-content">
                                    <h5 class="blog-title">
                                        <a href="blog-single.php?id=<?php echo $recent_post['id']; ?>" class="text-decoration-none text-primary">
                                            <?php echo htmlspecialchars($recent_post['title']); ?>
                                        </a>
                                    </h5>
                                    <p class="blog-excerpt small">
                                        <?php echo substr(htmlspecialchars($recent_post['excerpt']), 0, 100) . '...'; ?>
                                    </p>
                                    <div class="blog-meta small">
                                        <span><i class="fas fa-calendar me-1"></i> 
                                            <?php echo date('M j, Y', strtotime($recent_post['created_at'])); ?>
                                        </span>
                                        <span><i class="fas fa-clock me-1 ms-2"></i> 
                                            <?php echo htmlspecialchars($recent_post['read_time']); ?>
                                        </span>
                                    </div>
                                    <a href="blog-single.php?id=<?php echo $recent_post['id']; ?>" class="btn btn-primary btn-sm mt-2">
                                        Read More
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="blog-sidebar">
                    <!-- About Section -->
                    <div class="sidebar-widget mb-5">
                        <div class="service-card text-center">
                            <div class="service-icon mx-auto mb-3">
                                <i class="fas fa-heart"></i>
                            </div>
                            <h4 class="text-primary">Grace Senior Care</h4>
                            <p class="text-muted">Providing compassionate care and expert advice for seniors and their families.</p>
                            <a href="about.php" class="btn btn-orange btn-sm">Learn More</a>
                        </div>
                    </div>

                    <!-- Recent Posts -->
                    <?php if ($recent_posts): ?>
                    <div class="sidebar-widget mb-5">
                        <h4 class="widget-title mb-4">Recent Posts</h4>
                        <div class="recent-posts">
                            <?php foreach ($recent_posts as $recent_post): ?>
                            <div class="recent-post-item mb-3 pb-3 border-bottom">
                                <h6 class="mb-1">
                                    <a href="blog-single.php?id=<?php echo $recent_post['id']; ?>" class="text-decoration-none text-dark">
                                        <?php echo htmlspecialchars($recent_post['title']); ?>
                                    </a>
                                </h6>
                                <div class="post-meta small text-muted">
                                    <span><i class="fas fa-calendar me-1"></i> 
                                        <?php echo date('M j', strtotime($recent_post['created_at'])); ?>
                                    </span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Newsletter -->
                    <div class="sidebar-widget">
                        <div class="service-card">
                            <h4 class="text-primary mb-3">Stay Updated</h4>
                            <p class="text-muted small mb-3">Get the latest senior care tips and insights delivered to your inbox.</p>
                            <form class="subscribe-form">
                                <div class="mb-3">
                                    <input type="email" class="form-control form-control-sm" placeholder="Your email address" required>
                                </div>
                                <button type="submit" class="btn btn-orange btn-sm w-100">Subscribe</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>