<?php
include '../db/database_connection.php'; 

// Modified to include the id field
$query = "SELECT id, SVM_Event, Event_description, Event_date, Event_image FROM SVM_Events ORDER BY Event_date DESC";
$result = mysqli_query($conn, $query);

$events = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $events[] = $row;
    }
} else {
    // Log error if needed
    error_log("Error fetching events: " . mysqli_error($conn));
}

header('Content-Type: application/json');
echo json_encode($events);
?>