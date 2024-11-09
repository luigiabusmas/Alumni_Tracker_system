<?php require 'session.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Profiles Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./resources/styles.css"> 
    <link rel="stylesheet" href="./resources/dashboard.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="content container mt-4" id="dashboard-content">
    <h1>Alumni Profiles</h1>
    <a href="AddAlumniProfile.php" class="btn btn-success mb-3">Add Alumni</a>
    <p>Below are the registered alumni profiles in the system.</p>
    
    <table id="alumniTable" class="display">
        <thead>
            <tr>
                <th>Alumni ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>KLD Email</th>
                <th>Graduation Year</th>
                <th>Degree obtained</th>

                <th>Job Title</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch alumni profiles from the database
            $query = "SELECT id, CONCAT(fname, ' ', IFNULL(mname, ''), ' ', lname) AS full_name, 
                             email, graduation_year, alumni_id ,degree_obtained, kld_email, employment_status 
                      FROM alumni_profile_table";
            
            if ($stmt = $conn->prepare($query)) {
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['alumni_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['kld_email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['graduation_year']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['degree_obtained']) . "</td>";
    
                        echo "<td>" . htmlspecialchars($row['employment_status']) . "</td>";
                        echo "<td>
                            <a href='EditAlumniProfile.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-primary'>View</a>
           
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No alumni profiles found.</td></tr>";
                }
                $stmt->close();
            } else {
                echo "Error: " . $conn->error;
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
<script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#alumniTable').DataTable({
            "responsive": true,
            "searching": true,
            "paging": true,
            "info": true
        });
    });
</script>

</body>
</html>
