<?php
// booking.php
include 'includes/header.php';
include 'config/functions.php';

$success = '';
$error = '';

// Process booking form
if ($_POST) {
    $service_id = $_POST['service_id'] ?? '';
    $customer_name = $_POST['customer_name'] ?? '';
    $customer_email = $_POST['customer_email'] ?? '';
    $customer_phone = $_POST['customer_phone'] ?? '';
    $customer_address = $_POST['customer_address'] ?? '';
    $booking_date = $_POST['booking_date'] ?? '';
    $booking_time = $_POST['booking_time'] ?? '';
    $special_notes = $_POST['special_notes'] ?? '';

    // Basic validation
    if (empty($customer_name) || empty($customer_email) || empty($customer_phone) || empty($booking_date) || empty($booking_time)) {
        $error = "Please fill in all required fields.";
    } else {
        if (addBooking($service_id, $customer_name, $customer_email, $customer_phone, $customer_address, $booking_date, $booking_time, $special_notes)) {
            $success = "Thank you! Your booking has been submitted successfully. We'll contact you within 24 hours to confirm.";
        } else {
            $error = "Sorry, there was an error processing your booking. Please try again.";
        }
    }
}

// Get services for dropdown
$services = getServices();
?>

<!-- Add Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-80">
            <div class="col-lg-8 mx-auto text-center">
                <div class="hero-content">
                    <h1 class="hero-title">
                        Book Our <span class="text-warning">Services</span>
                    </h1>
                    <p class="hero-subtitle">
                        Schedule professional senior care services with our certified caregivers. 
                        Fill out the form below and we'll get back to you within 24 hours.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="text-center mb-5">
                    <h2 class="section-title">Book a Service</h2>
                    <p class="section-subtitle">Schedule your appointment with our caring professionals</p>
                </div>

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
                        <form method="POST" action="booking.php">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="service_id" class="form-label">Service Required *</label>
                                    <select class="form-select" id="service_id" name="service_id" required>
                                        <option value="">Select a service</option>
                                        <?php
                                        if (!empty($services)) {
                                            foreach ($services as $service) {
                                                echo '<option value="' . $service['id'] . '">' . htmlspecialchars($service['name']) . ' - ₦' . number_format($service['price'], 2) . '</option>';
                                            }
                                        } else {
                                            echo '<option value="">No services available</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="customer_name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name" required placeholder="Enter your full name">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="customer_email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="customer_email" name="customer_email" required placeholder="Enter your email address">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="customer_phone" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control" id="customer_phone" name="customer_phone" required placeholder="Enter your phone number">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="customer_address" class="form-label">Address</label>
                                <textarea class="form-control" id="customer_address" name="customer_address" rows="3" placeholder="Enter your complete address..."></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="booking_date" class="form-label">Preferred Date *</label>
                                    <input type="date" class="form-control" id="booking_date" name="booking_date" min="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="booking_time" class="form-label">Preferred Time *</label>
                                    <select class="form-select" id="booking_time" name="booking_time" required>
                                        <option value="">Select time</option>
                                        <option value="08:00">8:00 AM</option>
                                        <option value="09:00">9:00 AM</option>
                                        <option value="10:00">10:00 AM</option>
                                        <option value="11:00">11:00 AM</option>
                                        <option value="12:00">12:00 PM</option>
                                        <option value="13:00">1:00 PM</option>
                                        <option value="14:00">2:00 PM</option>
                                        <option value="15:00">3:00 PM</option>
                                        <option value="16:00">4:00 PM</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="special_notes" class="form-label">Special Notes or Requirements</label>
                                <textarea class="form-control" id="special_notes" name="special_notes" rows="4" placeholder="Please let us know about any special requirements, medical conditions, or specific needs..."></textarea>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5 py-3">
                                    <i class="fas fa-calendar-check me-2"></i> Book Appointment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <h4 class="mb-4">Why Book With Us?</h4>
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="service-card text-center h-100">
                                <i class="fas fa-clock text-primary fa-2x mb-3"></i>
                                <h6>Quick Response</h6>
                                <small class="text-muted">We respond within 24 hours</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="service-card text-center h-100">
                                <i class="fas fa-user-md text-primary fa-2x mb-3"></i>
                                <h6>Professional Staff</h6>
                                <small class="text-muted">Certified caregivers</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="service-card text-center h-100">
                                <i class="fas fa-shield-alt text-primary fa-2x mb-3"></i>
                                <h6>Safe & Secure</h6>
                                <small class="text-muted">Your information is protected</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>