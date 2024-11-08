<?php 
require 'session.php'; 

// Check if the event ID is provided
if (isset($_GET['id'])) {
    $event_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Fetch the existing event details from the database
    $sql = "SELECT * FROM events WHERE id = '$event_id'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $event = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('Event not found.'); window.location.href = 'Events.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('No event ID provided.'); window.location.href = 'Events.php';</script>";
    exit;
}

// Handle form submission for updating the event
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $author_id = mysqli_real_escape_string($conn, $_POST['author_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $event_type = mysqli_real_escape_string($conn, $_POST['event_type']);
    $updated_at = date('Y-m-d H:i:s');

    // Image upload handling
    $image = $event['image']; // Retain old image if not updated
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        $image_new_name = uniqid() . '.' . $image_extension;

        // Check for valid image types
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($image_extension), $allowed_extensions)) {
            // Move the uploaded image to the 'event_images' folder
            $image_upload_path = 'image/' . $image_new_name;
            if (move_uploaded_file($image_tmp_name, $image_upload_path)) {
                $image = $image_new_name;
            } else {
                echo "<script>alert('Error uploading image.');</script>";
            }
        } else {
            echo "<script>alert('Invalid image format. Only jpg, jpeg, png, and gif are allowed.');</script>";
        }
    }

    // Update the event in the database
    $update_sql = "UPDATE events SET 
                    title = '$title', 
                    description = '$description', 
                    author_id = '$author_id', 
                    status = '$status', 
                    start_date = '$start_date', 
                    end_date = '$end_date', 
                    location = '$location', 
                    event_type = '$event_type', 
                    image = '$image', 
                    updated_at = '$updated_at' 
                   WHERE id = '$event_id'";

    if (mysqli_query($conn, $update_sql)) {
        echo "<script>
                alert('Event updated successfully.');
                window.location.href = 'EditEvent.php?id=$event_id';
              </script>";
    } else {
        echo "Error: " . $update_sql . "<br>" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
        .form-section {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 30px;
        }
        .form-section h2 {
            color: #007bff;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>
<div class="container">
<div class="form-section d-flex justify-content-between">
                                    <div class="text-left">
                                        <a href="Events.php" class="btn btn-success">Back to list</a>
                            
                                    </div>


                                            <div class="text-right">
                                            <a href="DeleteEvents.php?id=<?php echo htmlspecialchars($event['id']); ?>"  
                                            class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this Event? This action cannot be undone.');">Delete</a>
                                        </div>
                    </div>
<div class="form-section">
    <h2>Edit Event</h2>

    <form action="EditEvent.php?id=<?php echo $event_id; ?>" method="POST" enctype="multipart/form-data">
        
    <div class="form-group">
    <p>Current Image: <img src="image/<?php echo $event['image']; ?>" alt="Event Image" style="max-height: 100px;"></p>
            <label for="image">Upload Image:</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
            <?php if (!empty($event['image'])): ?>
                
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="title">Event Title:</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Event Description:</label>
            <textarea class="form-control" id="description" name="description" rows="5" required><?php echo htmlspecialchars($event['description']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="author_id">Author ID:</label>
            <input type="text" class="form-control" id="author_id" name="author_id" value="<?php echo htmlspecialchars($event['author_id']); ?>" readonly >
        </div>

        <div class="form-group">
            <label for="status">Status:</label>
            <select class="form-control" id="status" name="status">
                <option value="Draft" <?php echo $event['status'] == 'Draft' ? 'selected' : ''; ?>>Draft</option>
                <option value="Published" <?php echo $event['status'] == 'Published' ? 'selected' : ''; ?>>Published</option>
                <option value="Archive" <?php echo $event['status'] == 'Archive' ? 'selected' : ''; ?>>Archive</option>
            </select>
        </div>

        <div class="form-group">
            <label for="start_date">Start Date:</label>
            <input type="datetime-local" class="form-control" id="start_date" name="start_date" value="<?php echo date('Y-m-d\TH:i', strtotime($event['start_date'])); ?>">
        </div>

        <div class="form-group">
            <label for="end_date">End Date:</label>
            <input type="datetime-local" class="form-control" id="end_date" name="end_date" value="<?php echo date('Y-m-d\TH:i', strtotime($event['end_date'])); ?>">
        </div>

        <div class="form-group">
            <label for="location">Location:</label>
            <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required>
        </div>

        <div class="form-group">
            <label for="event_type">Event Type:</label>
            <input type="text" class="form-control" id="event_type" name="event_type" value="<?php echo htmlspecialchars($event['event_type']); ?>" required>
        </div>


        <div class="text-center">
            <button type="submit" class="btn btn-primary">Update Event</button>
            <a href="events.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</div>


<?php include 'footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Use full jQuery -->
<script src="./resources/popper.min.js"></script>
<script src="./resources/bootstrap.min.js"></script>



</body>
</html>
