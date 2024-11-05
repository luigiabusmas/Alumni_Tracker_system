<?php  
session_start();
require 'database.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to the login page if not logged in
    exit();
}
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
    echo "<script>
            alert('$message');
            window.location.href = 'AlumniProfiles.php';
          </script>";
    exit; // Prevent further script execution
}
// Fetch user information from the database
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Prepare and execute the statement to fetch alumni_id and is_admin
$stmt = $conn->prepare("SELECT alumni_id, is_admin FROM users_access WHERE id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($alumni_id, $is_admin);
$stmt->fetch();
$stmt->close();

// Fetch records from the alumni_profile table using alumni_id
$profile_stmt = $conn->prepare("SELECT * FROM alumni_profile_table WHERE alumni_id = ?");
$profile_stmt->bind_param("s", $alumni_id);
$profile_stmt->execute();
$result = $profile_stmt->get_result();

if ($result->num_rows > 0) {
    $profile = $result->fetch_assoc(); // Fetch the profile data
} else {
    echo "No profile found for this student ID.";
    exit();
}
$profile_stmt->close();

// Handle search query and pagination
$search_query = isset($_POST['search']) ? $_POST['search'] : '';
$like_query = "%" . $search_query . "%";
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 10;
$offset = ($page - 1) * $records_per_page;

// Count total results for pagination
$count_stmt = $conn->prepare("SELECT COUNT(*) FROM alumni_profile_table WHERE alumni_id LIKE ? OR username LIKE ? OR email LIKE ? OR fname LIKE ? OR lname LIKE ?");
$count_stmt->bind_param("sssss", $like_query, $like_query, $like_query, $like_query, $like_query);
$count_stmt->execute();
$count_stmt->bind_result($total_records);
$count_stmt->fetch();
$count_stmt->close();

$total_pages = ceil($total_records / $records_per_page);

// Fetch paginated results
$search_stmt = $conn->prepare("SELECT * FROM alumni_profile_table WHERE alumni_id LIKE ? OR username LIKE ? OR email LIKE ? OR fname LIKE ? OR lname LIKE ? LIMIT ?, ?");
$search_stmt->bind_param("sssssss", $like_query, $like_query, $like_query, $like_query, $like_query, $offset, $records_per_page);
$search_stmt->execute();
$result_all = $search_stmt->get_result();

$profiles = []; // Array to hold profiles
while ($row = $result_all->fetch_assoc()) {
    $profiles[] = $row;
}
$search_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Profiles</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./resources/styles.css"> 
    <link rel="stylesheet" href="./resources/dashboard.css"> 
    <style>
        /* Custom styles */
        .table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        .table th, .table td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .table th {
            background-color: #f8f9fa;
            color: #343a40;
        }
        .table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .table tr:hover {
            background-color: #e9ecef;
            cursor: pointer;
        }
        .table img {
            width: 50px;
            height: auto;
            border-radius: 5px;
        }
        .content {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }
        h2 {
            margin-bottom: 20px;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }
        .pagination a {
            color: #343a40;
            padding: 8px 12px;
            text-decoration: none;
            margin: 0 5px;
            border-radius: 5px;
        }
        .pagination a:hover {
            background-color: #e9ecef;
        }
        .pagination .active {
            font-weight: bold;
            background-color: #343a40;
            color: #ffffff;
        }
        .search-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .search-container input[type="text"] {
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px 0 0 4px;
        }
        .search-container button {
            padding: 8px 12px;
            font-size: 14px;
            color: #ffffff;
            background-color: #343a40;
            border: none;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
        }
        .search-container button:hover {
            background-color: #495057;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="content container" id="dashboard-content">
    <h2>Alumni Profiles List</h2>

    <!-- Search Form -->
    <div class="search-container">
        <form method="POST" action="">
            <input type="text" name="search" placeholder="Search by Alumni id, Username, Email, First Name, or Last Name" value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit"><i class="fas fa-search"></i> Search</button>
        </form>
    </div>

    <table class="table table-bordered">
    <div class="form-section d-flex justify-content-between">
    <div class="text-left">
        <a href="AddAlumniProfile.php" class="btn btn-success">Add Alumni Profile</a>
      
   </div>

        <thead>
            <tr>
                <th>Alumni ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>KLD Email</th>
                <th>Primary Phone</th>
                <th>Gender</th>
                <th>Graduation Year</th>
                <th>Employment Status</th>
                <th>Company Name</th>
                <th>Profile Picture</th>
                <th>Action</th> <!-- New Action Column -->
            </tr>
        </thead>
        <tbody>
            <?php if (count($profiles) > 0): ?>
                <?php foreach ($profiles as $profile): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($profile['alumni_id']); ?></td>
                        <td><?php echo htmlspecialchars($profile['username']); ?></td>
                        <td><?php echo htmlspecialchars($profile['email']); ?></td>
                        <td><?php echo htmlspecialchars($profile['fname']); ?></td>
                        <td><?php echo htmlspecialchars($profile['lname']); ?></td>
                        <td><?php echo htmlspecialchars($profile['kld_email']); ?></td>
                        <td><?php echo htmlspecialchars($profile['primary_phone']); ?></td>
                        <td><?php echo htmlspecialchars($profile['gender']); ?></td>
                        <td><?php echo htmlspecialchars($profile['graduation_year']); ?></td>
                        <td><?php echo htmlspecialchars($profile['employment_status']); ?></td>
                        <td><?php echo htmlspecialchars($profile['company_name']); ?></td>
                        <td><img src="image/<?php echo htmlspecialchars($profile['profile_picture']); ?>" alt="Profile Picture"></td>
                        <td>
    <a href="EditAlumniProfile.php?id=<?php echo urlencode($profile['id']); ?>" class="btn btn-primary">View</a>
    <!-- <a href="DeleteAlumniProfile.php?id=<?php echo urlencode($profile['id']); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this profile?');">Delete</a> -->
</td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="13">No profiles found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" class="<?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="./resources/popper.min.js"></script>
<script src="./resources/bootstrap.min.js"></script>

</body>
</html>
