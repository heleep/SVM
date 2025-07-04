<?php
session_start();

// Decide user type
if (isset($_SESSION['principal'])) {
  $user_type = 'principal';
} else {
  $user_type = 'guest'; // no login for normal users
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SVM Institute of Technology</title>
  <link rel="stylesheet" href="../style.css">
  
</head>
<body>

  
  <div class="add_bar" style="text-align: center; margin-top: 30px;">
    <a href="/SVM/template/add_event.php" class="add-event-btn" style="
      display: inline-block;
      padding: 10px 20px;
      background-color: #003366;
      color: white;
      border: none;
      border-radius: 5px;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s;
      ">+ Add Event</a>
  </div>
  <?php if ($user_type == 'guest'): ?>
    <style>
      .add_bar {
        display: block;
      }
    </style>
  <?php endif; ?>
  <nav class="navbar">
    <div class="container">
      <div class="nav-content">
        <div class="logo">
          <img src="/SVM/assets/images/logo.jpg" alt="SVM Logo">
          <span>SVM Institute of Technology</span>
        </div>
        
        <div class="nav-links">
          <a href="../index.php" class="nav-link active">Home</a>
          <a href="/template/About.html" class="nav-link">About</a>
          <div class="dropdown">
            <a href="#" class="nav-link">Academics</a>
            <div class="dropdown-content">
              <a href="#" class="dropdown-item">Undergraduate Programs</a>
              <a href="#" class="dropdown-item">Graduate Programs</a>
              <a href="#" class="dropdown-item">Online Learning</a>
              <a href="#" class="dropdown-item">Short Courses</a>
            </div>
          </div>
          <div class="dropdown">
            <a href="#" class="nav-link">Research</a>
            <div class="dropdown-content">
              <a href="#" class="dropdown-item">Research Centers</a>
              <a href="#" class="dropdown-item">Publications</a>
              <a href="#" class="dropdown-item">Collaborations</a>
              <a href="#" class="dropdown-item">Innovation Labs</a>
            </div>
          </div>
          <a href="#" class="nav-link">Admissions</a>
          <div class="dropdown">
            <a class="nav-link">College</a>
            <div class="dropdown-content">
              <a href="/SVM/template/jp_arts.html" class="dropdown-item">JP College of Arts</a>
              <a href="/SVM/template/jp_science.html" class="dropdown-item">JP College of Science</a>
              <a href="/SVM/template/svmit.html" class="dropdown-item">SVMIT College</a>
              <a href="/SVM/template/mk_commerce.html" class="dropdown-item">MK College of Commerce</a>
              <a href="/SVM/template/mk_arts.html" class="dropdown-item">MK College of Arts</a>
              <div class="submenu">
                <a >School</a>
                <div class="submenu-content">
                    <a href="/SVM/template/svm_school.html">SVM School</a>
                </div>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <div class="slider">
    <div class="slides">
      <!-- Slides will be inserted by JavaScript -->
    </div>
  </div>

  <div class="features">
    <div class="container">
      <div class="features-grid">
        <div class="feature-card">
          <i class="lucide lucide-book-open"></i>
          <h3>World-Class Education</h3>
          <p>Experience learning from industry experts and renowned academicians in state-of-the-art facilities.</p>
        </div>
        <div class="feature-card">
          <i class="lucide lucide-users"></i>
          <h3>Diverse Community</h3>
          <p>Join a vibrant community of learners from across the globe, sharing knowledge and cultures.</p>
        </div>
        <div class="feature-card">
          <i class="lucide lucide-building-2"></i>
          <h3>Modern Campus</h3>
          <p>Study in our modern campus equipped with cutting-edge technology and research facilities.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Enhanced About Section -->
  <section class="about">
    <div class="container">
      <h2>About SVM Institute of Technology</h2>
      <p>
        At SVM Institute of Technology, we pride ourselves on fostering innovation, critical thinking, and academic excellence. Our curriculum challenges and inspires students to become leaders in their fields.
      </p>
      <p>
        With state-of-the-art laboratories, innovative teaching methods, and extensive industry collaborations, we provide a dynamic learning environment designed for the future.
      </p>
      <ul>
        <li>Innovative teaching methods</li>
        <li>World-class research facilities</li>
        <li>Global industry collaborations</li>
      </ul>
    </div>
  </section>

  <!-- Enhanced Programs Section -->
  <section class="programs">
    <div class="container">
      <h2>Our Programs</h2>
      <div class="programs-grid">
        <div class="program-card">
          <h3>B.Tech</h3>
          <p>Dive into various engineering disciplines with advanced labs, project-based learning, and industry internships.</p>
          <ul>
            <li>Computer Science</li>
            <li>Electronics</li>
            <li>Mechanical Engineering</li>
          </ul>
        </div>
        <div class="program-card">
          <h3>M.Tech</h3>
          <p>Enhance your technical skills and research capabilities in specialized fields of engineering and technology.</p>
          <ul>
            <li>Advanced Computing</li>
            <li>Robotics</li>
            <li>Data Science</li>
          </ul>
        </div>
        <div class="program-card">
          <h3>MBA</h3>
          <p>Develop leadership and strategic management skills with a curriculum designed for future business leaders.</p>
          <ul>
            <li>Marketing & Finance</li>
            <li>Operations Management</li>
            <li>Entrepreneurship</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <section class="events">
    <div class="container">
      <h2>Upcoming Events</h2>
      <div id="eventContainer">
        <!-- Events from the database will be loaded here -->
      </div>
    </div>
  </section>

  <footer>
    <div class="container">
      <div class="footer-grid">
        <div class="footer-section">
          <h4>Quick Links</h4>
          <ul>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Admissions</a></li>
            <li><a href="#">Academic Programs</a></li>
            <li><a href="#">Research</a></li>
          </ul>
        </div>
        <div class="footer-section">
          <h4>Resources</h4>
          <ul>
            <li><a href="#">Library</a></li>
            <li><a href="#">Student Portal</a></li>
            <li><a href="#">Career Services</a></li>
            <li><a href="#">Campus Life</a></li>
          </ul>
        </div>
        <div class="footer-section">
          <h4>Connect With Us</h4>
          <p>Stay updated with our newsletter</p>
          <div class="newsletter">
            <input type="email" placeholder="Enter your email">
            <button>Subscribe</button>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2024 SVM Institute of Technology. All rights reserved.</p>
      </div>
    </div>
  </footer>
  <script src="/SVM/script.js"></script>
  <script>
    fetch('get_events.php')
      .then(response => response.json())
      .then(data => {
        const container = document.getElementById('eventContainer');
        if(data.length === 0) {
          container.innerHTML = "<p>No events available at the moment.</p>";
          return;
        }
        data.forEach(eventItem => {
          const eventCard = document.createElement('div');
          eventCard.className = 'event-card';
          eventCard.innerHTML = `
            <h3>${eventItem.SVM_Event}</h3>
            <p>${eventItem.Event_date}</p>
          `;
          container.appendChild(eventCard);
        });
      })
      .catch(error => console.error('Error fetching events:', error));  
  </script>
</body>
</html>