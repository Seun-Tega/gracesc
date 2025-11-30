<?php
// careers.php
include 'includes/header.php';
include 'config/database.php';

// Handle form success/error messages
$success = isset($_GET['success']);
$error = isset($_GET['error']);
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-80">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="hero-title">
                        Join Our <span class="highlight-orange">Care Team</span>
                    </h1>
                    <p class="hero-subtitle">
                        Become a certified caregiver and make a difference in seniors' lives. 
                        We're looking for compassionate professionals to join our team.
                    </p>
                    <div class="hero-buttons">
                        <a href="#application-form" class="btn btn-orange btn-lg me-3">
                            Apply Now <i class="fas fa-arrow-down ms-2"></i>
                        </a>
                        <a href="about.php" class="btn btn-outline-light btn-lg">
                            Learn About Us
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="hero-image">
                    <i class="fas fa-users fa-10x text-white opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="section-padding bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Why Work With Us?</h2>
            <p class="section-subtitle">Join a team that values compassion and professionalism</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="service-card text-center h-100">
                    <div class="service-icon mx-auto">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h5>Professional Training</h5>
                    <p>Get certified and enhance your caregiving skills with our training programs</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="service-card text-center h-100">
                    <div class="service-icon mx-auto">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <h5>Make a Difference</h5>
                    <p>Help seniors maintain their dignity and independence while improving their quality of life</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="service-card text-center h-100">
                    <div class="service-icon mx-auto">
                        <i class="fas fa-users"></i>
                    </div>
                    <h5>Supportive Team</h5>
                    <p>Work with experienced professionals in a collaborative environment</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Success/Error Messages -->
<?php if ($success): ?>
<div class="container mt-4">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Your application has been submitted successfully. We'll review it and get back to you soon.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="container mt-4">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> There was a problem submitting your application. Please try again or contact us directly.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php endif; ?>

