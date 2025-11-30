<?php
// service-detail.php
include 'includes/header.php';
include 'config/functions.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: services.php");
    exit;
}

$service_id = $_GET['id'];
$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM services WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(":id", $service_id);
$stmt->execute();

if ($stmt->rowCount() == 0) {
    header("Location: services.php");
    exit;
}

$service = $stmt->fetch(PDO::FETCH_ASSOC);
$features = explode(',', $service['features']);

// Define icons for each service type
$serviceIcons = [
    'Geriatric Assessment' => 'fas fa-stethoscope',
    'Dementia Care' => 'fas fa-brain',
    'Physical Therapy' => 'fas fa-heartbeat',
    'Counseling' => 'fas fa-comments',
    'Grief Counseling' => 'fas fa-hands-helping',
    'Caregiver Services' => 'fas fa-user-nurse',
    'Day-centre Activities' => 'fas fa-users',
    'Caregiver Training' => 'fas fa-graduation-cap',
    'Annual Membership Plans' => 'fas fa-crown'
];

$icon = $serviceIcons[$service['name']] ?? 'fas fa-hands-helping';
?>

<!-- Service Hero Section -->
<!-- Service Hero Section with Background Image -->
<section class="service-hero" style="background: linear-gradient(rgba(30, 64, 175, 0.8), rgba(30, 64, 175, 0.8)), url('images/7.jpg') center/cover no-repeat;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center text-white">
                <div class="service-icon-large mx-auto mb-4">
                    <i class="<?php echo $icon; ?>"></i>
                </div>
                <h1 class="hero-title"><?php echo htmlspecialchars($service['name']); ?></h1>
                <p class="hero-subtitle"><?php echo htmlspecialchars($service['description']); ?></p>
                <div class="mt-4">
                    <span class="service-price display-4 fw-bold">₦<?php echo number_format($service['price'], 2); ?></span>
                    <span class="service-duration ms-3 fs-5 opacity-90">/ <?php echo htmlspecialchars($service['duration']); ?></span>
                </div>
                <div class="mt-3">
                    <a href="booking.php?service_id=<?php echo $service['id']; ?>" class="btn btn-orange btn-lg me-3">
                        <i class="fas fa-calendar-plus me-2"></i> Book Now
                    </a>
                    <a href="services.php" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-arrow-left me-2"></i> All Services
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Service Details -->
<section class="section-padding">
    <div class="container">
        <div class="service-detail-card">
            <div class="row">
                <div class="col-lg-8">
                    <h3 class="text-primary mb-4">Service Overview</h3>
                    <p class="lead mb-4"><?php echo htmlspecialchars($service['detailed_description']); ?></p>
                    
                    <h4 class="text-primary mb-4">What's Included</h4>
                    <div class="feature-grid">
                        <?php foreach ($features as $feature): ?>
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1"><?php echo htmlspecialchars(trim($feature)); ?></h6>
                                    <small class="text-muted">Professional service</small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Service Process -->
                    <div class="mt-5">
                        <h4 class="text-primary mb-4">How It Works</h4>
                        <div class="process-steps">
                            <div class="process-step">
                                <div class="step-number">1</div>
                                <div class="step-content">
                                    <h6>Initial Consultation</h6>
                                    <p class="text-muted mb-0">Free assessment to understand your specific needs and requirements</p>
                                </div>
                            </div>
                            <div class="process-step">
                                <div class="step-number">2</div>
                                <div class="step-content">
                                    <h6>Personalized Care Plan</h6>
                                    <p class="text-muted mb-0">We create a customized care plan tailored to your loved one's needs</p>
                                </div>
                            </div>
                            <div class="process-step">
                                <div class="step-number">3</div>
                                <div class="step-content">
                                    <h6>Caregiver Matching</h6>
                                    <p class="text-muted mb-0">We match you with the most suitable caregiver based on needs and personality</p>
                                </div>
                            </div>
                            <div class="process-step">
                                <div class="step-number">4</div>
                                <div class="step-content">
                                    <h6>Ongoing Support</h6>
                                    <p class="text-muted mb-0">Regular monitoring and adjustments to ensure optimal care quality</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Service Benefits -->
                    <div class="mt-5">
                        <h4 class="text-primary mb-4">Key Benefits</h4>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-shield-alt text-primary mt-1 me-3 fs-5"></i>
                                    <div>
                                        <h6>Safety & Security</h6>
                                        <p class="text-muted mb-0">Trained professionals ensure complete safety and wellbeing</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-heart text-primary mt-1 me-3 fs-5"></i>
                                    <div>
                                        <h6>Compassionate Care</h6>
                                        <p class="text-muted mb-0">Heartfelt service with genuine concern and empathy</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-clock text-primary mt-1 me-3 fs-5"></i>
                                    <div>
                                        <h6>Flexible Scheduling</h6>
                                        <p class="text-muted mb-0">Available when you need us most with customizable timing</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-user-md text-primary mt-1 me-3 fs-5"></i>
                                    <div>
                                        <h6>Expert Caregivers</h6>
                                        <p class="text-muted mb-0">Certified and experienced professionals with specialized training</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-home text-primary mt-1 me-3 fs-5"></i>
                                    <div>
                                        <h6>Comfort of Home</h6>
                                        <p class="text-muted mb-0">Receive care in the familiar and comfortable home environment</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-sync-alt text-primary mt-1 me-3 fs-5"></i>
                                    <div>
                                        <h6>Continuous Monitoring</h6>
                                        <p class="text-muted mb-0">Regular progress tracking and care plan adjustments</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="service-card text-center sticky-top" style="top: 120px;">
                        <div class="service-icon mx-auto">
                            <i class="<?php echo $icon; ?>"></i>
                        </div>
                        <h4 class="service-title">Book This Service</h4>
                        <div class="service-price mb-3">₦<?php echo number_format($service['price'], 2); ?></div>
                        <div class="service-duration mb-4">Duration: <?php echo htmlspecialchars($service['duration']); ?></div>
                        
                        <div class="mb-4">
                            <a href="booking.php?service_id=<?php echo $service['id']; ?>" class="btn btn-orange w-100 btn-lg mb-3">
                                <i class="fas fa-calendar-plus me-2"></i> Book Appointment
                            </a>
                            <a href="contact.php" class="btn btn-outline-primary w-100 mb-2">
                                <i class="fas fa-question-circle me-2"></i> Ask Questions
                            </a>
                            <a href="tel:+234800000000" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-phone me-2"></i> Call Now
                            </a>
                        </div>

                        <div class="service-features">
                            <h6 class="text-start mb-3">Service Highlights:</h6>
                            <div class="text-start">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    <small>Certified professional caregivers</small>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    <small>Free initial consultation</small>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    <small>24/7 customer support</small>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    <small>Flexible scheduling options</small>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    <small>Regular progress reports</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check text-success me-2"></i>
                                    <small>Insurance accepted</small>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Response time: Within 2 hours
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="row mt-5">
            <div class="col-lg-10 mx-auto">
                <div class="service-card">
                    <h3 class="text-center text-primary mb-5">Frequently Asked Questions</h3>
                    
                    <div class="accordion" id="serviceFAQ">
                        <div class="accordion-item border-0 mb-3">
                            <h5 class="accordion-header">
                                <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    What qualifications do your caregivers have?
                                </button>
                            </h5>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#serviceFAQ">
                                <div class="accordion-body">
                                    All our caregivers are certified professionals with extensive training in senior care. They undergo rigorous background checks, first aid certification, and receive continuous training to stay updated with the latest care techniques and best practices.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 mb-3">
                            <h5 class="accordion-header">
                                <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    How quickly can service begin after booking?
                                </button>
                            </h5>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#serviceFAQ">
                                <div class="accordion-body">
                                    We can typically begin services within 24-48 hours of your booking confirmation. For emergency situations, we offer same-day service initiation to ensure your loved one receives care when they need it most.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 mb-3">
                            <h5 class="accordion-header">
                                <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Is there a contract or long-term commitment required?
                                </button>
                            </h5>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#serviceFAQ">
                                <div class="accordion-body">
                                    We offer flexible arrangements to suit your needs. You can choose one-time services, short-term care, or long-term arrangements. There are no long-term contracts required, and you can adjust or cancel services with proper notice.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 mb-3">
                            <h5 class="accordion-header">
                                <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    What if I need to cancel or reschedule an appointment?
                                </button>
                            </h5>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#serviceFAQ">
                                <div class="accordion-body">
                                    You can cancel or reschedule your appointment with at least 24 hours notice without any charges. We understand that circumstances can change, and we're flexible to accommodate your needs while ensuring our caregivers' schedules are respected.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 mb-3">
                            <h5 class="accordion-header">
                                <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                    Do you provide services on weekends and holidays?
                                </button>
                            </h5>
                            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#serviceFAQ">
                                <div class="accordion-body">
                                    Yes, we provide services 7 days a week, including weekends and holidays. Our 24/7 support ensures that your loved one receives consistent care regardless of the day. Holiday schedules may have adjusted rates, which we communicate transparently.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0">
                            <h5 class="accordion-header">
                                <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
                                    What happens if my caregiver is unavailable?
                                </button>
                            </h5>
                            <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#serviceFAQ">
                                <div class="accordion-body">
                                    We maintain a team of qualified backup caregivers to ensure continuous service. If your regular caregiver is unavailable, we'll provide a equally qualified replacement and ensure they're briefed on your loved one's specific needs and care plan before the visit.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Services -->
