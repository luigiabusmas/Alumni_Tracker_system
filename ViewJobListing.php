<?php 
require 'session.php'; 

// Check if the job ID is provided
if (isset($_GET['id'])) {
    $job_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Fetch the job listing details from the database
    $sql = "SELECT * FROM job_listings WHERE id = '$job_id'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $job = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('Job listing not found.'); window.location.href = 'joblistings.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('No job ID provided.'); window.location.href = 'joblistings.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Job Listing Details</title>
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
        .job-section {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 30px;
        }
        .job-section h2 {
            color: #007bff;
        }
        .job-detail {
            margin-bottom: 15px;
        }
        .job-detail label {
            font-weight: bold;
            color: #555;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <div class="text-left mt-4">
        <a href="JobListing.php" class="btn btn-secondary">Back to Job Listings</a>
    </div>
    <div class="job-section">
        <!-- Job Title -->
        <h2><?php echo htmlspecialchars($job['title']); ?></h2>
        
        <!-- Job Details -->
        <div class="job-detail">
            <label>Company Name:</label>
            <p><?php echo htmlspecialchars($job['company_name']); ?></p>
        </div>
        <div class="job-detail">
            <label>Description:</label>
            <p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
        </div>
        <div class="job-detail">
            <label>Location:</label>
            <p><?php echo htmlspecialchars($job['location']); ?></p>
        </div>
        <div class="job-detail">
            <label>Job Type:</label>
            <p><?php echo htmlspecialchars($job['job_type']); ?></p>
        </div>
        <div class="job-detail">
            <label>Salary Range:</label>
            <p><?php echo htmlspecialchars($job['salary_range']); ?></p>
        </div>
        <div class="job-detail">
            <label>Experience Level:</label>
            <p><?php echo htmlspecialchars($job['experience_level']); ?></p>
        </div>
        <div class="job-detail">
            <label>Status:</label>
            <p><?php echo htmlspecialchars($job['status']); ?></p>
        </div>
        <div class="job-detail">
            <label>Start Date:</label>
            <p><?php echo !empty($job['start_date']) ? date('F d, Y', strtotime($job['start_date'])) : 'N/A'; ?></p>
        </div>
        <div class="job-detail">
            <label>End Date:</label>
            <p><?php echo !empty($job['end_date']) ? date('F d, Y', strtotime($job['end_date'])) : 'N/A'; ?></p>
        </div>
        <div class="job-detail">
            <label>Program:</label>
            <p><?php echo htmlspecialchars($job['program']); ?></p>
        </div>
        <div class="job-detail">
            <label>Courses:</label>
            <p><?php echo htmlspecialchars($job['courses']); ?></p>
        </div>
        <div class="job-detail">
            <label>Created At:</label>
            <p><?php echo date('F d, Y H:i', strtotime($job['created_at'])); ?></p>
        </div>
        <div class="job-detail">
            <label>Updated At:</label>
            <p><?php echo date('F d, Y H:i', strtotime($job['updated_at'])); ?></p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="./resources/popper.min.js"></script>
<script src="./resources/bootstrap.min.js"></script>

</body>
</html>
