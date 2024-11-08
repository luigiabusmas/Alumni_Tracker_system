<?php 
require 'session.php'; 

// Check if the event ID is provided
if (isset($_GET['id'])) {
    $event_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Fetch the event details from the database
    $sql = "SELECT * FROM events WHERE id = '$event_id'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $event = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('Event not found.'); window.location.href = 'eventslist.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('No event ID provided.'); window.location.href = 'eventslist.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Event Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./resources/styles.css"> 
    <link rel="stylesheet" href="./resources/dashboard.css"> 
    <style>
        body {
            background-color: #f4f7fa;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .event-section {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 30px;
        }
        .event-section h2 {
            color: #007bff;
        }
        .event-detail {
            margin-bottom: 15px;
        }
        .event-detail label {
            font-weight: bold;
            color: #555;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <div class="text-left mt-4">
        <a href="Events.php" class="btn btn-secondary">Back to Events List</a>
    </div>
    <div class="event-section">
        <!-- Event Title -->
        <h2><?php echo htmlspecialchars($event['title']); ?></h2>
        
        <!-- Event Image -->
        <?php if (!empty($event['image'])): ?>
        <div class="text-center mb-4">
            <img src="image/<?php echo htmlspecialchars($event['image']); ?>" alt="Event Image" class="img-fluid" style="max-height: 300px;">
        </div>
        <?php endif; ?>

        <!-- Event Details -->
        <div class="event-detail">
            <label>Description:</label>
            <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
        </div>
        <div class="event-detail">
            <label>Author ID:</label>
            <p><?php echo htmlspecialchars($event['author_id']); ?></p>
        </div>
        <div class="event-detail">
            <label>Status:</label>
            <p><?php echo htmlspecialchars($event['status']); ?></p>
        </div>
        <div class="event-detail">
            <label>Start Date:</label>
            <p><?php echo !empty($event['start_date']) ? date('F d, Y H:i', strtotime($event['start_date'])) : 'N/A'; ?></p>
        </div>
        <div class="event-detail">
            <label>End Date:</label>
            <p><?php echo !empty($event['end_date']) ? date('F d, Y H:i', strtotime($event['end_date'])) : 'N/A'; ?></p>
        </div>
        <div class="event-detail">
            <label>Location:</label>
            <p><?php echo htmlspecialchars($event['location']); ?></p>
        </div>
        <div class="event-detail">
            <label>Event Type:</label>
            <p><?php echo htmlspecialchars($event['event_type']); ?></p>
        </div>
        <div class="event-detail">
            <label>Created At:</label>
            <p><?php echo date('F d, Y H:i', strtotime($event['created_at'])); ?></p>
        </div>
        <div class="event-detail">
            <label>Updated At:</label>
            <p><?php echo date('F d, Y H:i', strtotime($event['updated_at'])); ?></p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="./resources/popper.min.js"></script>
<script src="./resources/bootstrap.min.js"></script>

</body>
</html>
