<?php 
require 'session.php'; 


// Check if the article ID is provided
if (isset($_GET['id'])) {
    $article_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Fetch the article details from the database
    $sql = "SELECT * FROM newsarticle WHERE id = '$article_id'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $article = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('Article not found.'); window.location.href = 'newslist.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('No article ID provided.'); window.location.href = 'newslist.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View News Article</title>
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
        .article-section {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 30px;
        }
        .article-section h2 {
            color: #007bff;
        }
        .article-detail {
            margin-bottom: 15px;
        }
        .article-detail label {
            font-weight: bold;
            color: #555;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
<div class="text-left mt-4">
            <a href="NewsArticle.php" class="btn btn-secondary">Back to Articles List</a>
        </div>
    <div class="article-section">
        
        <!-- Back Button -->

        <h2><?php echo htmlspecialchars($article['title']); ?></h2>
        
        <!-- Article Image -->
        <?php if (!empty($article['image'])): ?>
        <div class="text-center mb-4">
            <img src="image/<?php echo htmlspecialchars($article['image']); ?>" alt="Article Image" class="img-fluid" style="max-height: 300px;">
        </div>
        <?php endif; ?>

        <!-- Article Details -->
        <div class="article-detail">
            <label>Content:</label>
            <p><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>
        </div>
        <div class="article-detail">
            <label>Author ID:</label>
            <p><?php echo htmlspecialchars($article['author_id']); ?></p>
        </div>
        <div class="article-detail">
            <label>Published At:</label>
            <p><?php echo !empty($article['published_at']) ? date('F d, Y H:i', strtotime($article['published_at'])) : 'N/A'; ?></p>
        </div>
        <div class="article-detail">
            <label>Status:</label>
            <p><?php echo htmlspecialchars($article['status']); ?></p>
        </div>
        <div class="article-detail">
            <label>Start Date:</label>
            <p><?php echo !empty($article['start_date']) ? date('F d, Y H:i', strtotime($article['start_date'])) : 'N/A'; ?></p>
        </div>
        <div class="article-detail">
            <label>End Date:</label>
            <p><?php echo !empty($article['end_date']) ? date('F d, Y H:i', strtotime($article['end_date'])) : 'N/A'; ?></p>
        </div>
        <div class="article-detail">
            <label>Created At:</label>
            <p><?php echo date('F d, Y H:i', strtotime($article['created_at'])); ?></p>
        </div>
        <div class="article-detail">
            <label>Updated At:</label>
            <p><?php echo date('F d, Y H:i', strtotime($article['updated_at'])); ?></p>
        </div>

    </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="./resources/popper.min.js"></script>
<script src="./resources/bootstrap.min.js"></script>

</body>
</html>
