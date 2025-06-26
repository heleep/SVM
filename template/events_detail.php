<?php
include '../db/database_connection.php';

// Get event ID from URL parameter
$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch event details
$event = null;
if ($event_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM SVM_Events WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $event ? htmlspecialchars($event['SVM_Event']) : 'Event Not Found' ?> | SVM Institute of Technology</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.300.0/lucide.min.css">
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
            background-color: #f9fafb;
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

        /* Breadcrumb */
        .breadcrumb {
            padding: 1rem 0;
            background: #f3f4f6;
            margin-bottom: 2rem;
        }

        .breadcrumb-links {
            display: flex;
            gap: 0.5rem;
        }

        .breadcrumb-links a {
            color: #1e3a8a;
            text-decoration: none;
        }

        .breadcrumb-links a:hover {
            text-decoration: underline;
        }

        /* Event Detail Styles */
        .event-detail {
            padding: 3rem 0;
        }

        .event-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .event-header h1 {
            font-size: 2.5rem;
            color: #1e3a8a;
            margin-bottom: 1rem;
        }

        .event-date {
            display: inline-block;
            background: #1e3a8a;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 9999px;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }

        .event-content {
            display: flex;
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .event-image-container {
            flex: 1;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            height: 400px;
        }

        .event-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            background-color: #f3f4f6;
        }

        .event-details {
            flex: 1;
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .event-description {
            margin-top: 2rem;
            line-height: 1.8;
        }

        .event-description p {
            margin-bottom: 1.5rem;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #1e3a8a;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 9999px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s;
            margin-top: 1rem;
        }

        .back-button:hover {
            background: #1e40af;
        }

        /* Related Events */
        .related-events {
            padding: 3rem 0;
            background: white;
            border-top: 1px solid #e5e7eb;
        }

        .section-title {
            text-align: center;
            font-size: 2rem;
            color: #1e3a8a;
            margin-bottom: 2rem;
        }

        .events-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }

        .event-card-link {
            display: block;
            text-decoration: none;
            color: inherit;
            transition: transform 0.3s;
        }

        .event-card-link:hover {
            transform: translateY(-5px);
        }

        .event-card {
            display: flex;
            flex-direction: column;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            background: white;
            height: 100%;
        }

        .event-card-image {
            width: 100%;
            height: 200px;
            background-size: cover;
            background-position: center;
            background-color: #f3f4f6;
        }

        .event-card-content {
            padding: 15px;
            flex: 1;
        }

        .event-card-content h3 {
            font-size: 1.4rem;
            margin-bottom: 10px;
            color: #1e3a8a;
        }

        .event-card-content .event-date {
            display: inline-block;
            background: #1e3a8a;
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .event-card-content p {
            color: #4b5563;
            line-height: 1.5;
        }

        /* Footer Styles */
        footer {
            background: #1e3a8a;
            color: white;
            padding: 3rem 0;
            margin-top: 3rem;
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .event-content {
                flex-direction: column;
            }
            
            .event-image-container {
                height: 300px;
            }
        }

        /* Not Found Styles */
        .not-found {
            text-align: center;
            padding: 5rem 0;
        }

        .not-found h1 {
            font-size: 3rem;
            color: #1e3a8a;
            margin-bottom: 1rem;
        }

        .not-found p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container nav-content">
            <div class="logo">
                <img src="https://www.svgrepo.com/show/499795/education.svg" alt="SVM Logo">
                <span>SVM Institute of Technology</span>
            </div>
            <div class="nav-links">
                <a href="../index.php" class="nav-link">Home</a>
                <a href="#" class="nav-link">About</a>
                <a href="#" class="nav-link">Programs</a>
                <a href="#" class="nav-link">Admissions</a>
                <a href="#" class="nav-link">Research</a>
                <a href="#" class="nav-link">Campus Life</a>
                <a href="#" class="nav-link">Contact</a>
            </div>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <div class="container breadcrumb-links">
            <a href="../index.php">Home</a>
            <span>/</span>
            <a href="#">Events</a>
            <span>/</span>
            <span>Event Details</span>
        </div>
    </div>

    <?php if ($event): ?>
    <!-- Event Detail Section -->
    <section class="event-detail">
        <div class="container">
            <div class="event-header">
                <h1><?= htmlspecialchars($event['SVM_Event']) ?></h1>
                <div class="event-date">
                    <?= date('F j, Y', strtotime($event['Event_date'])) ?>
                </div>
            </div>
            
            <div class="event-content">
                <div class="event-image-container">
                    <?php if ($event['Event_image']): ?>
                        <img src="../assets/uploads/<?= htmlspecialchars($event['Event_image']) ?>" alt="<?= htmlspecialchars($event['SVM_Event']) ?>" class="event-image">
                    <?php else: ?>
                        <div class="event-image" style="background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #9ca3af;">
                            <span>No Image Available</span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="event-details">
                    <h2>Event Details</h2>
                    <div class="event-description">
                        <?= nl2br(htmlspecialchars($event['Event_description'])) ?>
                    </div>
                    
                    <a href="../index.php" class="back-button">
                        <i class="lucide lucide-arrow-left"></i> Back to Events
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Events -->
    <section class="related-events">
        <div class="container">
            <h2 class="section-title">Other Upcoming Events</h2>
            <div class="events-container">
                <!-- This would be populated with other events from the database -->
                <a href="events_detail.php?id=2" class="event-card-link">
                    <div class="event-card">
                        <div class="event-card-image" style="background-image: url('../assets/uploads/takeattendance.png');"></div>
                        <div class="event-card-content">
                            <h3>Freshers Welcome</h3>
                            <span class="event-date">October 19, 2025</span>
                            <p>Welcome event for new students joining our institute.</p>
                        </div>
                    </div>
                </a>
                
                <a href="events_detail.php?id=3" class="event-card-link">
                    <div class="event-card">
                        <div class="event-card-image" style="background-image: url('../assets/uploads/logo.png');"></div>
                        <div class="event-card-content">
                            <h3>Tech Symposium</h3>
                            <span class="event-date">May 31, 2025</span>
                            <p>Annual technology symposium showcasing student projects.</p>
                        </div>
                    </div>
                </a>
                
                <a href="events_detail.php?id=4" class="event-card-link">
                    <div class="event-card">
                        <div class="event-card-image" style="background-image: url('../assets/uploads/home.png');"></div>
                        <div class="event-card-content">
                            <h3>Industry Connect</h3>
                            <span class="event-date">August 9, 2025</span>
                            <p>Networking event with industry professionals and recruiters.</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>
    <?php else: ?>
    <!-- Event Not Found -->
    <section class="not-found">
        <div class="container">
            <h1>Event Not Found</h1>
            <p>The event you're looking for doesn't exist or has been removed.</p>
            <a href="../index.php" class="back-button">
                <i class="lucide lucide-arrow-left"></i> Back to Home
            </a>
        </div>
    </section>
    <?php endif; ?>

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
                        <li><a href="../index.php">Home</a></li>
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
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>
</html>