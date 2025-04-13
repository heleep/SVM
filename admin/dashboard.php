<?php
session_start();
include '../db/database_connection.php';

// Authentication check
if (!isset($_SESSION['admin'])) {
    header("Location: /SVM/admin/dashboard.php");
    exit();
}

// Handle Delete
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $_SESSION['message'] = "Event deleted successfully";
    header("Location: dashboard.php");
    exit();
}

// Handle Add/Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_event'])) {
        $id = $_POST['event_id'];
        $name = $_POST['event_name'];
        $description = $_POST['event_description'];
        $date = $_POST['event_date'];

        if ($id) {
            // Update existing event
            $stmt = $conn->prepare("UPDATE events SET event_name=?, event_description=?, event_date=? WHERE id=?");
            $stmt->bind_param("sssi", $name, $description, $date, $id);
        } else {
            // Add new event
            $stmt = $conn->prepare("INSERT INTO events (event_name, event_description, event_date) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $description, $date);
        }
        
        if ($stmt->execute()) {
            $_SESSION['message'] = $id ? "Event updated successfully" : "Event added successfully";
        } else {
            $_SESSION['error'] = "Database error: " . $conn->error;
        }
        header("Location: dashboard.php");
        exit();
    }
}

// Fetch events
$events = $conn->query("SELECT * FROM events ORDER BY event_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Add these in head section -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <!-- Add this modal for add/edit -->
    <div class="modal fade" id="eventModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Manage Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="event_id" id="event_id">
                        <div class="mb-3">
                            <label>Event Name</label>
                            <input type="text" name="event_name" id="event_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="event_description" id="event_description" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Date</label>
                            <input type="date" name="event_date" id="event_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="save_event" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modified table section -->
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        <?php while($event = $events->fetch_assoc()): ?>
        <tr>
            <td><?= $event['id'] ?></td>
            <td><?= htmlspecialchars($event['event_name']) ?></td>
            <td><?= htmlspecialchars($event['event_description']) ?></td>
            <td><?= $event['event_date'] ?></td>
            <td>
                <button class="btn btn-edit" 
                        data-id="<?= $event['id'] ?>"
                        data-name="<?= htmlspecialchars($event['event_name']) ?>"
                        data-desc="<?= htmlspecialchars($event['event_description']) ?>"
                        data-date="<?= $event['event_date'] ?>">
                    Edit
                </button>
                <form method="POST" style="display:inline">
                    <input type="hidden" name="id" value="<?= $event['id'] ?>">
                    <button type="submit" name="delete" class="btn btn-danger" 
                            onclick="return confirm('Delete this event?')">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Add New Event Button -->
    <button class="btn btn-success" id="addEvent">Add New Event</button>

    <script>
    // Edit Event Handler
    $('.btn-edit').click(function() {
        const event = {
            id: $(this).data('id'),
            name: $(this).data('name'),
            desc: $(this).data('desc'),
            date: $(this).data('date')
        };
        
        $('#event_id').val(event.id);
        $('#event_name').val(event.name);
        $('#event_description').val(event.desc);
        $('#event_date').val(event.date);
        $('#eventModal').modal('show');
    });

    // Add New Event Handler
    $('#addEvent').click(function() {
        $('#event_id').val('');
        $('#event_name').val('');
        $('#event_description').val('');
        $('#event_date').val('');
        $('#eventModal').modal('show');
    });

    // Show messages
    <?php if(isset($_SESSION['message'])): ?>
        alert('<?= $_SESSION['message'] ?>');
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        alert('Error: <?= $_SESSION['error'] ?>');
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    </script>
</body>
</html>