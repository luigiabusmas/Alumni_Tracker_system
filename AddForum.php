<?php 
require 'session.php'; 

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input data
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $author_id = mysqli_real_escape_string($conn, $_POST['author_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $created_at = date('Y-m-d H:i:s');
    $updated_at = $created_at;

    // Image upload handling
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        $image_new_name = uniqid() . '.' . $image_extension;

        // Check for valid image types (optional, add more if necessary)
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($image_extension), $allowed_extensions)) {
            // Move the uploaded image to the 'image' folder
            $image_upload_path = 'image/' . $image_new_name;
            if (move_uploaded_file($image_tmp_name, $image_upload_path)) {
                $image = $image_new_name; // Save only the file name, not the full path
            } else {
                echo "<script>alert('Error uploading image.');</script>";
            }
        } else {
            echo "<script>alert('Invalid image format. Only jpg, jpeg, png, and gif are allowed.');</script>";
        }
    }

    // Insert data into the forums table
    $sql = "INSERT INTO forums (title, content, author_id, status, image, created_at, updated_at) 
            VALUES ('$title', '$content', '$alumni_id', '$status', '$image', '$created_at', '$updated_at')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
        alert('New forum post created successfully.');
        window.location.href = 'AddForum.php'; // Redirect to clear form after submission
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
    <title>Create Forum Post</title>
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
<form action="AddForum.php" method="POST" enctype="multipart/form-data">
    <div class="container">
        <h2>Create Forum Post</h2>

        <div class="form-section">
            <h3>Post Details</h3>
            <!-- Title Input -->
            <div class="form-group">
                <label for="title">Post Title:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <!-- Content Textarea -->
            <div class="form-group">
                <label for="content">Post Content:</label>
                <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
            </div>

            <!-- Author ID Input (hidden) -->
            <input type="hidden" name="author_id" value="<?php echo $user_id; ?>"> <!-- Replace with dynamic value from session -->

            <!-- Status Dropdown -->
            <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status">
                    <option value="Open">Open</option>
                    <option value="Closed">Closed</option>
                    <option value="Archived">Archived</option>
                </select>
            </div>

            <!-- Image Upload -->
            <div class="form-group">
                <label for="image">Upload Image:</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
            </div>
        </div>

        <!-- Hidden fields for timestamps -->
        <input type="hidden" name="created_at" value="<?php echo date('Y-m-d H:i:s'); ?>">
        <input type="hidden" name="updated_at" value="<?php echo date('Y-m-d H:i:s'); ?>">

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Create Post</button>
            <a href="Forum.php" class="btn btn-secondary">Cancel</a>
        </div>
    </div>
</form>
</div>

<?php include 'footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Use full jQuery -->
<script src="./resources/popper.min.js"></script>
<script src="./resources/bootstrap.min.js"></script>

</body>
</html>
