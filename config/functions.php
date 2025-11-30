<?php
// config/functions.php

// Include database connection only if not already included
if (!class_exists('Database')) {
    include_once 'database.php';
}

function getServices($limit = null) {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "SELECT * FROM services WHERE is_active = TRUE ORDER BY created_at DESC";
        if ($limit) {
            $query .= " LIMIT " . intval($limit);
        }
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error in getServices: " . $e->getMessage());
        return [];
    }
}

function getServiceById($id) {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "SELECT * FROM services WHERE id = :id AND is_active = TRUE";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error in getServiceById: " . $e->getMessage());
        return false;
    }
}

function getServiceIcon($serviceName) {
    $icons = [
        'geriatric' => 'fas fa-stethoscope',
        'dementia' => 'fas fa-brain',
        'therapy' => 'fas fa-heartbeat',
        'counseling' => 'fas fa-comments',
        'grief' => 'fas fa-hands-helping',
        'caregiver' => 'fas fa-user-nurse',
        'activities' => 'fas fa-users',
        'training' => 'fas fa-graduation-cap',
        'membership' => 'fas fa-crown',
        'assessment' => 'fas fa-clipboard-check',
        'physical' => 'fas fa-running',
        'medical' => 'fas fa-stethoscope',
        'nutrition' => 'fas fa-utensils',
        'personal' => 'fas fa-hands',
        'home' => 'fas fa-home',
        'emergency' => 'fas fa-first-aid',
        'day-centre' => 'fas fa-users',
        'annual' => 'fas fa-crown'
    ];
    
    $serviceName = strtolower($serviceName);
    
    foreach ($icons as $keyword => $icon) {
        if (strpos($serviceName, $keyword) !== false) {
            return $icon;
        }
    }
    
    return 'fas fa-hands-helping'; // Default icon
}

function getTeamMembers($limit = null) {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "SELECT * FROM team_members WHERE is_active = TRUE ORDER BY created_at DESC";
        if ($limit) {
            $query .= " LIMIT " . intval($limit);
        }
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt;
    } catch (PDOException $e) {
        error_log("Database error in getTeamMembers: " . $e->getMessage());
        return false;
    }
}

function getGalleryImages($limit = null) {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        if ($db === null) {
            error_log("Database connection is null");
            return [];
        }
        
        $query = "SELECT * FROM gallery WHERE is_active = TRUE ORDER BY created_at DESC";
        if ($limit) {
            $query .= " LIMIT " . intval($limit);
        }
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {
        error_log("Database error in getGalleryImages: " . $e->getMessage());
        return [];
    } catch (Exception $e) {
        error_log("General error in getGalleryImages: " . $e->getMessage());
        return [];
    }
}

function addBooking($service_id, $customer_name, $customer_email, $customer_phone, $customer_address, $booking_date, $booking_time, $special_notes) {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "INSERT INTO bookings SET service_id=:service_id, customer_name=:customer_name, customer_email=:customer_email, customer_phone=:customer_phone, customer_address=:customer_address, booking_date=:booking_date, booking_time=:booking_time, special_notes=:special_notes";
        
        $stmt = $db->prepare($query);
        
        $stmt->bindParam(":service_id", $service_id);
        $stmt->bindParam(":customer_name", $customer_name);
        $stmt->bindParam(":customer_email", $customer_email);
        $stmt->bindParam(":customer_phone", $customer_phone);
        $stmt->bindParam(":customer_address", $customer_address);
        $stmt->bindParam(":booking_date", $booking_date);
        $stmt->bindParam(":booking_time", $booking_time);
        $stmt->bindParam(":special_notes", $special_notes);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    } catch (PDOException $e) {
        error_log("Database error in addBooking: " . $e->getMessage());
        return false;
    }
}

function addContact($name, $email, $phone, $subject, $message) {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "INSERT INTO contacts SET name=:name, email=:email, phone=:phone, subject=:subject, message=:message";
        
        $stmt = $db->prepare($query);
        
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":subject", $subject);
        $stmt->bindParam(":message", $message);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    } catch (PDOException $e) {
        error_log("Database error in addContact: " . $e->getMessage());
        return false;
    }
}

function getBlogPosts($limit = null) {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "SELECT * FROM blog_posts WHERE is_active = TRUE ORDER BY created_at DESC";
        if ($limit) {
            $query .= " LIMIT " . intval($limit);
        }
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt;
    } catch (PDOException $e) {
        error_log("Database error in getBlogPosts: " . $e->getMessage());
        return false;
    }
}

function submitApplication($full_name, $email, $phone, $address, $position, $experience, $qualifications, $availability, $message, $resume_path) {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "INSERT INTO caregiver_applications SET full_name=:full_name, email=:email, phone=:phone, address=:address, position=:position, experience=:experience, qualifications=:qualifications, availability=:availability, message=:message, resume_path=:resume_path";
        
        $stmt = $db->prepare($query);
        
        $stmt->bindParam(":full_name", $full_name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":address", $address);
        $stmt->bindParam(":position", $position);
        $stmt->bindParam(":experience", $experience);
        $stmt->bindParam(":qualifications", $qualifications);
        $stmt->bindParam(":availability", $availability);
        $stmt->bindParam(":message", $message);
        $stmt->bindParam(":resume_path", $resume_path);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    } catch (PDOException $e) {
        error_log("Database error in submitApplication: " . $e->getMessage());
        return false;
    }
}
function addContacts($name, $email, $phone, $subject, $message) {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "INSERT INTO contacts SET name=:name, email=:email, phone=:phone, subject=:subject, message=:message";
        
        $stmt = $db->prepare($query);
        
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":subject", $subject);
        $stmt->bindParam(":message", $message);
        
        if($stmt->execute()) {
            // Send email notification
            $to = "graceseniorcare77@gmail.com";
            $email_subject = "New Contact Form: " . $subject;
            $email_body = "
            New contact form submission:
            
            Name: $name
            Email: $email
            Phone: " . ($phone ?: 'Not provided') . "
            Subject: $subject
            
            Message:
            $message
            
            Submitted on: " . date('Y-m-d H:i:s');
            
            $headers = "From: $email\r\n";
            $headers .= "Reply-To: $email\r\n";
            
            // Uncomment to enable email sending
            // mail($to, $email_subject, $email_body, $headers);
            
            return true;
        }
        return false;
    } catch (PDOException $e) {
        error_log("Database error in addContact: " . $e->getMessage());
        return false;
    }
}
?>