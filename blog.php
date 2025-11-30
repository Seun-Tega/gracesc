<?php
// blog.php
include 'includes/header.php';
include 'config/database.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Fetch all published blog posts
$posts = [];
try {
    $query = "SELECT * FROM blog_posts WHERE status = 'published' ORDER BY created_at DESC";
    $stmt = $db->query($query);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
}

// Get featured post (first post or specific one)
$featured_post = !empty($posts) ? $posts[0] : null;
// Remove featured post from regular posts to avoid duplication
$regular_posts = $posts;
if ($featured_post) {
    array_shift($regular_posts);
}
?>

<section class="section-padding">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="section-title">Senior Care Insights</h1>
            <p class="section-subtitle">Expert advice, tips, and resources for seniors and caregivers</p>
        </div>

        <?php if ($featured_post): ?>
        <!-- Featured Article -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="blog-card">
                    <div class="row g-0">
                        <div class="col-lg-6">
                            <div class="blog-image h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="fas fa-brain fa-4x"></i>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="blog-content h-100 d-flex flex-column justify-content-center">
                                <span class="badge bg-orange mb-3 align-self-start">Featured</span>
                                <h2 class="blog-title"><?php echo htmlspecialchars($featured_post['title']); ?></h2>
                                <p class="blog-excerpt"><?php echo htmlspecialchars($featured_post['excerpt']); ?></p>
                                <div class="blog-meta">
                                    <span><i class="fas fa-calendar me-1"></i> <?php echo date('F j, Y', strtotime($featured_post['created_at'])); ?></span>
                                    <span><i class="fas fa-clock me-1"></i> <?php echo htmlspecialchars($featured_post['read_time']); ?></span>
                                </div>
                                <a href="blog-single.php?id=<?php echo $featured_post['id']; ?>" class="btn btn-primary mt-3 align-self-start">Read Full Article</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Blog Grid -->
        <div class="row g-4">
            <?php if (empty($regular_posts)): ?>
                <div class="col-12 text-center">
                    <div class="service-card">
                        <div class="service-icon mx-auto mb-4">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <h3 class="text-primary mb-3">No Blog Posts Yet</h3>
                        <p class="text-muted">Check back soon for new articles and insights about senior care.</p>
                    </div>
                </div>
            <?php else: ?>
                <?php 
                // Define colors for blog images
                $colors = [
                    'linear-gradient(135deg, #8b5cf6, #6366f1)',
                    'linear-gradient(135deg, #10b981, #059669)',
                    'linear-gradient(135deg, #f59e0b, #d97706)',
                    'linear-gradient(135deg, #ef4444, #dc2626)',
                    'linear-gradient(135deg, #8b5cf6, #7c3aed)',
                    'linear-gradient(135deg, #06b6d4, #0891b2)'
                ];
                
                // Define icons for blog images
                $icons = [
                    'fas fa-heartbeat',
                    'fas fa-utensils',
                    'fas fa-home',
                    'fas fa-pills',
                    'fas fa-hands-helping',
                    'fas fa-comments'
                ];
                
                $color_index = 0;
                $icon_index = 0;
                ?>
                
                <?php foreach ($regular_posts as $post): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="blog-card">
                        <div class="blog-image" style="background: <?php echo $colors[$color_index % count($colors)]; ?>;">
                            <i class="<?php echo $icons[$icon_index % count($icons)]; ?> fa-3x"></i>
                        </div>
                        <div class="blog-content">
                            <h3 class="blog-title"><?php echo htmlspecialchars($post['title']); ?></h3>
                            <p class="blog-excerpt"><?php echo htmlspecialchars($post['excerpt']); ?></p>
                            <div class="blog-meta">
                                <span><i class="fas fa-calendar me-1"></i> <?php echo date('F j, Y', strtotime($post['created_at'])); ?></span>
                                <span><i class="fas fa-clock me-1"></i> <?php echo htmlspecialchars($post['read_time']); ?></span>
                            </div>
                            <a href="blog-single.php?id=<?php echo $post['id']; ?>" class="btn btn-primary btn-sm mt-3">Read More</a>
                        </div>
                    </div>
                </div>
                <?php 
                    $color_index++;
                    $icon_index++;
                endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Newsletter Section -->
        <div class="row mt-5">
            <div class="col-lg-8 mx-auto">
                <div class="service-card text-center">
                    <div class="service-icon mx-auto mb-4">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <h3 class="text-primary mb-3">Stay Updated with Senior Care Tips</h3>
                    <p class="text-muted mb-4">Subscribe to our newsletter for the latest insights, tips, and resources on senior care and wellness.</p>
                    <form class="row g-3 justify-content-center">
                        <div class="col-md-8">
                            <input type="email" class="form-control" placeholder="Enter your email address" required>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-orange w-100">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>