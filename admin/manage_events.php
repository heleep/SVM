<?php
session_start();
include '../db/database_connection.php';

if (!isset($_SESSION['admin'])) {
  header("Location: /SVM/admin/manage_event.php");
  exit();
}

$upload_dir = __DIR__ . '/../assets/uploads/';
$upload_web_path = '/SVM/assets/uploads/';

// Handle Add Event
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_event'])) {
  $event = $_POST['event_name'];
  $date = $_POST['event_date'];
  $description = $_POST['event_description'];

  // Handle image upload
  $image_name = '';
  if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === UPLOAD_ERR_OK) {
    $image_name = uniqid() . '_' . basename($_FILES['event_image']['name']);
    $target_path = $upload_dir . $image_name;
    move_uploaded_file($_FILES['event_image']['tmp_name'], $target_path);
  }

  $stmt = $conn->prepare("INSERT INTO SVM_Events (SVM_Event, Event_date, Event_description, Event_image) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssss", $event, $date, $description, $image_name);
  $stmt->execute();
  header("Location: manage_events.php");
  exit();
}

// Handle Delete Event
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $stmt = $conn->prepare("DELETE FROM SVM_Events WHERE SVM_Event = ?");
  $stmt->bind_param("s", $id);
  $stmt->execute();
  header("Location: manage_events.php");
  exit();
}

// Handle Update Event
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_event'])) {
  $original_name = $_POST['original_name'];
  $updated_name = $_POST['updated_event_name'];
  $updated_date = $_POST['updated_event_date'];
  $updated_description = $_POST['updated_event_description'];
  $stmt = $conn->prepare("UPDATE SVM_Events SET SVM_Event = ?, Event_date = ?, Event_description = ? WHERE SVM_Event = ?");
  $stmt->bind_param("ssss", $updated_name, $updated_date, $updated_description, $original_name);
  $stmt->execute();
  header("Location: manage_events.php");
  exit();
}

$events = $conn->query("SELECT * FROM SVM_Events ORDER BY Event_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Events - SVMIT</title>
  <style>
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
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      background: white;
    }
    th, td {
      padding: 12px;
      border: 1px solid #ccc;
      text-align: left;
    }
    th {
      background: #003366;
      color: white;
    }
    .add-form {
      background: #fff;
      padding: 20px;
      margin-bottom: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      display: none;
    }
    .add-form input[type="text"],
    .add-form input[type="date"],
    .add-form input[type="file"] {
      padding: 10px;
      width: calc(50% - 22px);
      margin: 0 10px 10px 0;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .add-form textarea {
      width: 100%;
      margin-bottom: 10px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .add-form button {
      padding: 10px 20px;
      background: #003366;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
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
    .btn-update {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 6px 10px;
      margin-right: 5px;
      border-radius: 4px;
      cursor: pointer;
    }
    .btn-delete {
      background-color: #dc3545;
      color: white;
      border: none;
      padding: 6px 10px;
      border-radius: 4px;
      cursor: pointer;
    }
    img.event-image {
      width: 100px;
      height: auto;
    }
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
    <h1>All Events</h1>
    <button class="btn-show-form" onclick="document.querySelector('.add-form').style.display='block'">Add Event</button>
    <form class="add-form" method="POST" enctype="multipart/form-data">
      <input type="text" name="event_name" placeholder="Event Name" required>
      <input type="date" name="event_date" required>
      <input type="file" name="event_image" accept="image/*" required>
      <textarea name="event_description" placeholder="Event Description"></textarea>
      <button type="submit" name="add_event">Add</button>
    </form>

    <table>
      <tr>
        <th>Event</th>
        <th>Date</th>
        <th>Description</th>
        <th>Image</th>
        <th>Actions</th>
      </tr>
      <?php while($event = $events->fetch_assoc()): ?>
      <tr>
        <form method="POST">
          <td>
            <input type="text" name="updated_event_name" value="<?= htmlspecialchars($event['SVM_Event']) ?>" required>
            <input type="hidden" name="original_name" value="<?= htmlspecialchars($event['SVM_Event']) ?>">
          </td>
          <td><input type="date" name="updated_event_date" value="<?= htmlspecialchars($event['Event_date']) ?>" required></td>
          <td><textarea name="updated_event_description"><?= htmlspecialchars($event['Event_description']) ?></textarea></td>
          <td>
            <?php if (!empty($event['Event_image'])): ?>
              <img src="<?= $upload_web_path . htmlspecialchars($event['Event_image']) ?>" class="event-image" alt="Event Image">
            <?php else: ?>
              No Image
            <?php endif; ?>
          </td>
          <td>
            <button type="submit" name="update_event" class="btn-update">Update</button>
            <a href="?delete=<?= urlencode($event['SVM_Event']) ?>" class="btn-delete" onclick="return confirm('Delete this event?');">Delete</a>
          </td>
        </form>
      </tr>
      <?php endwhile; ?>
    </table>
  </div>
</body>
</html>
