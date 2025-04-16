<?php
session_start();
include '../db/database_connection.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the email and password from the form
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // If the user is the admin, check credentials
    if ($email === 'admin@gmail.com' && $password === '@admin_') {
        // Set a session variable to indicate admin is logged in
        $_SESSION['admin'] = true;
        $_SESSION['admin_email'] = $email;
        // Redirect to admin dashboard page
        header("Location: /SVM/admin/admin_home.html");
        exit;
    } else {
        // For any other user, look up in the database
        // Use a prepared statement for security
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // Check if we got exactly one matching record
        if ($stmt->num_rows === 1) {
            $stmt->bind_result($user_id, $hashedPassword);
            $stmt->fetch();
            // Verify the password. If you are storing plain text (not recommended), you can do:
            // if ($password === $hashedPassword) { ... }
            // Otherwise, if you are using password hashing:
            if (password_verify($password, $hashedPassword)) {
                // Store user session info
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_email'] = $email;
                // Redirect to home page (index.html or a dashboard)
                header("Location: /SVM/index.html");
                exit;
            } else {
                $error = "Invalid password. Please try again.";
            }
        } else {
            $error = "No user found with that email.";
        }
        $stmt->close();
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
