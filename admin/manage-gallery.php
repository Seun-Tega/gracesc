<?php
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

// Handle image upload - Check if form was submitted first
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'add_gallery' && isset($_FILES['gallery_images'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        
        $target_dir = "../uploads/gallery/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $uploaded_count = 0;
        $error_count = 0;
        
        // Loop through each uploaded file
        foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['gallery_images']['error'][$key] === UPLOAD_ERR_OK) {
                $original_name = $_FILES['gallery_images']['name'][$key];
                $imageFileType = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
                
                // Generate unique filename
                $filename = "gallery_" . time() . "_" . uniqid() . "." . $imageFileType;
                $target_file = $target_dir . $filename;
                
                // Validate image
                $check = getimagesize($tmp_name);
                if ($check !== false) {
                    // Check file size (max 5MB)
                    if ($_FILES['gallery_images']['size'][$key] > 5000000) {
                        $error = "File $original_name is too large. Maximum size is 5MB.";
                        $error_count++;
                        continue;
                    }
                    
                    // Allow certain file formats
                    $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    if (!in_array($imageFileType, $allowed_types)) {
                        $error = "Sorry, only JPG, JPEG, PNG, GIF & WEBP files are allowed.";
                        $error_count++;
                        continue;
                    }
                    
                    if (move_uploaded_file($tmp_name, $target_file)) {
                        $query = "INSERT INTO gallery (title, description, image_url, category) VALUES (?, ?, ?, ?)";
                        $stmt = $db->prepare($query);
                        if ($stmt->execute([$title, $description, "uploads/gallery/" . $filename, $category])) {
                            $uploaded_count++;
                        } else {
                            $error_count++;
                        }
                    } else {
                        $error_count++;
                    }
                } else {
                    $error = "File $original_name is not an image.";
                    $error_count++;
                }
            }
        }
        
        if ($uploaded_count > 0) {
            $success = "Successfully uploaded $uploaded_count image(s)!";
            if ($error_count > 0) {
                $error = "$error_count image(s) failed to upload.";
            }
        } elseif ($error_count > 0) {
            $error = "All images failed to upload. Please try again.";
        }
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // First get the image URL to delete the file
    $query = "SELECT image_url FROM gallery WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$id]);
    $image = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($image) {
        // Delete the physical file
        $file_path = "../" . $image['image_url'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        
        // Delete from database
        $query = "DELETE FROM gallery WHERE id = ?";
        $stmt = $db->prepare($query);
        if ($stmt->execute([$id])) {
            $success = "Image deleted successfully!";
        } else {
            $error = "Failed to delete image from database.";
        }
    } else {
        $error = "Image not found.";
    }
}

