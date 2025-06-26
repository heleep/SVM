<?php
include 'db/database_connection.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch events
$today = date('Y-m-d');
$sql = "SELECT * FROM SVM_Events WHERE Event_date >= '$today' ORDER BY Event_date ASC";
$result = $conn->query($sql);
$events = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SVM Institute of Technology</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.300.0/lucide.min.css">
    <script>
        // Initialize Lucide icons
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Navbar Styles */
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo img {
            width: 70px;
            height: auto;
        }

        .logo span {
            font-size: 1.5rem;
            font-weight: bold;
            color: #1e3a8a;
        }

        .nav-links {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .nav-link {
            text-decoration: none;
            color: #1e3a8a;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: #030712;
        }

        /* Active navigation link styling */
        .nav-link.active {
            color: #1e40af;
            font-weight: 600;
            position: relative;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            background-color: #1e40af;
        }

        /* Dropdown Styles */
        .dropdown {
            position: relative;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            top: calc(100% + 5px);
            left: 0;
            background: white;
            min-width: 200px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 0.5rem;
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.3s, transform 0.3s;
            z-index: 1000;
        }

        .dropdown:hover .dropdown-content {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        .dropdown-item {
            display: block;
            padding: 0.75rem 1rem;
            text-decoration: none;
            color: #1f2937;
            transition: background-color 0.2s;
        }

        /* Dropdown item active state */
        .dropdown-item.active {
            background-color: #f3f4f6;
            color: #1e40af;
            font-weight: 600;
        }

        .dropdown-item:hover {
            background-color: #f3f4f6;
            color: #1e40af;
        }

        .submenu {
            position: relative;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 180px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .dropdown-content .dropdown-item {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background 0.3s ease;
        }

        .dropdown-content .dropdown-item:hover {
            background-color: #ddd;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Submenu styling */
        .submenu {
            position: relative;
        }

        .submenu-content {
            display: none;
            position: absolute;
            left: 100%;
            top: 0;
            background-color: white;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        }

        .submenu a {
            padding: 12px 16px;
            display: block;
            color: black;
            text-decoration: none;
        }

        .submenu a:hover {
            background-color: #ddd;
        }

        .submenu:hover .submenu-content {
            display: block;
        }

        /* Mobile responsive styles */
        @media (max-width: 768px) {
            .nav-links {
                flex-direction: column;
            }

            .dropdown-content,
            .submenu-content {
                position: static;
            }
        }

        /* Slider Styles */
        .slider {
            height: 600px;
            position: relative;
            overflow: hidden;
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            background-size: cover;
            background-position: center;
        }

        .slide.active {
            opacity: 1;
        }

        .slide::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        .slide-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
            z-index: 1;
            width: 100%;
            max-width: 800px;
            padding: 0 2rem;
        }

        .slide-content h1 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 1rem;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s forwards;
        }

        .slide-content p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s 0.2s forwards;
        }

        .slide-content button {
            background: #1e3a8a;
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 9999px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s 0.4s forwards;
        }

        .slide-content button:hover {
            background: #1e40af;
        }

        /* Features Section */
        .features {
            padding: 5rem 0;
            background: #f9fafb;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            opacity: 0;
            transform: translateY(20px);
        }

        .feature-card.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .feature-card:hover {
            transform: translateY(-0.5rem);
        }

        .feature-card i {
            font-size: 3rem;
            color: #1e3a8a;
            margin-bottom: 1rem;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #1e3a8a;
        }

        .feature-card p {
            color: #6b7280;
        }

        /* Footer Styles */
        footer {
            background: #1e3a8a;
            color: white;
            padding: 3rem 0;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h4 {
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 0.5rem;
        }

        .footer-section a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-section a:hover {
            color: #bfdbfe;
        }

        .newsletter {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .newsletter input {
            flex: 1;
            padding: 0.75rem;
            border: none;
            border-radius: 0.375rem;
        }

        .newsletter button {
            background: #1e40af;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .newsletter button:hover {
            background: #1e4620;
        }

        .footer-bottom {
            border-top: 1px solid #2563eb;
            padding-top: 2rem;
            text-align: center;
        }

        /* About Section */
        .about {
            padding: 4rem 0;
            background: #fff;
            text-align: center;
        }

        .about h2 {
            font-size: 2.5rem;
            color: #1e3a8a;
            margin-bottom: 1rem;
        }

        .about p {
            font-size: 1.125rem;
            color: #333;
            margin-bottom: 1rem;
        }

        .about ul {
            list-style: none;
            padding: 0;
            margin: 1rem 0;
        }

        .about ul li {
            font-size: 1rem;
            margin: 0.5rem 0;
            color: #1e3a8a;
        }

        /* Programs Section */
        .programs {
            padding: 4rem 0;
            background: #f9fafb;
            text-align: center;
        }

        .programs h2 {
            font-size: 2.5rem;
            color: #1e3a8a;
            margin-bottom: 2rem;
        }

        .programs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .program-card {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .program-card h3 {
            color: #1e3a8a;
            margin-bottom: 1rem;
        }

        .program-card p {
            color: #6b7280;
            margin-bottom: 1rem;
        }

        .program-card ul {
            list-style: none;
            padding: 0;
            margin: 1rem 0;
        }

        .program-card ul li {
            font-size: 1rem;
            margin: 0.5rem 0;
            color: #1e3a8a;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                transform: translateX(-100%);
            }
            to {
                transform: translateX(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .slide-content h1 {
                font-size: 2rem;
            }

            .slide-content p {
                font-size: 1rem;
            }
        }

        /* ===== EVENTS SECTION ===== */
        .events {
            padding: 4rem 0;
            background: #f9fafb;
        }

        .events h2 {
            text-align: center;
            font-size: 2.5rem;
            color: #1e3a8a;
            margin-bottom: 2rem;
        }

        .events-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(500px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .event-card-link {
            display: block;
            text-decoration: none;
            color: inherit;
            margin-bottom: 20px;
            transition: transform 0.3s;
        }

        .event-card-link:hover {
            transform: translateY(-5px);
        }

        .event-card {
            display: flex;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            background: white;
            height: 200px;
        }

        .event-image {
            width: 300px;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-color: #f3f4f6;
        }

        .event-content {
            padding: 15px;
            flex: 1;
        }

        .event-content h3 {
            font-size: 1.4rem;
            margin-bottom: 10px;
            color: #1e3a8a;
        }

        .event-content .event-date {
            display: block;
            background: #1e3a8a;
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-bottom: 10px;
            width: fit-content;
        }

        .event-content p {
            color: #4b5563;
            line-height: 1.5;
        }

        .status-container {
            margin-bottom: 20px;
            text-align: center;
        }

        .status-message {
            padding: 15px;
            border-radius: 4px;
            text-align: center;
            font-weight: 500;
            margin: 20px auto;
            max-width: 600px;
        }

        .status-message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Responsive adjustments */
        @media (max-width: 600px) {
            .events-container {
                grid-template-columns: 1fr;
            }
            
            .event-card {
                flex-direction: column;
                height: auto;
            }
            
            .event-image {
                width: 100%;
                height: 200px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container nav-content">
            <div class="logo">
                <img src="assets/images/logo.jpg" alt="SVM Logo">
                <span>SVM Institute of Technology</span>
            </div>
            <div class="nav-links">
                <a href="#" class="nav-link active">Home</a>
                <a href="#" class="nav-link">About</a>
                <a href="#" class="nav-link">Programs</a>
                <a href="#" class="nav-link">Admissions</a>
                <a href="#" class="nav-link">Research</a>
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
                <a href="#" class="nav-link">Contact</a>
            </div>
        </div>
    </nav>

    <!-- Slider -->
    <div class="slider">
        <div class="slides">
            <div class="slide active" style="background-image: url('https://images.unsplash.com/photo-1562774053-701939374585?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');">
                <div class="slide-content">
                    <h1>Welcome to SVM Institute of Technology</h1>
                    <p>Empowering future innovators with excellence in education.</p>
                    <button>
                        Learn More
                        <i class="lucide lucide-chevron-right"></i>
                    </button>
                </div>
            </div>
            <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');">
                <div class="slide-content">
                    <h1>Innovative Research</h1>
                    <p>Advancing technology through groundbreaking research.</p>
                    <button>
                        Explore Research
                        <i class="lucide lucide-chevron-right"></i>
                    </button>
                </div>
            </div>
            <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1592280771190-3e2e4d571952?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');">
                <div class="slide-content">
                    <h1>Modern Facilities</h1>
                    <p>Experience state-of-the-art labs and learning spaces.</p>
                    <button>
                        View Facilities
                        <i class="lucide lucide-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="features-grid">
                <div class="feature-card visible">
                    <i class="lucide lucide-graduation-cap"></i>
                    <h3>Quality Education</h3>
                    <p>Our curriculum is designed to meet industry standards and prepare students for real-world challenges.</p>
                </div>
                <div class="feature-card visible">
                    <i class="lucide lucide-flask-conical"></i>
                    <h3>Research Excellence</h3>
                    <p>State-of-the-art research facilities with industry partnerships for innovative projects.</p>
                </div>
                <div class="feature-card visible">
                    <i class="lucide lucide-users"></i>
                    <h3>Industry Connections</h3>
                    <p>Strong ties with leading companies for internships and job placements.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about">
        <div class="container">
            <h2>About SVM Institute</h2>
            <p>Founded in 1998, SVM Institute of Technology has been a pioneer in technical education, providing quality education and producing industry-ready professionals.</p>
            <p>Our mission is to foster innovation, critical thinking, and leadership skills in our students.</p>
            
            <div class="features-grid" style="margin-top: 3rem;">
                <div class="feature-card">
                    <h3>20,000+</h3>
                    <p>Alumni Worldwide</p>
                </div>
                <div class="feature-card">
                    <h3>120+</h3>
                    <p>Faculty Members</p>
                </div>
                <div class="feature-card">
                    <h3>50+</h3>
                    <p>Industry Partnerships</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Programs Section -->
    <section class="programs">
        <div class="container">
            <h2>Our Programs</h2>
            <div class="programs-grid">
                <div class="program-card">
                    <h3>Computer Science</h3>
                    <p>Cutting-edge curriculum in AI, machine learning, and software engineering.</p>
                    <ul>
                        <li>B.Tech in Computer Science</li>
                        <li>M.Tech in AI & ML</li>
                        <li>Ph.D in Computer Science</li>
                    </ul>
                </div>
                <div class="program-card">
                    <h3>Electronics & Communication</h3>
                    <p>Specialized programs in VLSI, embedded systems, and communication technologies.</p>
                    <ul>
                        <li>B.Tech in ECE</li>
                        <li>M.Tech in VLSI Design</li>
                        <li>Ph.D in Electronics</li>
                    </ul>
                </div>
                <div class="program-card">
                    <h3>Mechanical Engineering</h3>
                    <p>Programs focusing on robotics, automotive engineering, and thermal sciences.</p>
                    <ul>
                        <li>B.Tech in Mechanical</li>
                        <li>M.Tech in Robotics</li>
                        <li>Ph.D in Mechanical</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Events Section -->
    <section class="events">
        <div class="container">
            <h2>Upcoming Events</h2>
            <div class="status-container">
                <?php if (empty($events)): ?>
                    <div class="status-message error">No upcoming events found.</div>
                <?php endif; ?>
            </div>
            
            <div class="events-container">
                <?php foreach ($events as $event): ?>
                    <?php
                    $imagePath = $event['Event_image'] ? "assets/uploads/{$event['Event_image']}" : "https://via.placeholder.com/300x150?text=Event+Image";
                    ?>
                    <a href="template/events_detail.php?id=<?= $event['id'] ?>" class="event-card-link">
                        <div class="event-card">
                            <div class="event-image" style="background-image: url('<?= $imagePath ?>');"></div>
                            <div class="event-content">
                                <h3><?= htmlspecialchars($event['SVM_Event']) ?></h3>
                                <span class="event-date"><?= date('F j, Y', strtotime($event['Event_date'])) ?></span>
                                <p><?= htmlspecialchars(substr($event['Event_description'], 0, 100)) ?>...</p>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-section">
                    <h4>Contact Us</h4>
                    <ul>
                        <li><i class="lucide lucide-map-pin"></i> 123 Education Street, Tech City</li>
                        <li><i class="lucide lucide-phone"></i> +1 (555) 123-4567</li>
                        <li><i class="lucide lucide-mail"></i> info@svminstitute.edu</li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Programs</a></li>
                        <li><a href="#">Admissions</a></li>
                        <li><a href="#">Campus Life</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Newsletter</h4>
                    <p>Subscribe to our newsletter for updates.</p>
                    <div class="newsletter">
                        <input type="email" placeholder="Your email">
                        <button>Subscribe</button>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 SVM Institute of Technology. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Slider functionality
        const slides = [
            {
                image: 'https://images.unsplash.com/photo-1562774053-701939374585?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80',
                title: 'Welcome to SVM Institute of Technology',
                description: 'Empowering future innovators with excellence in education.'
            },
            {
                image: 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80',
                title: 'Innovative Research',
                description: 'Advancing technology through groundbreaking research.'
            },
            {
                image: 'https://images.unsplash.com/photo-1592280771190-3e2e4d571952?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80',
                title: 'Modern Facilities',
                description: 'Experience state-of-the-art labs and learning spaces.'
            }
        ];

        // Automatic slider
        let currentSlide = 0;
        const slideElements = document.querySelectorAll('.slide');

        function nextSlide() {
            slideElements[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slideElements.length;
            slideElements[currentSlide].classList.add('active');
        }

        setInterval(nextSlide, 5000);

        // Feature cards animation
        const featureCards = document.querySelectorAll('.feature-card');
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        featureCards.forEach(card => {
            observer.observe(card);
        });

        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>
</html>