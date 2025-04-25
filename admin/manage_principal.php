<?php
session_start();
include '../db/database_connection.php';

if (!isset($_SESSION['admin'])) {
  header("Location: /SVM/template/login.html");
  exit();
}

$message = "";
$error = "";

// Handle Add Principal
// Handle Add Principal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_principal'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
  
    if (!preg_match("/^[^@\s]+@gmail\.com$/", $username)) {
      $error = "Username must end with @gmail.com";
    } else {
      $check = $conn->prepare("SELECT * FROM SVM_Principal WHERE user_name = ?");
      $check->bind_param("s", $username);
      $check->execute();
      $result = $check->get_result();
  
      if ($result->num_rows > 0) {
        $error = "Username already exists.";
      } else {
        $stmt = $conn->prepare("INSERT INTO SVM_Principal (user_name, user_password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);
        if ($stmt->execute()) {
          $message = "Principal added successfully!";
        } else {
          $error = "Error adding principal.";
        }
      }
    }
  }

// Handle Delete
if (isset($_GET['delete'])) {
  $username = $_GET['delete'];
  $stmt = $conn->prepare("DELETE FROM SVM_Principal WHERE user_name = ?");
  $stmt->bind_param("s", $username);
  if ($stmt->execute()) {
    $message = "Principal deleted successfully!";
  } else {
    $error = "Error deleting principal.";
  }
}

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_principal'])) {
  $original_username = $_POST['original_username'];
  $new_username = $_POST['username'];
  $new_password = $_POST['password'];
  $stmt = $conn->prepare("UPDATE SVM_Principal SET user_name = ?, user_password = ? WHERE user_name = ?");
  $stmt->bind_param("sss", $new_username, $new_password, $original_username);
  if ($stmt->execute()) {
    $message = "Principal updated successfully!";
  } else {
    $error = "Error updating principal.";
  }
}

$principals = $conn->query("SELECT * FROM SVM_Principal ORDER BY user_name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Principals - SVMIT</title>
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
    .add-form, .update-form { display: none; background: #fff; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
    .add-form input, .update-form input { padding: 10px; width: calc(50% - 22px); margin: 0 10px 10px 0; border: 1px solid #ccc; border-radius: 4px; }
    .add-form button, .update-form button, .btn-update, .btn-delete { padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; }
    .btn-update { background-color: #28a745; color: white; margin-right: 5px; }
    .btn-delete { background-color: #dc3545; color: white; }
    table { width: 100%; border-collapse: collapse; background: white; }
    th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
    th { background: #003366; color: white; }
  </style>
</head>
<body>
  <div class="sidebar">
    <img src="/SVM/assets/images/logo.jpg" alt="SVM Logo">
    <h2>Admin</h2>
    <a href="admin_home.html">Home</a>
    <a href="manage_events.php">Manage Events</a>
    <a href="manage_image.php">Manage Image</a>
    <a href="manage_principal.php">Manage Principal</a>
  </div>

  <div class="main-content">
    <h1>Manage Principals</h1>
    <?php if (!empty($error)): ?><div class="error"><?= $error ?></div><?php endif; ?>
    <?php if (!empty($message)): ?><div class="message"><?= $message ?></div><?php endif; ?>

    <button class="btn-show-form" onclick="document.querySelector('.add-form').style.display='block'">Add New Principal</button>
    <form class="add-form" method="POST">
      <input type="text" name="username" placeholder="Username" required>
      <input type="text" name="password" placeholder="Password" required>
      <button type="submit" name="add_principal">Add</button>
    </form>

    <table>
      <tr>
        <th>Username</th>
        <th>Password</th>
        <th>Actions</th>
      </tr>
      <?php while($principal = $principals->fetch_assoc()): ?>
      <tr>
        <form method="POST">
          <td>
            <input type="text" name="username" value="<?= htmlspecialchars($principal['user_name']) ?>" required>
            <input type="hidden" name="original_username" value="<?= htmlspecialchars($principal['user_name']) ?>">
          </td>
          <td><input type="text" name="password" value="<?= htmlspecialchars($principal['user_password']) ?>" required></td>
          <td>
            <button type="submit" name="update_principal" class="btn-update">Update</button>
            <a href="?delete=<?= urlencode($principal['user_name']) ?>" class="btn-delete" onclick="return confirm('Delete this principal?');">Delete</a>
          </td>
        </form>
      </tr>
      <?php endwhile; ?>
    </table>
  </div>
</body>
</html>
