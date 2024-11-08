<?php require 'session.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./resources/styles.css"> 
    <link rel="stylesheet" href="./resources/dashboard.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="content container mt-4" id="dashboard-content">
    <h1>Job Listings</h1>
    <a href="AddJobListing.php" class="btn btn-success">Add Job Listing</a>
    <p>Below are the latest job listings available in the system.</p>
    
    <table id="jobsTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Company Name:</th>
                <th>Job Title</th>
                <th>Job Type</th>
                <th>Experience Level</th>
                <th>Related Program</th>
                <th>Related Courses</th>
                <th>Author</th>
                <th>Status</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Connect to your database and fetch job listings
            $query = "SELECT * FROM `job_listings`";
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
                    echo "<td>" . $row['company_name'] . "</td>";
                    echo "<td>" . $row['title'] . "</td>";
                    echo "<td>" . $row['job_type'] . "</td>";
                    echo "<td>" . $row['experience_level'] . "</td>";
                    echo "<td>" . $row['program'] . "</td>";
                    echo "<td>" . $row['courses'] . "</td>";
                    echo "<td>" . ($row ? $row['author_id'] : 'Unknown') . "</td>"; // Display author's username
                    echo "<td>" . $row['status'] . "</td>";
               
   
                    echo "<td>" . $row['start_date'] . "</td>";
                    echo "<td>" . $row['end_date'] . "</td>";
                    echo "<td>
                        <a href='ViewJobListing.php?id=" . $row['id'] . "' class='btn btn-primary'>View</a>
                        <a href='EditJobListing.php?id=" . $row['id'] . "' class='btn btn-secondary'>Edit</a>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No job listings found.</td></tr>";
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
        $('#jobsTable').DataTable(); // Apply DataTables functionality
    });
</script>

</body>
</html>
