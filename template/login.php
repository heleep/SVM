<?php
session_start();
include '../db/database_connection.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  // Admin login check
  if ($email === 'admin@gmail.com' && $password === '@admin_') {
      $_SESSION['admin'] = true;
      $_SESSION['admin_email'] = $email;
      header("Location: /SVM/admin/admin_home.html");
      exit;
  }

  // Principal login check
  $stmt = $conn->prepare("SELECT user_name, user_password FROM SVM_Principal WHERE user_name = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows === 1) {
      $stmt->bind_result($principal_email, $principal_password);
      $stmt->fetch();

      if ($password === $principal_password) {
          $_SESSION['principal'] = true;
          $_SESSION['principal_email'] = $email;
          header("Location: /SVM/template/index.php");
          exit;
      } else {
          $error = "Invalid password. Please try again.";
      }
      $stmt->close();
  } else {
      // If neither admin nor principal found
      $error = "Invalid credentials. Only authorized personnel can login.";
  }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - SVM Institute</title>
  <link rel="stylesheet" href="style.css">
  <style>
    /* Simple inline styling for error messages */
    .error {
        color: red;
        text-align: center;
        margin: 1rem 0;
    }
  </style>
</head>
<body>
  <section class="about" style="min-height: 70vh;">
    <div class="container">
      <h2>Login to Your Account</h2>
      <?php if (isset($error)): ?>
        <div class="error"><?= $error ?></div>
      <?php endif; ?>
      <form id="loginForm" class="auth-form" action="../template/login.php" method="POST">
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="auth-btn">Login</button>
      </form>
      <p style="margin-top: 1rem;">Don't have an account? <a href="register.html">Register here</a></p>
    </div>
  </section>
  <!-- Footer section here -->
</body>
</html>
