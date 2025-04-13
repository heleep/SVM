<?php
session_start();
include '../db/database_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Use prepared statement for security
    $stmt = $conn->prepare("SELECT id, password FROM admin_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $hashedPassword);
        $stmt->fetch();
        // Verify the password using password_verify()
        if (password_verify($password, $hashedPassword)) {
            $_SESSION['admin_id'] = $id;
            header("Location: SVM/admin/dashboard.php");
            exit;
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Admin not found.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login - SVM Admin Panel</title>
  <link rel="stylesheet" href="../style.css">
  <style>
    /* Simple styling for the admin login */
    .auth-form {
      max-width: 400px;
      margin: 3rem auto;
      padding: 2rem;
      background: #fff;
      border-radius: 0.5rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .auth-form h2 {
      text-align: center;
      margin-bottom: 1rem;
      color: #1e3a8a;
    }
    .auth-form .form-group {
      margin-bottom: 1.5rem;
    }
    .auth-form label {
      display: block;
      margin-bottom: 0.5rem;
      color: #1e3a8a;
    }
    .auth-form input {
      width: 100%;
      padding: 0.75rem;
      border: 2px solid #e5e7eb;
      border-radius: 0.375rem;
      font-size: 1rem;
    }
    .auth-form input:focus {
      border-color: #1e3a8a;
      outline: none;
    }
    .auth-form button {
      width: 100%;
      background: #1e3a8a;
      color: #fff;
      border: none;
      padding: 1rem;
      border-radius: 0.375rem;
      cursor: pointer;
      font-size: 1rem;
      transition: background 0.3s;
    }
    .auth-form button:hover {
      background: #1e40af;
    }
    .error-message {
      color: red;
      text-align: center;
      margin-bottom: 1rem;
    }
  </style>
</head>
<body>
  <div class="auth-form">
    <h2>Admin Login</h2>
    <?php if (isset($error)): ?>
      <div class="error-message"><?= $error ?></div>
    <?php endif; ?>
    <form action="" method="POST">
      <div class="form-group">
         <label for="username">Username:</label>
         <input type="text" id="username" name="username" required>
      </div>
      <div class="form-group">
         <label for="password">Password:</label>
         <input type="password" id="password" name="password" required>
      </div>
      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>
