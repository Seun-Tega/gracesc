<?php
// contact.php
include 'includes/header.php';
include 'config/functions.php';

$success = '';
$error = '';

// Process contact form
if ($_POST) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    // Basic validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = "Please fill in all required fields.";
    } else {
        if (addContact($name, $email, $phone, $subject, $message)) {
            $success = "Thank you for your message! We'll get back to you within 24 hours.";
        } else {
            $error = "Sorry, there was an error sending your message. Please try again.";
        }
    }
}
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-80">
            <div class="col-lg-8 mx-auto text-center">
                <div class="hero-content">
                    <h1 class="hero-title">
                        Contact <span class="text-warning">Us</span>
                    </h1>
                    <p class="hero-subtitle">
                        Get in touch with our caring team. We're here to answer your questions and provide the best senior care solutions for your loved ones.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Get In Touch</h2>
            <p class="section-subtitle">We're here to help you with all your senior care needs</p>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="booking-form card shadow-lg border-0">
                    <div class="card-body p-4 p-md-5">
                        <form method="POST" action="contact.php">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" required placeholder="Enter your full name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="email" name="email" required placeholder="Enter your email address">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter your phone number">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="subject" class="form-label">Subject *</label>
                                    <select class="form-select" id="subject" name="subject" required>
                                        <option value="">Select a subject</option>
                                        <option value="General Inquiry">General Inquiry</option>
                                        <option value="Service Information">Service Information</option>
                                        <option value="Booking Question">Booking Question</option>
                                        <option value="Emergency">Emergency</option>
                                        <option value="Partnership">Partnership</option>
                                        <option value="Career Opportunity">Career Opportunity</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="message" class="form-label">Message *</label>
                                <textarea class="form-control" id="message" name="message" rows="6" required placeholder="Please tell us how we can help you or your loved one..."></textarea>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5 py-3">
                                    <i class="fas fa-paper-plane me-2"></i> Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="row mt-5">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="service-card text-center h-100">
                    <i class="fas fa-map-marker-alt text-primary fa-2x mb-3"></i>
                    <h5>Our Location</h5>
                    <p class="text-muted">Bethesda Hospital Complex<br>Ilobu Road, Service Area<br>Osogbo, Nigeria</p>
                    <a href="https://maps.google.com/?q=Bethesda+Hospital+Complex+Osogbo" class="btn btn-outline-primary btn-sm mt-2" target="_blank">
                        <i class="fas fa-directions me-1"></i> Get Directions
                    </a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="service-card text-center h-100">
                    <i class="fas fa-phone text-primary fa-2x mb-3"></i>
                    <h5>Phone Numbers</h5>
                    <p class="text-muted">
                        Main: +234 803 392 7717<br>
                        <small>24/7 Care Available</small>
                    </p>
                    <div class="mt-3">
                        <a href="tel:+2348033927717" class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-phone me-1"></i> Call Now
                        </a>
                        <a href="https://wa.me/2348033927717" class="btn btn-success btn-sm" target="_blank">
                            <i class="fab fa-whatsapp me-1"></i> WhatsApp
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="service-card text-center h-100">
                    <i class="fas fa-envelope text-primary fa-2x mb-3"></i>
                    <h5>Email Address</h5>
                    <p class="text-muted">
                        graceseniorcare77@gmail.com<br>
                        <small>We respond within 24 hours</small>
                    </p>
                    <a href="mailto:graceseniorcare77@gmail.com" class="btn btn-outline-primary btn-sm mt-2">
                        <i class="fas fa-envelope me-1"></i> Send Email
                    </a>
                </div>
            </div>
        </div>

        <!-- Business Hours -->
        <div class="row mt-4">
            <div class="col-lg-8 mx-auto">
                <div class="service-card text-center">
                    <i class="fas fa-clock text-primary fa-2x mb-3"></i>
                    <h5>Business Hours</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted mb-1"><strong>Monday - Friday:</strong></p>
                            <p class="text-muted">8:00 AM - 6:00 PM</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1"><strong>Saturday:</strong></p>
                            <p class="text-muted">9:00 AM - 4:00 PM</p>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0">
                        <strong>Emergency Services:</strong> Available 24/7<br>
                        <small class="text-warning">Trained and Certified Caregivers Always Available</small>
                    </p>
                </div>
            </div>
        </div>

        <!-- Emergency Contact -->
        <div class="text-center mt-5">
            <div class="alert alert-warning border-0">
                <h5 class="alert-heading">
                    <i class="fas fa-exclamation-triangle me-2"></i>24/7 Emergency Contact
                </h5>
                <p class="mb-3">For urgent medical emergencies or immediate assistance, call our emergency line:</p>
                <a href="tel:+2348033927717" class="btn btn-danger btn-lg px-4">
                    <i class="fas fa-phone me-2"></i> Emergency: +234 803 392 7717
                </a>
                <p class="mt-3 mb-0 small">
                    <i class="fas fa-shield-alt me-1"></i>
                    Immediate response from our certified caregivers
                </p>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>