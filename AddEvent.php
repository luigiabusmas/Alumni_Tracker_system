<?php 
require 'session.php'; 

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input data
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    // $author_id = mysqli_real_escape_string($conn, $_POST['author_id']); // Assuming the author is selected from a user
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $event_type = mysqli_real_escape_string($conn, $_POST['event_type']);
    $created_at = date('Y-m-d H:i:s');
    $updated_at = $created_at;

    // Image upload handling
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        $image_new_name = uniqid() . '.' . $image_extension;

        // Allow all file types for the image
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'docx', 'xlsx', 'txt', 'zip', 'rar', 'ppt', 'pptx', 'mp4', 'avi'];

        if (in_array(strtolower($image_extension), $allowed_extensions)) {
            // Move the uploaded image to the 'image' folder
            $image_upload_path = 'image/' . $image_new_name;
            if (move_uploaded_file($image_tmp_name, $image_upload_path)) {
                $image = $image_new_name; // Save only the file name, not the full path
            } else {
                echo "<script>alert('Error uploading image.');</script>";
            }
        } else {
            echo "<script>alert('Invalid file format. Only image, document, and video files are allowed.');</script>";
        }
    }


    // Insert data into the database
    $sql = "INSERT INTO events (title, description, author_id, status, start_date, end_date, location, event_type, image, created_at, updated_at) 
            VALUES ('$title', '$description', '$alumni_id', '$status', '$start_date', '$end_date', '$location', '$event_type', '$image', '$created_at', '$updated_at')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
        alert('New event created successfully.');
        window.location.href = 'AddEvent.php'; // Redirect to clear form after submission
      </script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
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

        h2 {
            color: #333;
        }

        .form-section {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
        }

        .form-section h3 {
            color: #007bff;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ced4da;
            padding: 10px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container mt-5">
<form action="AddEvent.php" method="POST" enctype="multipart/form-data">
    <div class="container">
      

        <div class="form-section">
        <div class="text-center">
        <h2>Create Event</h2>
        </div>
            <!-- Title Input -->
            <div class="form-group">
                <label for="title">Event Title:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <!-- Description Textarea -->
            <div class="form-group">
                <label for="description">Event Description:</label>
                <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
            </div>

            <!-- Status Dropdown -->
            <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="Draft" selected>Draft</option>
                    <option value="Published">Published</option>
                    <option value="Archived">Archived</option>
                </select>
            </div>

            <!-- Start Date Input -->
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="datetime-local" class="form-control" id="start_date" name="start_date" required>
            </div>

            <!-- End Date Input -->
            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="datetime-local" class="form-control" id="end_date" name="end_date" required>
            </div>

            <!-- Location Input -->
            <div class="form-group">
                <label for="location">Event Location:</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>

            <!-- Event Type Input -->
            <div class="form-group">
                <label for="event-type">Event Type:</label>
                <select name="event_type" id="event-type" class="custom-select" required>
                    <option value="">Select Event Type</option>
                    <option value="conference">Conference</option>
                    <option value="seminar">Seminar</option>
                    <option value="workshop">Workshop</option>
                    <option value="webinar">Webinar</option>
                    <option value="meeting">Meeting</option>
                    <option value="networking">Networking</option>
                    <option value="party">Party</option>
                    <option value="conference_call">Conference Call</option>
                    <option value="exhibition">Exhibition</option>
                    <option value="show">Show</option>
                    <option value="presentation">Presentation</option>
                </select>
            </div>

            <!-- Image Upload -->
            <div class="form-group">
                <label for="image">Upload Image:</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
            </div>

            <!-- Hidden fields for timestamps -->
            <input type="hidden" name="created_at" value="<?php echo date('Y-m-d H:i:s'); ?>">
            <input type="hidden" name="updated_at" value="<?php echo date('Y-m-d H:i:s'); ?>">

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Create Event</button>
                <a href="Events.php" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </div>
</form>

</div>
</div>
<?php include 'footer.php'; ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get the current date for validation
        const today = new Date().toISOString().slice(0, 16); // Format: YYYY-MM-DDTHH:MM

        // Set the minimum allowed value for the start date
        const startDateInput = document.getElementById('start_date');
        startDateInput.setAttribute('min', today);

        // Add an event listener for start_date change to update end_date validation
        startDateInput.addEventListener('change', function () {
            validateEndDate();
        });

        // Function to validate end_date
        function validateEndDate() {
            const startDate = new Date(startDateInput.value);
            const endDateInput = document.getElementById('end_date');
            const endDate = new Date(endDateInput.value);

            // If end date is before start date, show an alert and reset
            if (endDate <= startDate) {
                alert('End date must be greater than start date.');
                endDateInput.value = ''; // Clear the end date input
            }
        }

        // Add an event listener for end_date change to validate
        const endDateInput = document.getElementById('end_date');
        endDateInput.addEventListener('change', validateEndDate);
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Use full jQuery -->
<script src="./resources/popper.min.js"></script>
<script src="./resources/bootstrap.min.js"></script>

</body>
</html>
