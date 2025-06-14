<?php
include '../db/database_connection.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM SVM_Events WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("<h2>Event not found</h2>");
}

$event = $result->fetch_assoc();
$image = !empty($event['Event_image']) 
    ? '/SVM/assets/uploads/' . htmlspecialchars($event['Event_image']) 
    : '/SVM/assets/images/default_event.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($event['SVM_Event']) ?> - SVM Institute</title>
  <link rel="stylesheet" href="/SVM/style.css">
</head>
<body>
  <?php include '../template/navbar.php'; ?>

  <div class="event-detail-container">
    <div class="event-detail-image">
      <img src="<?= $image ?>" alt="<?= htmlspecialchars($event['SVM_Event']) ?>">
    </div>
    <div class="event-detail-content">
      <h1><?= htmlspecialchars($event['SVM_Event']) ?></h1>
      <p class="event-date"><strong>Date:</strong> <?= htmlspecialchars($event['Event_date']) ?></p>
      <div class="event-description">
        <?= nl2br(htmlspecialchars($event['Event_description'])) ?>
      </div>
      <a href="/SVM/index.html" class="back-button">⬅ Back to Home</a>
    </div>
  </div>

  <?php include '../template/footer.php'; ?>
</body>
</html>