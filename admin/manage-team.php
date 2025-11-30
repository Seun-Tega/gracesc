<?php
// admin/manage-team.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include '../config/database.php';
$database = new Database();
$db = $database->getConnection();

$success = '';
$error = '';

// Handle form submissions
if ($_POST) {
    if (isset($_POST['add_team_member'])) {
        $name = $_POST['name'];
        $role = $_POST['role'];
        $bio = $_POST['bio'];
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $expertise = $_POST['expertise'] ?? '';
        $experience = $_POST['experience'] ?? '';
        
        // Handle image upload
        $image_url = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $upload_dir = '../images/team/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = 'team-member-' . time() . '.' . $file_extension;
            $target_file = $upload_dir . $filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_url = 'images/team/' . $filename;
            }
        }
        
        $query = "INSERT INTO team_members (name, role, bio, image_url, email, phone, expertise, experience) 
                  VALUES (:name, :role, :bio, :image_url, :email, :phone, :expertise, :experience)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':bio', $bio);
        $stmt->bindParam(':image_url', $image_url);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':expertise', $expertise);
        $stmt->bindParam(':experience', $experience);
        
        if ($stmt->execute()) {
            $success = "Team member added successfully!";
        } else {
            $error = "Error adding team member.";
        }
    }
    
    // Update team member
    if (isset($_POST['update_team_member'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $role = $_POST['role'];
        $bio = $_POST['bio'];
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $expertise = $_POST['expertise'] ?? '';
        $experience = $_POST['experience'] ?? '';
        
        // Handle image upload
        $image_url = $_POST['current_image'] ?? '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $upload_dir = '../images/team/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = 'team-member-' . time() . '.' . $file_extension;
            $target_file = $upload_dir . $filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_url = 'images/team/' . $filename;
            }
        }
        
        $query = "UPDATE team_members SET name = :name, role = :role, bio = :bio, 
                  image_url = :image_url, email = :email, phone = :phone, 
                  expertise = :expertise, experience = :experience 
                  WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':bio', $bio);
        $stmt->bindParam(':image_url', $image_url);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':expertise', $expertise);
        $stmt->bindParam(':experience', $experience);
        
        if ($stmt->execute()) {
            $success = "Team member updated successfully!";
        } else {
            $error = "Error updating team member.";
        }
    }
    
    // Handle delete
    if (isset($_POST['delete_team_member'])) {
        $id = $_POST['team_member_id'];
        $query = "DELETE FROM team_members WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            $success = "Team member deleted successfully!";
        } else {
            $error = "Error deleting team member.";
        }
    }
}

// Get all team members
$query = "SELECT * FROM team_members ORDER BY display_order, created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$team_members = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get team member for editing
$edit_member = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $stmt = $db->prepare("SELECT * FROM team_members WHERE id = :id");
    $stmt->bindParam(":id", $edit_id);
    $stmt->execute();
    $edit_member = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Helper function to safely output values
function safe_output($value) {
    return $value !== null ? htmlspecialchars($value) : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Team - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include 'sidebar.php'; ?>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>Manage Team Members</h2>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTeamModal">
                            <i class="fas fa-plus me-2"></i>Add New Member
                        </button>
                    </div>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">All Team Members</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Role</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Experience</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($team_members as $member): ?>
                                        <tr>
                                            <td><?php echo $member['id']; ?></td>
                                            <td>
                                                <?php if (!empty($member['image_url'])): ?>
                                                    <img src="../<?php echo safe_output($member['image_url']); ?>" class="rounded" width="50" height="50" style="object-fit: cover;" alt="<?php echo safe_output($member['name']); ?>">
                                                <?php else: ?>
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                        <i class="fas fa-user text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong><?php echo safe_output($member['name']); ?></strong>
                                                <?php if (!empty($member['bio'])): ?>
                                                    <br><small class="text-muted"><?php echo substr(safe_output($member['bio']), 0, 50) . '...'; ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo safe_output($member['role']); ?></td>
                                            <td><?php echo safe_output($member['email']); ?></td>
                                            <td><?php echo safe_output($member['phone']); ?></td>
                                            <td><?php echo safe_output($member['experience']); ?></td>
                                            <td>
                                                <a href="?edit_id=<?php echo $member['id']; ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="team_member_id" value="<?php echo $member['id']; ?>">
                                                    <button type="submit" name="delete_team_member" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this team member?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Team Member Modal -->
    <div class="modal fade" id="addTeamModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Team Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Name *</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Role *</label>
                                    <input type="text" class="form-control" name="role" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bio *</label>
                            <textarea class="form-control" name="bio" rows="3" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="tel" class="form-control" name="phone">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Expertise (comma separated)</label>
                                    <input type="text" class="form-control" name="expertise" placeholder="e.g., Dementia Care, Physical Therapy">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Experience</label>
                                    <input type="text" class="form-control" name="experience" placeholder="e.g., 10+ years">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Profile Image</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_team_member" class="btn btn-primary">Add Team Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Team Member Modal -->
    <?php if ($edit_member): ?>
    <div class="modal fade show" id="editTeamModal" style="display: block; background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Team Member</h5>
                    <a href="manage-team.php" class="btn-close"></a>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $edit_member['id']; ?>">
                    <input type="hidden" name="current_image" value="<?php echo safe_output($edit_member['image_url']); ?>">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Name *</label>
                                    <input type="text" class="form-control" name="name" value="<?php echo safe_output($edit_member['name']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Role *</label>
                                    <input type="text" class="form-control" name="role" value="<?php echo safe_output($edit_member['role']); ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bio *</label>
                            <textarea class="form-control" name="bio" rows="3" required><?php echo safe_output($edit_member['bio']); ?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" value="<?php echo safe_output($edit_member['email']); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="tel" class="form-control" name="phone" value="<?php echo safe_output($edit_member['phone']); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Expertise (comma separated)</label>
                                    <input type="text" class="form-control" name="expertise" value="<?php echo safe_output($edit_member['expertise']); ?>" placeholder="e.g., Dementia Care, Physical Therapy">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Experience</label>
                                    <input type="text" class="form-control" name="experience" value="<?php echo safe_output($edit_member['experience']); ?>" placeholder="e.g., 10+ years">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Profile Image</label>
                            <?php if (!empty($edit_member['image_url'])): ?>
                                <div class="mb-2">
                                    <img src="../<?php echo safe_output($edit_member['image_url']); ?>" class="rounded" width="100" height="100" style="object-fit: cover;" alt="Current image">
                                    <br><small>Current image</small>
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            <small class="text-muted">Leave empty to keep current image</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="manage-team.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" name="update_team_member" class="btn btn-primary">Update Team Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>