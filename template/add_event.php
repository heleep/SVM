<?php
session_start();
include '../db/database_connection.php';

if (!isset($_SESSION['principal'])) {
  header("Location: /SVM/template/login.html");
  exit();
}

$upload_dir = __DIR__ . "/../assets/uploads/";
$upload_web_path = "/SVM/assets/uploads/";

$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $event_name = trim($_POST['event_name']);
  $event_date = $_POST['event_date'];
  $event_description = trim($_POST['event_description']);

  if (empty($event_name) || empty($event_date)) {
    $error = "Please fill in all required fields.";
  } else {
    $image_filename = null;
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === UPLOAD_ERR_OK) {
      $file = $_FILES['event_image'];
      $filename = uniqid() . '_' . basename($file['name']);
      $target_file = $upload_dir . $filename;
      $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

      // Validate image
      if (!getimagesize($file['tmp_name'])) {
        $error = "File is not a valid image.";
      } elseif ($file['size'] > 5000000) { // 5MB limit
        $error = "Image size must be less than 5MB.";
      } elseif (!in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
      } else {
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
          $image_filename = $filename;
        } else {
          $error = "Failed to upload image. Debug: " . print_r(error_get_last(), true);
        }
      }
    }

    if (empty($error)) {
      $conn->begin_transaction();
      
      try {
        $stmt = $conn->prepare("INSERT INTO SVM_Events (SVM_Event, Event_date, Event_description) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $event_name, $event_date, $event_description);
        $stmt->execute();
        $event_id = $conn->insert_id;

        if ($image_filename) {
          $stmt_img = $conn->prepare("INSERT INTO images (filename) VALUES (?)");
          $stmt_img->bind_param("s", $image_filename);
          $stmt_img->execute();
        }
        
        $conn->commit();
        $message = "Event created successfully!";
      } catch (Exception $e) {
        $conn->rollback();
        
        if ($image_filename && file_exists($upload_dir . $image_filename)) {
          unlink($upload_dir . $image_filename);
        }
        
        $error = "Error creating event: " . $e->getMessage();
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Event - SVMIT</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }
    .event-container {
      background: white;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      max-width: 500px;
      width: 100%;
    }
    h2 {
      text-align: center;
      color: #003366;
      margin-bottom: 30px;
    }
    .form-group {
      margin-bottom: 20px;
    }
    label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
      color: #333;
    }
    input[type="text"],
    input[type="date"],
    textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }
    textarea {
      min-height: 100px;
      resize: vertical;
    }
    button {
      width: 100%;
      padding: 12px;
      background-color: #003366;
      color: white;
      font-size: 16px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s;
    }
    button:hover {
      background-color: #0055aa;
    }
    .message {
      text-align: center;
      margin-top: 15px;
      color: green;
    }
    .error {
      text-align: center;
      margin-top: 15px;
      color: red;
    }
    .preview-image {
      max-width: 100%;
      max-height: 200px;
      margin-top: 10px;
      display: none;
    }
  </style>
</head>
<body>
  <div class="event-container">
    <h2>Add New Event</h2>
    <?php if (!empty($message)): ?><div class="message"><?= htmlspecialchars($message) ?></div><?php endif; ?>
    <?php if (!empty($error)): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label for="event_name">Event Name*</label>
        <input type="text" id="event_name" name="event_name" required>
      </div>
      <div class="form-group">
        <label for="event_date">Event Date*</label>
        <input type="date" id="event_date" name="event_date" required>
      </div>
      <div class="form-group">
        <label for="event_description">Event Description</label>
        <textarea id="event_description" name="event_description"></textarea>
      </div>
      <div class="form-group">
        <label for="event_image">Event Image</label>
        <input type="file" id="event_image" name="event_image" accept="image/*">
        <img id="imagePreview" class="preview-image" src="#" alt="Preview">
      </div>
      <button type="submit">Add Event</button>
    </form>
  </div>

  <script>
    // Image preview functionality
    document.getElementById('event_image').addEventListener('change', function(e) {
      const preview = document.getElementById('imagePreview');
      const file = e.target.files[0];
      
      if (file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
          preview.src = e.target.result;
          preview.style.display = 'block';
        }
        
        reader.readAsDataURL(file);
      } else {
        preview.style.display = 'none';
      }
    });
  </script>
</body>
</html>