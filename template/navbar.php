<?php
// navbar.php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar">
  <div class="container">
    <div class="nav-content">
      <div class="logo">
        <img src="/SVM/assets/images/logo.jpg" alt="SVM Logo">
        <span>SVM Institute of Technology</span>
      </div>
      
      <div class="nav-links">
        <a href="/SVM/index.php" class="nav-link <?= ($currentPage === 'index.php') ? 'active' : '' ?>">Home</a>
        <a href="/SVM/template/About.html" class="nav-link <?= ($currentPage === 'About.html') ? 'active' : '' ?>">About</a>
        <!-- Rest of navigation links -->
      </div>
    </div>
  </div>
</nav>