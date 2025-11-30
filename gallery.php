<?php
// gallery.php
include 'includes/header.php';
include 'config/functions.php';

// Get gallery images using the function
$gallery_items = getGalleryImages();
?>

<!-- Hero Section -->
<section class="hero-section" style="min-height: 60vh;">
    <div class="container">
        <div class="row align-items-center min-vh-60">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="hero-title">Our Gallery</h1>
                <p class="hero-subtitle">
                    A visual journey through our facility, activities, and the compassionate care we provide
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section class="section-padding">
    <div class="container">
        <!-- Gallery Filter -->
        <div class="text-center mb-5">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary active" data-filter="all">All Photos</button>
                <button type="button" class="btn btn-outline-primary" data-filter="facility">Our Facility</button>
                <button type="button" class="btn btn-outline-primary" data-filter="activities">Activities</button>
                <button type="button" class="btn btn-outline-primary" data-filter="caregiving">Caregiving</button>
                <button type="button" class="btn btn-outline-primary" data-filter="team">Our Team</button>
                <button type="button" class="btn btn-outline-primary" data-filter="events">Events</button>
            </div>
        </div>

        <!-- Gallery Grid -->
        <div class="row g-4" id="gallery-grid">
            <?php if (!empty($gallery_items)): ?>
                <?php foreach ($gallery_items as $item): ?>
                <div class="col-md-6 col-lg-4 col-xl-3 gallery-item" data-category="<?php echo $item['category']; ?>">
                    <div class="gallery-card">
                        <div class="gallery-image-container">
                            <img src="<?php echo $item['image_url']; ?>" 
                                 alt="<?php echo htmlspecialchars($item['title']); ?>"
                                 class="gallery-image"
                                 data-bs-toggle="modal" 
                                 data-bs-target="#imageModal"
                                 data-title="<?php echo htmlspecialchars($item['title']); ?>"
                                 data-description="<?php echo htmlspecialchars($item['description']); ?>"
                                 data-image="<?php echo $item['image_url']; ?>">
                            <div class="gallery-overlay">
                                <div class="gallery-content">
                                    <i class="fas fa-search-plus fa-2x mb-2"></i>
                                    <h6><?php echo htmlspecialchars($item['title']); ?></h6>
                                    <span class="gallery-category"><?php echo ucfirst($item['category']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="service-card py-5">
                        <i class="fas fa-images fa-5x text-muted mb-4"></i>
                        <h3 class="text-primary">Gallery Coming Soon</h3>
                        <p class="text-muted lead">We are currently preparing amazing photos of our facility, activities, and care services.</p>
                        <p class="text-muted">Check back soon to see the Grace Senior Care experience through our photos.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" alt="" class="img-fluid rounded" id="imageModalImage">
                <p class="mt-3 text-muted" id="imageModalDescription"></p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter buttons
    const filterButtons = document.querySelectorAll('[data-filter]');
    const galleryItems = document.querySelectorAll('.gallery-item');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter items
            galleryItems.forEach(item => {
                if (filter === 'all' || item.getAttribute('data-category') === filter) {
                    item.style.display = 'block';
                    item.style.opacity = '1';
                    item.style.transform = 'scale(1)';
                } else {
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });
        });
    });
    
    // Modal functionality
    const galleryImages = document.querySelectorAll('.gallery-image[data-bs-toggle="modal"]');
    
    galleryImages.forEach(img => {
        img.addEventListener('click', function() {
            const title = this.getAttribute('data-title');
            const description = this.getAttribute('data-description');
            const imageSrc = this.getAttribute('data-image');
            
            document.getElementById('imageModalTitle').textContent = title;
            document.getElementById('imageModalDescription').textContent = description;
            document.getElementById('imageModalImage').src = imageSrc;
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>