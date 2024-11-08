<?php require 'session.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum Threads Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./resources/styles.css"> 
    <link rel="stylesheet" href="./resources/dashboard.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="content container mt-4" id="dashboard-content">
    <h1>Forum Threads</h1>
    <a href="AddForum.php" class="btn btn-success">Add New Thread</a>
    <p>Below are the latest forum threads available in the system.</p>
    
    <table id="forumTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Content</th>
                <th>Image</th>
                <th>Author</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Connect to your database and fetch forum threads
            $query = "SELECT * FROM `forums`";
            $result = $conn->query($query);
            
            if ($result && $result->num_rows > 0) {
                // Loop through and display each record
                while ($row = $result->fetch_assoc()) {
                    // Fetch author's username based on author_id from the user_access table
                    $author_query = "SELECT username FROM users_access WHERE alumni_id = '".$row['author_id']."'";
                    $author_result = $conn->query($author_query);
                    $author = $author_result->fetch_assoc();
                    
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['title'] . "</td>";
                    echo "<td>" . $row['content'] . "</td>";
                    echo "<td>" . ($row['image'] ? "<img src='" . $row['image'] . "' alt='Thread Image' width='50'>" : 'No Image') . "</td>";
                    echo "<td>" . ($author ? $author['username'] : 'Unknown') . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "<td>" . $row['created_at'] . "</td>";
                    echo "<td>" . $row['updated_at'] . "</td>";
                    echo "<td>
                        <a href='ViewForums.php?id=" . $row['id'] . "' class='btn btn-primary'>View</a>
                        <a href='EditForums.php?id=" . $row['id'] . "' class='btn btn-secondary'>Edit</a>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No forum threads found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="./resources/popper.min.js"></script>
<script src="./resources/bootstrap.min.js"></script>

<!-- DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#forumTable').DataTable(); // Apply DataTables functionality
    });
</script>

</body>
</html>
