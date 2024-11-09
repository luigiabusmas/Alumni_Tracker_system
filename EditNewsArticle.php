<?php 
require 'session.php'; 

// Check if the article ID is provided
if (isset($_GET['id'])) {
    $article_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Fetch the existing article details from the database
    $sql = "SELECT * FROM newsarticle WHERE id = '$article_id'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $article = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('Article not found.'); window.location.href = 'NewsArticle.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('No article ID provided.'); window.location.href = 'NewsArticle.php';</script>";

}

// Handle form submission for updating the article
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $author_id = mysqli_real_escape_string($conn, $_POST['author_id']);
    $published_at = mysqli_real_escape_string($conn, $_POST['published_at']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $updated_at = date('Y-m-d H:i:s');

    // Validate start_date and end_date
    $today = date('Y-m-d\TH:i'); // Current date in 'YYYY-MM-DDTHH:MM' format
    if ($start_date < $today) {
     
        echo "<script>
        alert('Start Date cannot be in the past. Please select today or a future date.');
        window.location.href = 'EditNewsArticle.php?id=$article_id';
      </script>";
      return;
    }
    if ($end_date <= $start_date) {

        echo "<script>
        alert('End Date must be greater than Start Date.');
        window.location.href = 'EditNewsArticle.php?id=$article_id';
      </script>";
      return;
    }

    // Image upload handling
    $image = $article['image']; // Retain old image if not updated
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

    // Update the article in the database
    $update_sql = "UPDATE newsarticle SET 
                    title = '$title', 
                    content = '$content', 
                    author_id = '$author_id', 
                    published_at = '$published_at', 
                    status = '$status', 
                    start_date = '$start_date', 
                    end_date = '$end_date', 
                    image = '$image', 
                    updated_at = '$updated_at' 
                   WHERE id = '$article_id'";

    if (mysqli_query($conn, $update_sql)) {
        echo "<script>
                alert('Article updated successfully.');
                window.location.href = 'EditNewsArticle.php?id=$article_id';
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
    <title>Edit News Article</title>
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
            <a href="NewsArticle.php" class="btn btn-success">Back to list</a>
        </div>

        <div class="text-right">
            <a href="DeleteNewsArticle.php?id=<?php echo htmlspecialchars($article['id']); ?>"  
            class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this article? This action cannot be undone.');">Delete</a>
        </div>
    </div>

    <div class="form-section">
        <h2>Edit News Article</h2>
        <form action="EditNewsArticle.php?id=<?php echo $article_id; ?>" method="POST" enctype="multipart/form-data">
            <!-- Image Upload -->
            <div class="form-group">
                <?php if (!empty($article['image'])): ?>
                    <p>Current Image: <img src="image/<?php echo $article['image']; ?>" alt="Current Image" style="max-height: 100px;"></p>
                <?php endif; ?>
                <label for="image">Upload New Image:</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
            </div>

            <!-- Title Input -->
            <div class="form-group">
                <label for="title">Article Title:</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($article['title']); ?>" required>
            </div>

            <!-- Content Textarea -->
            <div class="form-group">
                <label for="content">Article Content:</label>
                <textarea class="form-control" id="content" name="content" rows="5" required><?php echo htmlspecialchars($article['content']); ?></textarea>
            </div>

            <!-- Author ID Input -->
            <div class="form-group">
                <label for="author_id">Author ID:</label>
                <input type="hidden" class="form-control" id="author_id" name="author_id" value="<?php echo htmlspecialchars($article['author_id']); ?>" readonly>
            </div>

            <!-- Published Date Input -->
            <div class="form-group">
                <label for="published_at">Published Date:</label>
                <input type="hidden" class="form-control" id="published_at" name="published_at" value="<?php echo date('Y-m-d\TH:i', strtotime($article['published_at'])); ?>">
            </div>

            <!-- Status Dropdown -->
            <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status">
                    <option value="Draft" <?php echo $article['status'] == 'Draft' ? 'selected' : ''; ?>>Draft</option>
                    <option value="Published" <?php echo $article['status'] == 'Published' ? 'selected' : ''; ?>>Published</option>
                    <option value="Archived" <?php echo $article['status'] == 'Archived' ? 'selected' : ''; ?>>Archived</option>
                </select>
            </div>

            <!-- Start Date Input -->
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="datetime-local" class="form-control" id="start_date" name="start_date" value="<?php echo date('Y-m-d\TH:i', strtotime($article['start_date'])); ?>" required>
            </div>

            <!-- End Date Input -->
            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="datetime-local" class="form-control" id="end_date" name="end_date" value="<?php echo date('Y-m-d\TH:i', strtotime($article['end_date'])); ?>" required>
            </div>

            <!-- Submit Button -->
            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary">Update Article</button>
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="./resources/popper.min.js"></script>
<script src="./resources/bootstrap.min.js"></script>

</body>
</html>