// Get all gallery images
$query = "SELECT * FROM gallery ORDER BY created_at DESC";
$gallery_items = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Gallery - GRACE SENIOR CARE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <style>
        .gallery-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }
        .gallery-item:hover .gallery-image {
            transform: scale(1.05);
        }
        .gallery-item {
            margin-bottom: 20px;
            position: relative;
        }
        .gallery-actions {
            position: absolute;
            top: 10px;
            right: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .gallery-item:hover .gallery-actions {
            opacity: 1;
        }
        .category-badge {
            position: absolute;
            top: 10px;
            left: 10px;
        }
        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }
        .file-input-wrapper input[type=file] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
        }
        .preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        .preview-item {
            position: relative;
            width: 100px;
            height: 100px;
        }
        .preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 5px;
        }
        .preview-item .remove-preview {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'sidebar.php'; ?>
            
            <div class="col-md-9 col-lg-10 bg-light">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="text-primary">Manage Gallery</h2>
                        <span class="badge bg-primary"><?php echo count($gallery_items); ?> images</span>
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

                    <!-- Add Gallery Form -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-plus me-2"></i>Add New Gallery Images
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data" id="galleryForm">
                                <input type="hidden" name="action" value="add_gallery">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Title *</label>
                                            <input type="text" class="form-control" name="title" required 
                                                   placeholder="Enter image title">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Category *</label>
                                            <select class="form-select" name="category" required>
                                                <option value="">Select category</option>
                                                <option value="facility">Facility</option>
                                                <option value="activities">Activities</option>
                                                <option value="caregiving">Caregiving</option>
                                                <option value="team">Our Team</option>
                                                <option value="events">Events</option>
                                                <option value="general">General</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3" 
                                              placeholder="Optional description for the images"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Images *</label>
                                    <div class="file-input-wrapper">
                                        <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('gallery_images').click()">
                                            <i class="fas fa-images me-2"></i>Choose Images
                                        </button>
                                        <input type="file" class="form-control" id="gallery_images" name="gallery_images[]" 
                                               accept="image/*" multiple required style="display: none;">
                                    </div>
                                    <div id="fileCount" class="text-muted mt-2"></div>
                                    <div id="imagePreview" class="preview-container mt-2"></div>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        You can select multiple images. Maximum file size: 5MB each. Supported formats: JPG, JPEG, PNG, GIF, WEBP
                                    </small>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload me-2"></i>Upload Images
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Gallery Grid -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-images me-2"></i>Gallery Images
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($gallery_items)): ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-images fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted">No images in gallery</h5>
                                    <p class="text-muted">Upload your first image using the form above.</p>
                                </div>
                            <?php else: ?>
                                <div class="row">
                                    <?php foreach ($gallery_items as $item): ?>
                                    <div class="col-md-4 col-lg-3 gallery-item">
                                        <div class="category-badge">
                                            <span class="badge bg-primary"><?php echo ucfirst($item['category']); ?></span>
                                        </div>
                                        <div class="gallery-actions">
                                            <a href="?delete=<?php echo $item['id']; ?>" 
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Are you sure you want to delete this image?')"
                                               title="Delete Image">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                        <img src="../<?php echo $item['image_url']; ?>" 
                                             alt="<?php echo htmlspecialchars($item['title']); ?>" 
                                             class="gallery-image"
                                             onerror="this.src='https://via.placeholder.com/300x200?text=Image+Not+Found'">
                                        <div class="mt-2">
                                            <h6 class="mb-1"><?php echo htmlspecialchars($item['title']); ?></h6>
                                            <?php if ($item['description']): ?>
                                                <p class="text-muted small mb-1"><?php echo htmlspecialchars($item['description']); ?></p>
                                            <?php endif; ?>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                <?php echo date('M j, Y', strtotime($item['created_at'])); ?>
                                            </small>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // File input and preview functionality
        document.getElementById('gallery_images').addEventListener('change', function(e) {
            const files = e.target.files;
            const previewContainer = document.getElementById('imagePreview');
            const fileCount = document.getElementById('fileCount');
            
            previewContainer.innerHTML = '';
            
            if (files.length > 0) {
                fileCount.textContent = files.length + ' file(s) selected';
                
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const previewItem = document.createElement('div');
                            previewItem.className = 'preview-item';
                            previewItem.innerHTML = `
                                <img src="${e.target.result}" alt="Preview">
                                <button type="button" class="remove-preview" onclick="removePreview(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            `;
                            previewContainer.appendChild(previewItem);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            } else {
                fileCount.textContent = '';
            }
        });

        function removePreview(button) {
            const previewItem = button.parentElement;
            previewItem.remove();
            
            // Update file count
            const remainingPreviews = document.querySelectorAll('.preview-item').length;
            document.getElementById('fileCount').textContent = remainingPreviews + ' file(s) selected';
        }

        // Form validation
        document.getElementById('galleryForm').addEventListener('submit', function(e) {
            const files = document.getElementById('gallery_images').files;
            if (files.length === 0) {
                e.preventDefault();
                alert('Please select at least one image to upload.');
                return false;
            }
            
            // Check file sizes
            let valid = true;
            for (let i = 0; i < files.length; i++) {
                if (files[i].size > 5000000) { // 5MB
                    valid = false;
                    alert('File "' + files[i].name + '" is too large. Maximum size is 5MB.');
                    break;
                }
            }
            
            if (!valid) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>