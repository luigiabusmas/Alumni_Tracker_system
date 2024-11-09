<?php require 'session.php'; ?>
<?php
    if (isset($_GET['id'])) {
        $thread_id = $_GET['id'];
        $query = "SELECT * FROM forums WHERE id = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("i", $thread_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $thread = $result->fetch_assoc();
                // echo "<h1>Edit Forum Thread</h1>";

                // Display the edit form with current thread data
                ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Forum Thread</title>
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
        .btn {
            padding: 8px 15px;
            font-size: 0.9rem;
        }
        hr {
            margin: 20px 0;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>


<div class="container">
    <div class="form-section d-flex justify-content-between">
        <div class="text-left">
            <a href="Forums.php" class="btn btn-success">Back to list</a>
        </div>
        <div class="text-right">
            <a href="DeleteForums.php?id=<?php echo htmlspecialchars($thread['id']); ?>"  
               class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this Thread? This action cannot be undone.');">Delete</a>
        </div>
    </div>

    <div class="form-section">

        <form method="POST" action="" enctype="multipart/form-data">
            <!-- Image Upload -->
            <div class="form-group">
                <label for="image">Image:</label>
                <p>Current Image: <img src="image/<?php echo htmlspecialchars($thread['image']); ?>" alt="Forum Image" style="max-height: 100px;"></p>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <!-- Title Input -->
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($thread['title']); ?>" required>
            </div>

            <!-- Content Textarea -->
            <div class="form-group">
                <label for="content">Content:</label>
                <textarea name="content" class="form-control" rows="5" required><?php echo htmlspecialchars($thread['content']); ?></textarea>
            </div>

            <!-- Submit Button -->
            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
                <?php

                // Handling form submission
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $updated_title = $_POST['title'];
                    $updated_content = $_POST['content'];

                    // Check if an image was uploaded, else retain the existing image
                    $image = $thread['image']; // Retain the old image if no new one is uploaded
                    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                        $image_tmp_name = $_FILES['image']['tmp_name'];
                        $image_name = basename($_FILES['image']['name']); // Get the file name without path
                        $image_path = "image/" . $image_name;

                        // Move the uploaded file to the desired directory
                        if (move_uploaded_file($image_tmp_name, $image_path)) {
                            $image = $image_name; // Update with new image name
                        }
                    }

                    // Update the forum thread in the database
                    $update_query = "UPDATE forums SET title = ?, content = ?, updated_at = NOW(), image = ? WHERE id = ?";
                    if ($update_stmt = $conn->prepare($update_query)) {
                        $update_stmt->bind_param("sssi", $updated_title, $updated_content, $image, $thread_id);
                        $update_stmt->execute();
                        echo "<script>alert('Forum updated successfully.');window.location.href = 'EditForums.php?id=$thread_id';</script>";
                    } else {
                        // Display error message as alert
                        echo "<script>alert('Forum update failed.');</script>";
                    }
                }

            }
            $stmt->close();
        }
    }
    ?>
</div>

<?php include 'footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Use full jQuery -->
<script src="./resources/popper.min.js"></script>
<script src="./resources/bootstrap.min.js"></script>

</body>
</html>
