<?php
// Use the correct path to your connection file
include '../db/database_connection.php'; 

// Query to get SVM_Event and Event_date from SVM_Events table
$query = "SELECT SVM_Event, Event_date FROM SVM_Events ORDER BY Event_date DESC";
$result = mysqli_query($conn, $query);

$events = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $events[] = $row;
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($events);
?>
