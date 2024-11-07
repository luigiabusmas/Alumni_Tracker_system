<?php require 'session.php';  ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./resources/styles.css"> 
    <link rel="stylesheet" href="./resources/dashboard.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">

</head>
<body>

<?php include 'header.php'; ?>

<div class="content container mt-4" id="dashboard-content">
    <h1>News Articles</h1>
    <a href="AddNewsArticle.php" class="btn btn-success">Add News & Articles</a>
    <p>Below are the latest news articles in the system.</p>
    
    <table id="newsTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Status</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Published At</th>
                <th>Image</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Connect to your database and fetch NewsArticle records
            
            $query = "SELECT * FROM `NewsArticle`";
            $result = $conn->query($query);
            
            if ($result->num_rows > 0) {
                // Loop through and display each record
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['title'] . "</td>";
                    echo "<td>" . $row['author_id'] . "</td>"; // Replace with author's name from your alumni table if needed
                    echo "<td>" . $row['status'] . "</td>";
                    echo "<td>" . $row['start_date'] . "</td>";
                    echo "<td>" . $row['end_date'] . "</td>";
                    echo "<td>" . $row['published_at'] . "</td>";
                    echo "<td><img src='image/" . $row['image'] . "' alt='Article Image' width='100'></td>";
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Use full jQuery -->
<script src="./resources/popper.min.js"></script>
<script src="./resources/bootstrap.min.js"></script>

<!-- DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#newsTable').DataTable(); // Apply DataTables functionality
    });
</script>

</body>
</html>
