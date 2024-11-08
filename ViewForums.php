<?php require 'session.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Forum Thread</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./resources/styles.css">
    <link rel="stylesheet" href="./resources/dashboard.css">
    <style>
        .comment, .reply { padding: 1px; margin-bottom: 2px; border-radius: 5px; background-color: #f9f9f9; }
        .comment p, .reply p { margin: 1px 0; }
        .comment small, .reply small { font-size: 0.8rem; color: #777; }
        .reply { margin-left: 50px; border-left: 3px solid #ddd; }
        .form-group textarea { padding: 6px; }
        .btn { padding: 4px 10px; font-size: 0.9rem; }
        hr { margin: 10px 0; }
        .reply-form { display: none; margin-top: 5px; }
        .comments-container { max-height: 600px; overflow-y: auto; margin-bottom: 20px; }
        .comment .replies { 
            /* max-height: 200px; overflow-y: auto;  */
            margin-top: 10px; }
        .reply-link { cursor: pointer; color: black; text-decoration: none; }
        .see-more { cursor: pointer; color: #007bff; text-decoration: underline; display: none; }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="content container mt-4">
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
                echo "<h1>" . htmlspecialchars($thread['title']) . "</h1>";
                echo $thread['image'] ? "<div><img src='" . htmlspecialchars($thread['image']) . "' alt='Image' width='300'></div>" : "";
                echo "<p><strong>Author:</strong> " . htmlspecialchars($thread['author_id']) . "</p>";
                echo "<p><strong>Created:</strong> " . $thread['created_at'] . "</p>";
                echo "<div>" . nl2br(htmlspecialchars($thread['content'])) . "</div>";
            }

            $stmt->close();
        }

        // Handling Comment Submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
            $comment_content = $_POST['comment'];
            $author_id = $_SESSION['user_id'];
            $insert_query = "INSERT INTO forum_comments (thread_id, comment_content, author_id, created_at) VALUES (?, ?, ?, NOW())";
            if ($insert_stmt = $conn->prepare($insert_query)) {
                $insert_stmt->bind_param("iss", $thread_id, $comment_content, $alumni_id);
                $insert_stmt->execute();
            }
        }

        // Handling Reply Submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reply'])) {
            $reply_content = $_POST['reply'];
            $comment_id = $_POST['comment_id'];
            $author_id = $_SESSION['user_id'];
            
            // Insert reply into the forum_comment_replies table
            $reply_query = "INSERT INTO forum_comment_replies (comment_id, reply_content, author_id, created_at) VALUES (?, ?, ?, NOW())";
            if ($reply_stmt = $conn->prepare($reply_query)) {
                $reply_stmt->bind_param("iss", $comment_id, $reply_content, $alumni_id);
                $reply_stmt->execute();
            }
        }

        $comments_query = "SELECT * FROM forum_comments WHERE thread_id = ? ORDER BY created_at DESC";
        if ($comments_stmt = $conn->prepare($comments_query)) {
            $comments_stmt->bind_param("i", $thread_id);
            $comments_stmt->execute();
            $comments_result = $comments_stmt->get_result();

     
            if (isset($_SESSION['user_id'])) {
                echo "<h3>Add a Comment</h3>";
                echo "<form method='POST' action=''>
                        <div class='form-group'>
                            <textarea name='comment' class='form-control' rows='3' required></textarea>
                        </div>
                        <button type='submit' class='btn btn-primary'>Post Comment</button>
                      </form>";
            } else {
                echo "<p>You must be logged in to post a comment.</p>";
            }
      
            
            echo "<div class='comments-container'>";
            while ($comment = $comments_result->fetch_assoc()) {
                echo "<div class='comment'>";
                echo "<p><strong>" . htmlspecialchars($comment['author_id']) . "</strong> <small>(" . $comment['created_at'] . ")</small></p>";
                echo "<p>" . nl2br(htmlspecialchars($comment['comment_content'])) . "</p>";
     // Reply Link and Form
     if (isset($_SESSION['user_id'])) {
        echo "<a href='#' class='reply-link btn btn-success' data-comment-id='" . $comment['id'] . "'>Reply</a>";
        echo "<form method='POST' action='' class='reply-form' id='reply-form-" . $comment['id'] . "'>";
        echo "<div class='form-group'>";
        echo "<textarea name='reply' class='form-control' rows='2' required></textarea>";
        echo "<input type='hidden' name='comment_id' value='" . $comment['id'] . "'>";
        echo "</div>";
        echo "<button type='submit' class='btn btn-secondary'>Post Reply</button>";
        echo "<button type='button' class='btn btn-link cancel-reply'>Cancel</button>";
        echo "</form>";
    }
                // Display Replies
                $replies_query = "SELECT * FROM forum_comment_replies WHERE comment_id = ? ORDER BY created_at DESC";
                if ($replies_stmt = $conn->prepare($replies_query)) {
                    $replies_stmt->bind_param("i", $comment['id']);
                    $replies_stmt->execute();
                    $replies_result = $replies_stmt->get_result();
                    $replies_count = 0;

                    echo "<div class='replies'>";
                    while ($reply = $replies_result->fetch_assoc()) {
                        $replies_count++;
                        echo "<div class='reply'>";
                        echo "<strong>" . htmlspecialchars($reply['author_id']) . "</strong> <small>(" . $reply['created_at'] . ")</small>";
                        echo "<p>" . nl2br(htmlspecialchars($reply['reply_content'])) . "</p>";
                        echo "</div>";
                    }
                    if ($replies_count > 2) {
                        echo "<span class='see-more' data-comment-id='" . $comment['id'] . "'>See more</span>";
                    }
                    echo "</div>";
                }

           
                echo "</div><hr>";
            }
            echo "</div>";
        }
    }
    ?>

  
</div>

<?php include 'footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Use full jQuery -->
<script src="./resources/popper.min.js"></script>
<script src="./resources/bootstrap.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Show reply form
        $('.reply-link').click(function(e) {
            e.preventDefault();
            var commentId = $(this).data('comment-id');
            $('#reply-form-' + commentId).slideDown();
        });

        // Cancel reply
        $('.cancel-reply').click(function() {
            $(this).closest('.reply-form').slideUp();
        });

        // Toggle "See more" replies
        $('.see-more').click(function() {
            var commentId = $(this).data('comment-id');
            $(this).siblings('.reply').show();
            $(this).hide();
        });
    });
</script>

</body>
</html>
