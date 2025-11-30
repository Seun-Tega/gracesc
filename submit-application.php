<?php
// submit-application.php
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    
    try {
        $db->beginTransaction();
        
        // Insert main application
        $query = "INSERT INTO caregiver_applications SET 
                  full_name=?, age=?, dob=?, sex=?, religion=?, denomination=?, nin=?,
                  address=?, street=?, city=?, state=?, hometown=?, phone=?, email=?,
                  facebook=?, secondary_school=?, secondary_date=?, college=?, college_date=?,
                  position=?, reason_leaving=?, kin_first=?, kin_middle=?, kin_last=?,
                  kin_relationship=?, kin_phone=?, kin_address=?, kin_email=?, signature=?,
                  application_date=?";
        
        $stmt = $db->prepare($query);
        $stmt->execute([
            $_POST['full_name'], $_POST['age'], $_POST['dob'], $_POST['sex'],
            $_POST['religion'], $_POST['denomination'], $_POST['nin'],
            $_POST['address'], $_POST['street'], $_POST['city'], $_POST['state'],
            $_POST['hometown'], $_POST['phone'], $_POST['email'], $_POST['facebook'],
            $_POST['secondary_school'], $_POST['secondary_date'], $_POST['college'],
            $_POST['college_date'], $_POST['position'], $_POST['reason_leaving'],
            $_POST['kin_first'], $_POST['kin_middle'], $_POST['kin_last'],
            $_POST['kin_relationship'], $_POST['kin_phone'], $_POST['kin_address'],
            $_POST['kin_email'], $_POST['signature'], $_POST['application_date']
        ]);
        
        $application_id = $db->lastInsertId();
        
        // Insert work experience
        if (isset($_POST['company'])) {
            $work_stmt = $db->prepare("INSERT INTO application_work_experience 
                                     (application_id, company_name, employment_date, company_address, role) 
                                     VALUES (?, ?, ?, ?, ?)");
            
            foreach ($_POST['company'] as $index => $company) {
                if (!empty($company)) {
                    $work_stmt->execute([
                        $application_id,
                        $company,
                        $_POST['employment_date'][$index] ?? null,
                        $_POST['company_address'][$index] ?? null,
                        $_POST['role'][$index] ?? null
                    ]);
                }
            }
        }
        
        // Insert guarantors
        $guarantor_stmt = $db->prepare("INSERT INTO application_guarantors 
                                      (application_id, guarantor_type, first_name, middle_name, last_name, 
                                       relationship, phone, residential_address, occupation, email) 
                                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Guarantor A
        if (!empty($_POST['guarantor_a_first'])) {
            $guarantor_stmt->execute([
                $application_id, 'A',
                $_POST['guarantor_a_first'], $_POST['guarantor_a_middle'], $_POST['guarantor_a_last'],
                $_POST['guarantor_a_relationship'], $_POST['guarantor_a_phone'],
                $_POST['guarantor_a_residential'], $_POST['guarantor_a_occupation'], $_POST['guarantor_a_email']
            ]);
        }
        
        // Guarantor B
        if (!empty($_POST['guarantor_b_first'])) {
            $guarantor_stmt->execute([
                $application_id, 'B',
                $_POST['guarantor_b_first'], $_POST['guarantor_b_middle'], $_POST['guarantor_b_last'],
                $_POST['guarantor_b_relationship'], $_POST['guarantor_b_phone'],
                $_POST['guarantor_b_residential'], $_POST['guarantor_b_occupation'], $_POST['guarantor_b_email']
            ]);
        }
        
        $db->commit();
        
        // Success response
        header('Location: careers.php?success=1');
        exit;
        
    } catch (Exception $e) {
        $db->rollBack();
        header('Location: careers.php?error=1');
        exit;
    }
}