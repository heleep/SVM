<?php
include '../db/database_connection.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$today = date('Y-m-d');
$events = [];

// Use prepared statement
$stmt = $conn->prepare("SELECT id, SVM_Event, Event_description, Event_date, Event_image 
                        FROM SVM_Events 
                        WHERE Event_date >= ? 
                        ORDER BY Event_date ASC");

if ($stmt) {
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        // Validate date format
        if (DateTime::createFromFormat('Y-m-d', $row['Event_date'])) {
            $events[] = $row;
        }
    }
    $stmt->close();
} else {
    error_log("Prepare failed: " . $conn->error);
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($events);
?>