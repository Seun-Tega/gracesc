<?php
// services.php
include 'includes/header.php';
include 'config/functions.php';

// Get all services from database
$services = getServices();
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-80">
            <div class="col-lg-8 mx-auto text-center">
                <div class="hero-content">
                    <h1 class="hero-title">
                        Our <span class="highlight-orange">Comprehensive Services</span>
                    </h1>
                    <p class="hero-subtitle">
                        Complete senior care solutions designed to meet every aspect of elderly wellbeing - 
                        from medical care to emotional support and social engagement.
                    </p>
                    <div class="hero-buttons">
                        <a href="contact.php" class="btn btn-orange btn-lg me-3">
                            Book Service <i class="fas fa-calendar-plus ms-2"></i>
                        </a>
                        <a href="contact.php" class="btn btn-outline-light btn-lg">
                            Get Consultation <i class="fas fa-phone ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- All Services Grid -->
<section class="section-padding">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Complete Range of Services</h2>
            <p class="section-subtitle">Professional care for every aspect of senior wellbeing</p>
        </div>

        <div class="row g-4">
            <?php if (!empty($services)): ?>
                <?php foreach ($services as $service): ?>
                    <?php
                    // Get features as array
                    $features = !empty($service['features']) ? explode(',', $service['features']) : [];
                    ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card h-100">
                            <div class="service-icon">
                                <i class="<?php echo getServiceIcon($service['name']); ?>"></i>
                            </div>
                            <h5 class="service-title"><?php echo htmlspecialchars($service['name']); ?></h5>
                            <p class="service-desc"><?php echo htmlspecialchars($service['description']); ?></p>
                            <div class="service-price">₦<?php echo number_format($service['price'], 2); ?></div>
                            <div class="service-duration">Duration: <?php echo htmlspecialchars($service['duration']); ?></div>
                            
                            <?php if (!empty($features)): ?>
                            <div class="features-list">
                                <?php foreach ($features as $feature): ?>
                                    <li><?php echo htmlspecialchars(trim($feature)); ?></li>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                            
                            <div class="mt-3">
                                <a href="service-detail.php?id=<?php echo $service['id']; ?>" class="btn btn-primary btn-sm">Learn More</a>
                                <a href="contact.php?service=<?php echo urlencode($service['name']); ?>" class="btn btn-orange btn-sm ms-2">Book Now</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="service-card">
                        <i class="fas fa-concierge-bell fa-4x text-muted mb-4"></i>
                        <h4>Services Coming Soon</h4>
                        <p class="text-muted">We are currently updating our service offerings. Please check back soon or contact us for more information.</p>
                        <a href="contact.php" class="btn btn-primary">Contact Us</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Membership Plans Section -->
<section class="section-padding bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Annual Membership Plans</h2>
            <p class="section-subtitle">Choose the plan that works best for your ongoing care needs</p>
        </div>

        <div class="row justify-content-center">
            <!-- Basic Plan -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="service-card text-center h-100">
                    <div class="service-icon mx-auto">
                        <i class="fas fa-star"></i>
                    </div>
                    <h4 class="service-title">Basic Plan</h4>
                    <div class="service-price">₦180,000<span class="text-muted">/year</span></div>
                    <div class="service-duration">Save ₦60,000 vs monthly</div>
                    <div class="features-list text-start my-4">
                        <li>2 Caregiver visits per week</li>
                        <li>Basic health monitoring</li>
                        <li>Phone support</li>
                        <li>Monthly progress report</li>
                        <li>10% discount on additional services</li>
                    </div>
                    <a href="contact.php" class="btn btn-primary w-100">Get Started</a>
                </div>
            </div>

            <!-- Premium Plan -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="service-card text-center h-100 position-relative">
                    <span class="position-absolute top-0 start-50 translate-middle badge bg-orange">Most Popular</span>
                    <div class="service-icon mx-auto">
                        <i class="fas fa-crown"></i>
                    </div>
                    <h4 class="service-title">Premium Plan</h4>
                    <div class="service-price">₦300,000<span class="text-muted">/year</span></div>
                    <div class="service-duration">Save ₦120,000 vs monthly</div>
                    <div class="features-list text-start my-4">
                        <li>Daily caregiver visits</li>
                        <li>Comprehensive health monitoring</li>
                        <li>24/7 emergency support</li>
                        <li>Weekly progress reports</li>
                        <li>20% discount on additional services</li>
                        <li>Free annual health assessment</li>
                    </div>
                    <a href="contact.php" class="btn btn-orange w-100">Get Started</a>
                </div>
            </div>

            <!-- Family Plan -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="service-card text-center h-100">
                    <div class="service-icon mx-auto">
                        <i class="fas fa-users"></i>
                    </div>
                    <h4 class="service-title">Family Plan</h4>
                    <div class="service-price">₦420,000<span class="text-muted">/year</span></div>
                    <div class="service-duration">Save ₦180,000 vs monthly</div>
                    <div class="features-list text-start my-4">
                        <li>Care for 2 seniors</li>
                        <li>Unlimited caregiver access</li>
                        <li>Priority 24/7 support</li>
                        <li>Family counseling sessions</li>
                        <li>25% discount on additional services</li>
                        <li>Free caregiver training for family</li>
                    </div>
                    <a href="contact.php" class="btn btn-primary w-100">Get Started</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Service Categories -->
<section class="section-padding">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Service Categories</h2>
            <p class="section-subtitle">Organized care solutions for different needs</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="service-card text-center">
                    <div class="service-icon mx-auto">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h5>Medical Services</h5>
                    <ul class="list-unstyled mt-3">
                        <li>• Geriatric Assessment</li>
                        <li>• Physical Therapy</li>
                        <li>• Dementia Care</li>
                        <li>• Medical Monitoring</li>
                    </ul>
                </div>
            </div>

            <div class="col-md-4">
                <div class="service-card text-center">
                    <div class="service-icon mx-auto">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h5>Emotional Support</h5>
                    <ul class="list-unstyled mt-3">
                        <li>• Counseling</li>
                        <li>• Grief Counseling</li>
                        <li>• Family Support</li>
                        <li>• Mental Health Care</li>
                    </ul>
                </div>
            </div>

            <div class="col-md-4">
                <div class="service-card text-center">
                    <div class="service-icon mx-auto">
                        <i class="fas fa-home"></i>
                    </div>
                    <h5>Daily Living Support</h5>
                    <ul class="list-unstyled mt-3">
                        <li>• Caregiver Services</li>
                        <li>• Day-Centre Activities</li>
                        <li>• Social Engagement</li>
                        <li>• Companionship</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section-padding" style="background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);">
    <div class="container">
        <div class="row justify-content-center text-center text-white">
            <div class="col-lg-8">
                <h2 class="mb-4">Not Sure Which Service You Need?</h2>
                <p class="mb-4">Contact us for a free consultation and we'll help you choose the right care solution for your loved one.</p>
                <div class="cta-buttons">
                    <a href="contact.php" class="btn btn-orange btn-lg me-3">
                        <i class="fas fa-calendar me-2"></i> Free Consultation
                    </a>
                    <a href="tel:+2348033927717" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-phone me-2"></i> Call +234 803 392 7717
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>