<!-- Application Form Section -->
<section id="application-form" class="section-padding">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Caregiver Application Form</h2>
            <p class="section-subtitle">Complete this form to apply for a caregiver position</p>
        </div>
        
        <div class="application-form-container">
            <div class="service-card">
                <form id="caregiverApplication" method="POST" action="submit-application.php">
                    <!-- Personal Information -->
                    <div class="form-section mb-5">
                        <h4 class="text-primary mb-4">Personal Information</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name *</label>
                                <input type="text" class="form-control" name="full_name" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Age *</label>
                                <input type="number" class="form-control" name="age" required min="18" max="65">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Date of Birth *</label>
                                <input type="date" class="form-control" name="dob" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sex *</label>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sex" value="Male" id="male" required>
                                        <label class="form-check-label" for="male">Male</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sex" value="Female" id="female" required>
                                        <label class="form-check-label" for="female">Female</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Religion</label>
                                <input type="text" class="form-control" name="religion">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Denomination</label>
                                <input type="text" class="form-control" name="denomination">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">NIN *</label>
                                <input type="text" class="form-control" name="nin" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Permanent Home Address *</label>
                                <input type="text" class="form-control" name="address" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Street</label>
                                <input type="text" class="form-control" name="street">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">City</label>
                                <input type="text" class="form-control" name="city">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">State</label>
                                <input type="text" class="form-control" name="state">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Hometown</label>
                                <input type="text" class="form-control" name="hometown">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control" name="phone" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Facebook Account</label>
                                <input type="text" class="form-control" name="facebook">
                            </div>
                        </div>
                    </div>

                    <!-- Education -->
                    <div class="form-section mb-5">
                        <h4 class="text-primary mb-4">Education</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Secondary School</label>
                                <input type="text" class="form-control" name="secondary_school">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Graduation Date</label>
                                <input type="date" class="form-control" name="secondary_date">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">College/University</label>
                                <input type="text" class="form-control" name="college">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Graduation Date</label>
                                <input type="date" class="form-control" name="college_date">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Position Applying For *</label>
                                <input type="text" class="form-control" name="position" required>
                            </div>
                        </div>
                    </div>

                    <!-- Work Experience -->
                    <div class="form-section mb-5">
                        <h4 class="text-primary mb-4">Work Experience</h4>
                        <div id="workExperience">
                            <div class="work-entry mb-3 p-3 border rounded">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Company Name</label>
                                        <input type="text" class="form-control" name="company[]">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Date of Employment</label>
                                        <input type="date" class="form-control" name="employment_date[]">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Address</label>
                                        <input type="text" class="form-control" name="company_address[]">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Role/Title</label>
                                        <input type="text" class="form-control" name="role[]">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addWorkExperience()">
                            <i class="fas fa-plus me-2"></i>Add Another Job
                        </button>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Reason for Leaving Previous Position</label>
                        <textarea class="form-control" name="reason_leaving" rows="3"></textarea>
                    </div>

                    <!-- Guarantors -->
                    <div class="form-section mb-5">
                        <h4 class="text-primary mb-4">Guarantors</h4>
                        
                        <h5 class="mb-3">Guarantor A</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="guarantor_a_first">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Middle Name</label>
                                <input type="text" class="form-control" name="guarantor_a_middle">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="guarantor_a_last">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Relationship</label>
                                <input type="text" class="form-control" name="guarantor_a_relationship">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" name="guarantor_a_phone">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Residential Address</label>
                                <input type="text" class="form-control" name="guarantor_a_residential">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Occupation/Position</label>
                                <input type="text" class="form-control" name="guarantor_a_occupation">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="guarantor_a_email">
                            </div>
                        </div>

                        <h5 class="mb-3">Guarantor B</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="guarantor_b_first">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Middle Name</label>
                                <input type="text" class="form-control" name="guarantor_b_middle">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="guarantor_b_last">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Relationship</label>
                                <input type="text" class="form-control" name="guarantor_b_relationship">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" name="guarantor_b_phone">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Residential Address</label>
                                <input type="text" class="form-control" name="guarantor_b_residential">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Occupation/Position</label>
                                <input type="text" class="form-control" name="guarantor_b_occupation">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="guarantor_b_email">
                            </div>
                        </div>
                    </div>

                    <!-- Next of Kin -->
                    <div class="form-section mb-5">
                        <h4 class="text-primary mb-4">Next of Kin</h4>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="kin_first">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Middle Name</label>
                                <input type="text" class="form-control" name="kin_middle">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="kin_last">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Relationship</label>
                                <input type="text" class="form-control" name="kin_relationship">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" name="kin_phone">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Home Address</label>
                                <input type="text" class="form-control" name="kin_address">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="kin_email">
                            </div>
                        </div>
                    </div>

                    <!-- Declaration -->
                    <div class="form-section mb-5">
                        <h4 class="text-primary mb-4">Declaration</h4>
                        <div class="declaration-text p-4 border rounded bg-light">
                            <p class="mb-3"><strong>PLEASE READ CAREFULLY, INITIAL EACH PARAGRAPH AND SIGN BELOW.</strong></p>
                            <p class="mb-3">I hereby certify that I have not knowingly withheld any information that might adversely affect my chances of employment and the answers given by the questions and statements on this applications are true and correct. I hereby authorize Grace Senior Social Care to verify all information on this application. I also authorize my guarantors to give Grace Senior Social Care any information they may have regarding me.</p>
                            <p class="mb-3">I further certify that I have personally completed this application or on any document used to secure or misstatement of material fact in this application or on any document used to secure employment shall be grounds for rejection of this application or for immediate discharge if I am employed, regardless of the time elapsed before delivery.</p>
                            <p class="mb-3">I understand that nothing contained in this application, or conveyed during any interview is intended to create an employment contract between me and the company. I understand that if employed, salary will be paid for the job done and every aspect of my employment with the company shall not be on at will basis, meaning that the company may terminate my employment for discipline charges with or without prior notice.</p>
                            
                            <div class="row g-3 mt-4">
                                <div class="col-md-6">
                                    <label class="form-label">Applicant's Signature *</label>
                                    <input type="text" class="form-control" name="signature" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date *</label>
                                    <input type="date" class="form-control" name="application_date" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-orange btn-lg">
                            <i class="fas fa-paper-plane me-2"></i> Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
function addWorkExperience() {
    const workExp = document.getElementById('workExperience');
    const newEntry = document.createElement('div');
    newEntry.className = 'work-entry mb-3 p-3 border rounded';
    newEntry.innerHTML = `
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Company Name</label>
                <input type="text" class="form-control" name="company[]">
            </div>
            <div class="col-md-3">
                <label class="form-label">Date of Employment</label>
                <input type="date" class="form-control" name="employment_date[]">
            </div>
            <div class="col-md-3">
                <label class="form-label">Address</label>
                <input type="text" class="form-control" name="company_address[]">
            </div>
            <div class="col-md-2">
                <label class="form-label">Role/Title</label>
                <input type="text" class="form-control" name="role[]">
            </div>
        </div>
    `;
    workExp.appendChild(newEntry);
}

// Form validation
document.getElementById('caregiverApplication').addEventListener('submit', function(e) {
    const requiredFields = this.querySelectorAll('[required]');
    let valid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            valid = false;
            field.classList.add('is-invalid');
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    if (!valid) {
        e.preventDefault();
        alert('Please fill in all required fields marked with *');
    }
});
</script>

<?php include 'includes/footer.php'; ?>