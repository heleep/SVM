<?php
session_start();
include '../db/database_connection.php';

if (!isset($_SESSION['admin'])) {
    header("Location: /SVM/template/login.html");
    exit();
}

$upload_dir = __DIR__ . "/../assets/uploads/";
$upload_web_path = "/SVM/assets/uploads/";

$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['upload']) && isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $file = $_FILES['image'];
        $filename = uniqid() . '_' . basename($file['name']);
        $target_file = $upload_dir . $filename;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (getimagesize($file['tmp_name']) && $file['size'] < 5000000 && in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                $stmt = $conn->prepare("INSERT INTO images (filename) VALUES (?)");
                $stmt->bind_param("s", $filename);
                $stmt->execute();
                $message = "Image uploaded successfully!";
            } else {
                $error = "Failed to move uploaded file. Debug: " . print_r(error_get_last(), true);
            }
        } else {
            $error = "Invalid file format or size!";
        }
    }

    if (isset($_POST['update']) && isset($_FILES['new_image']) && $_FILES['new_image']['error'] === 0) {
        $id = $_POST['id'];
        $file = $_FILES['new_image'];
        $old = $conn->query("SELECT filename FROM images WHERE id=$id")->fetch_assoc();
        $oldFile = $old['filename'];

        $new_filename = uniqid() . '_' . basename($file['name']);
        $target_file = $upload_dir . $new_filename;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (getimagesize($file['tmp_name']) && $file['size'] < 5000000 && in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                $stmt = $conn->prepare("UPDATE images SET filename=? WHERE id=?");
                $stmt->bind_param("si", $new_filename, $id);
                $stmt->execute();

                if (file_exists($upload_dir . $oldFile)) {
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

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $file = $conn->query("SELECT filename FROM images WHERE id=$id")->fetch_assoc()['filename'];
    if (file_exists($upload_dir . $file)) {
        unlink($upload_dir . $file);
    }
    $conn->query("DELETE FROM images WHERE id=$id");
    $message = "Image deleted successfully!";
}

$images = $conn->query("SELECT * FROM images ORDER BY uploaded_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Image Manager - SVMIT Admin Panel</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f4f4f4; display: flex; }
        .sidebar { width: 220px; height: 100vh; background-color: #002244; padding-top: 20px; color: white; position: fixed; }
        .sidebar img { display: block; margin: 0 auto 20px; width: 80px; height: 80px; border-radius: 50%; }
        .sidebar h2 { text-align: center; font-size: 18px; margin-bottom: 30px; }
        .sidebar a { display: block; padding: 12px 20px; color: white; text-decoration: none; transition: background 0.3s; }
        .sidebar a:hover { background-color: #004488; }
        .main-content { margin-left: 220px; padding: 40px; width: 100%; }
        h1 { color: #003366; }
        .message { color: green; margin-bottom: 20px; }
        .error { color: red; margin-bottom: 20px; }
        .btn-show-form { padding: 10px 20px; background: #003366; color: white; border: none; border-radius: 4px; margin-bottom: 20px; cursor: pointer; }
        .add-form { display: none; background: #fff; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        .add-form input[type="file"] { padding: 10px; width: 100%; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px; }
        .add-form button, .btn-update, .btn-delete { padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-update { background-color: #28a745; color: white; margin-right: 5px; }
        .btn-delete { background-color: #dc3545; color: white; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background: #003366; color: white; }
        img.thumb { width: 100px; height: auto; }
    </style>
</head>
<body>
    <div class="sidebar">
        <img src="/SVM/assets/images/logo.jpg" alt="SVM Logo">
        <h2>Admin</h2>
        <a href="admin_home.html">Home</a>
        <a href="manage_events.php">Manage Events</a>
        <a href="manage_image.php">Manage Images</a>
        <a href="manage_principal.php">Manage Principal</a>
    </div>

    <div class="main-content">
        <h1>Image Manager</h1>
        <?php if (!empty($error)): ?><div class="error"><?= $error ?></div><?php endif; ?>
        <?php if (!empty($message)): ?><div class="message"><?= $message ?></div><?php endif; ?>

        <button class="btn-show-form" onclick="document.querySelector('.add-form').style.display='block'">Upload New Image</button>
        <form class="add-form" method="POST" enctype="multipart/form-data">
            <input type="file" name="image" required>
            <button type="submit" name="upload">Upload</button>
        </form>

        <table>
            <tr>
                <th>Image</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
            <?php while($img = $images->fetch_assoc()): ?>
                <tr>
                    <td><img src="<?= $upload_web_path . $img['filename'] ?>" class="thumb"></td>
                    <td><?= $img['uploaded_at'] ?></td>
                    <td>
                        <form method="POST" enctype="multipart/form-data" style="display:inline-block">
                            <input type="hidden" name="id" value="<?= $img['id'] ?>">
                            <input type="file" name="new_image" required>
                            <button type="submit" name="update" class="btn-update">Update</button>
                        </form>
                        <a href="?delete=<?= $img['id'] ?>" class="btn-delete" onclick="return confirm('Delete this image?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>