<section class="section-padding bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Related Services</h2>
            <p class="section-subtitle">You might also be interested in these complementary services</p>
        </div>

        <div class="row g-4">
            <?php
            // Get related services (excluding current service)
            $related_query = "SELECT * FROM services WHERE id != :id ORDER BY RAND() LIMIT 3";
            $related_stmt = $db->prepare($related_query);
            $related_stmt->bindParam(':id', $service_id);
            $related_stmt->execute();
            
            while ($related_service = $related_stmt->fetch(PDO::FETCH_ASSOC)) {
                $related_icon = $serviceIcons[$related_service['name']] ?? 'fas fa-hands-helping';
                ?>
                <div class="col-lg-4 col-md-6">
                    <div class="service-card h-100">
                        <div class="service-icon">
                            <i class="<?php echo $related_icon; ?>"></i>
                        </div>
                        <h5 class="service-title"><?php echo htmlspecialchars($related_service['name']); ?></h5>
                        <p class="service-desc"><?php echo htmlspecialchars($related_service['description']); ?></p>
                        <div class="service-price">₦<?php echo number_format($related_service['price'], 2); ?></div>
                        <div class="service-duration">Duration: <?php echo htmlspecialchars($related_service['duration']); ?></div>
                        <div class="mt-3">
                            <a href="service-detail.php?id=<?php echo $related_service['id']; ?>" class="btn btn-primary btn-sm">
                                Learn More
                            </a>
                            <a href="booking.php?service_id=<?php echo $related_service['id']; ?>" class="btn btn-orange btn-sm ms-2">
                                Book Now
                            </a>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section-padding">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h2 class="section-title">Ready to Get Started?</h2>
                <p class="section-subtitle">Take the first step towards better care for your loved one</p>
                <div class="mt-4">
                    <a href="booking.php?service_id=<?php echo $service['id']; ?>" class="btn btn-orange btn-lg me-3">
                        <i class="fas fa-calendar-check me-2"></i> Book This Service
                    </a>
                    <a href="contact.php" class="btn btn-primary btn-lg me-3">
                        <i class="fas fa-phone me-2"></i> Contact Us
                    </a>
                    <a href="services.php" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-list me-2"></i> All Services
                    </a>
                </div>
                <div class="mt-4">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Free consultation available. No commitment required.
                    </small>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>