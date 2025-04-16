<?php
session_start();
include '../db/database_connection.php';

if (!isset($_SESSION['admin'])) {
    header("Location: /SVM/template/login.html");
    exit();
}

// Set the upload directory using an absolute path. 
// __DIR__ returns the current directory path. Adjust if necessary.
$upload_dir = __DIR__ . "../assets/uploads/";
// The corresponding web URL path for the images
$upload_web_path = "/SVM/assets/uploads/";

// Initialize messages
$message = "";
$error = "";

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check for errors from the file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== 0) {
        $error = "Upload error: " . $_FILES['image']['error'];
    }
    if (isset($_FILES['new_image']) && $_FILES['new_image']['error'] !== 0) {
        $error = "Upload error: " . $_FILES['new_image']['error'];
    }

    // Handle Image Upload
    if (isset($_POST['upload']) && empty($error)) {
        $file = $_FILES['image'];
        
        // Generate a unique filename
        $filename = uniqid() . '_' . basename($file['name']);
        $target_file = $upload_dir . $filename;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate file type, size (<5MB) and that it's an image
        if (getimagesize($file['tmp_name']) && 
            $file['size'] < 5000000 && 
            in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            
            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                // Insert record into the database
                $stmt = $conn->prepare("INSERT INTO images (filename) VALUES (?)");
                $stmt->bind_param("s", $filename);
                $stmt->execute();
                $message = "Image uploaded successfully!";
            } else {
                $error = "Failed to move uploaded file.";
            }
        } else {
            $error = "Invalid file format (allowed: jpg, jpeg, png, gif) or file too large!";
        }
    }

    // Handle Image Update
    if (isset($_POST['update']) && empty($error)) {
        $id = $_POST['id'];
        $file = $_FILES['new_image'];
        
        // Retrieve old filename
        $oldFileRes = $conn->query("SELECT filename FROM images WHERE id=$id");
        $oldFileRow = $oldFileRes ? $oldFileRes->fetch_assoc() : null;
        $oldFile = $oldFileRow ? $oldFileRow['filename'] : '';

        // Generate new filename
        $new_filename = uniqid() . '_' . basename($file['name']);
        $target_file = $upload_dir . $new_filename;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate new image
        if (getimagesize($file['tmp_name']) && 
            $file['size'] < 5000000 && 
            in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                
            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                // Update database record
                $stmt = $conn->prepare("UPDATE images SET filename=? WHERE id=?");
                $stmt->bind_param("si", $new_filename, $id);
                $stmt->execute();

                // Delete old file if exists
                if ($oldFile && file_exists($upload_dir . $oldFile)) {
                    unlink($upload_dir . $oldFile);
                }
                $message = "Image updated successfully!";
            } else {
                $error = "Failed to update the image file.";
            }
        } else {
            $error = "Invalid file format or size for update!";
        }
    }
}

// Handle Image Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $fileRes = $conn->query("SELECT filename FROM images WHERE id=$id");
    $fileRow = $fileRes ? $fileRes->fetch_assoc() : null;
    $fileName = $fileRow ? $fileRow['filename'] : '';

    if ($fileName && file_exists($upload_dir . $fileName)) {
        unlink($upload_dir . $fileName);
    }
    $conn->query("DELETE FROM images WHERE id=$id");
    $message = "Image deleted successfully!";
}

// Fetch all images (order by uploaded_at descending)
$images = $conn->query("SELECT * FROM images ORDER BY uploaded_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Image Manager - SVMIT Admin Panel</title>
    <style>
        /* Admin theme styles */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
        }
        .sidebar {
            width: 220px;
            height: 100vh;
            background-color: #002244;
            padding-top: 20px;
            color: white;
            position: fixed;
        }
        .sidebar img {
            display: block;
            margin: 0 auto 20px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }
        .sidebar h2 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 30px;
        }
        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            transition: background 0.3s;
        }
        .sidebar a:hover {
            background-color: #004488;
        }
        .main-content {
            margin-left: 220px;
            padding: 40px;
            width: 100%;
        }
        h1 {
            color: #003366;
        }
        .message {
            color: green;
            margin-bottom: 20px;
        }
        .error {
            color: red;
            margin-bottom: 20px;
        }
        .btn-show-form {
            padding: 10px 20px;
            background: #003366;
            color: white;
            border: none;
            border-radius: 4px;
            margin-bottom: 20px;
            cursor: pointer;
        }
        .add-form, .image-card form {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .add-form input[type=\"file\"] {\n            padding: 10px;\n            width: 100%;\n            margin-bottom: 10px;\n            border: 1px solid #ccc;\n            border-radius: 4px;\n        }\n        .add-form button, .btn-update, .btn-delete {\n            padding: 10px 20px;\n            border: none;\n            border-radius: 4px;\n            cursor: pointer;\n        }\n        .btn-update {\n            background-color: #28a745;\n            color: white;\n            margin-right: 5px;\n        }\n        .btn-delete {\n            background-color: #dc3545;\n            color: white;\n        }\n        .image-grid {\n            display: grid;\n            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));\n            gap: 20px;\n        }\n        .image-card {\n            background: #fff;\n            padding: 10px;\n            border: 1px solid #ddd;\n            border-radius: 8px;\n            text-align: center;\n        }\n        .image-card img {\n            max-width: 100%;\n            max-height: 150px;\n        }\n        .image-card form {\n            margin-top: 10px;\n        }\n    </style>
</head>
<body>
    <div class="sidebar">
        <img src="/SVM/assets/logo.png" alt="SVM Logo">
        <h2>Admin Panel</h2>
        <a href="admin_home.php">Home</a>
        <a href="manage_events.php">Manage Events</a>
        <a href="manage_image.php">Manage Image</a>
    </div>

    <div class="main-content">
        <h1>Image Manager</h1>
        <?php if (!empty($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <?php if (!empty($message)): ?>
            <div class="message"><?= $message ?></div>
        <?php endif; ?>

        <!-- Upload Form -->
        <button class="btn-show-form" onclick="document.querySelector('.add-form').style.display='block'">Upload New Image</button>
        <form class="add-form" method="POST" enctype="multipart/form-data">
            <input type="file" name="image" required>
            <button type="submit" name="upload">Upload</button>
        </form>

        <!-- Image Gallery -->
        <div class="image-grid">
            <?php while($image = $images->fetch_assoc()): ?>
                <div class="image-card">
                    <img src="<?= $upload_web_path . $image['filename'] ?>" alt="Image">
                    <p>Uploaded: <?= $image['uploaded_at'] ?></p>
                    <!-- Delete Button --> 
                    <a href="?delete=<?= $image['id'] ?>" class="btn-delete" onclick="return confirm('Delete this image?');">Delete</a>
                    <!-- Update Form --> 
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $image['id'] ?>">
                        <input type="file" name="new_image" required>
                        <button type="submit" name="update" class="btn-update">Update</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
