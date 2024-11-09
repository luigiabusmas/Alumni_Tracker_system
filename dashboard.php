<?php require 'session.php'; ?>

<?php
// Fetch data for statistics

// Fetch degree data (number of users per degree obtained)
function fetch_degree_data() {
    global $conn; // Assuming $conn is your database connection

    $query = "SELECT degree_obtained, COUNT(*) as count FROM alumni_profile_table GROUP BY degree_obtained";
    $result = mysqli_query($conn, $query);
    
    $degree_data = [];
    $labels = [];
    $counts = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $labels[] = $row['degree_obtained'];
        $counts[] = $row['count'];
    }

    $degree_data = [
        'labels' => $labels,
        'counts' => $counts
    ];

    return $degree_data;
}

// Fetch graduation year data (number of users per graduation year)
function fetch_graduation_year_data() {
    global $conn; // Assuming $conn is your database connection

    $query = "SELECT graduation_year, COUNT(*) as count FROM alumni_profile_table GROUP BY graduation_year";
    $result = mysqli_query($conn, $query);
    
    $graduation_year_data = [];
    $years = [];
    $counts = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $years[] = $row['graduation_year'];
        $counts[] = $row['count'];
    }

    $graduation_year_data = [
        'years' => $years,
        'counts' => $counts
    ];

    return $graduation_year_data;
}

// Fetch employment status data per graduation year
function fetch_employment_status_data() {
    global $conn; // Assuming $conn is your database connection

    $query = "SELECT graduation_year, employment_status, COUNT(*) as count 
              FROM alumni_profile_table 
              GROUP BY graduation_year, employment_status";
    $result = mysqli_query($conn, $query);
    
    $employment_status_data = [];
    $years = [];
    $employed = [];
    $unemployed = [];
    $pursuedStudies = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $years[] = $row['graduation_year'];

        if ($row['employment_status'] == 'Employed') {
            $employed[$row['graduation_year']] = $row['count'];
        } elseif ($row['employment_status'] == 'Unemployed') {
            $unemployed[$row['graduation_year']] = $row['count'];
        } elseif ($row['employment_status'] == 'Pursued Studies') {
            $pursuedStudies[$row['graduation_year']] = $row['count'];
        }
    }

    $employment_status_data = [
        'years' => $years,
        'employed' => $employed,
        'unemployed' => $unemployed,
        'pursuedStudies' => $pursuedStudies
    ];

    return $employment_status_data;
}

// Fetch feedback data (average ratings for functionality, usability, and maintainability)
function fetch_feedback_data() {
    global $conn; // Assuming $conn is your database connection

    $feedback_data = [];

    // Fetch average feedback for Functionality
    $query = "SELECT AVG(task_efficiency) as avg_task_efficiency,
                     AVG(ui_satisfaction) as avg_ui_satisfaction,
                     AVG(system_stability) as avg_system_stability,
                     AVG(maintainability_troubleshooting) as avg_maintainability_troubleshooting
              FROM feedback";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $feedback_data['ratings'] = [
            round($row['avg_task_efficiency'], 2),
            round($row['avg_ui_satisfaction'], 2),
            round($row['avg_system_stability'], 2),
            round($row['avg_maintainability_troubleshooting'], 2)
        ];
    }

    return $feedback_data;
}

// Get the data for all statistics
$degree_data = fetch_degree_data();
$graduation_year_data = fetch_graduation_year_data();
$employment_status_data = fetch_employment_status_data();
$feedback_data = fetch_feedback_data();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./resources/styles.css"> 
    <link rel="stylesheet" href="./resources/dashboard.css"> 
</head>
<body>

<?php include 'header.php'; ?>

<div class="content container mt-4" id="dashboard-content">
    <h1>Welcome to the Alumni Tracker Dashboard</h1>
    <p>Select a menu item to view its content.</p>

    <!-- Charts Section -->
    <div class="row">
        <div class="col-md-6">
            <h3>Number of Users (Degree Obtained)</h3>
            <canvas id="degreeChart"></canvas>
        </div>
        <div class="col-md-6">
            <h3>Number of Users (Graduation Year)</h3>
            <canvas id="graduationYearChart"></canvas>
        </div>
        <div class="col-md-6">
            <h3>Employment Status (Yearly)</h3>
            <canvas id="employmentStatusChart"></canvas>
        </div>
        <div class="col-md-6">
            <h3>Feedback Ratings</h3>
            <canvas id="feedbackChart"></canvas>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="./resources/popper.min.js"></script>
<script src="./resources/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Degree Obtained Chart
    const degreeData = <?php echo json_encode($degree_data); ?>;
    const degreeCtx = document.getElementById('degreeChart').getContext('2d');
    new Chart(degreeCtx, {
        type: 'bar',
        data: {
            labels: degreeData.labels,
            datasets: [{
                label: 'Number of Users',
                data: degreeData.counts,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Graduation Year Chart
    const graduationYearData = <?php echo json_encode($graduation_year_data); ?>;
    const graduationYearCtx = document.getElementById('graduationYearChart').getContext('2d');
    new Chart(graduationYearCtx, {
        type: 'line',
        data: {
            labels: graduationYearData.years,
            datasets: [{
                label: 'Number of Users',
                data: graduationYearData.counts,
                fill: false,
                borderColor: 'rgba(75, 192, 192, 1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Employment Status Chart
    const employmentStatusData = <?php echo json_encode($employment_status_data); ?>;
    const employmentStatusCtx = document.getElementById('employmentStatusChart').getContext('2d');
    new Chart(employmentStatusCtx, {
        type: 'bar',
        data: {
            labels: employmentStatusData.years,
            datasets: [
                {
                    label: 'Employed',
                    data: employmentStatusData.employed,
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Unemployed',
                    data: employmentStatusData.unemployed,
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Pursued Studies',
                    data: employmentStatusData.pursuedStudies,
                    backgroundColor: 'rgba(153, 102, 255, 0.5)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Feedback Ratings Chart
    const feedbackData = <?php echo json_encode($feedback_data); ?>;
    const feedbackCtx = document.getElementById('feedbackChart').getContext('2d');
    new Chart(feedbackCtx, {
        type: 'radar',
        data: {
            labels: ['Functionality', 'Usability', 'System Stability', 'Maintainability'],
            datasets: [{
                label: 'Average Rating (1 - 5)',
                data: feedbackData.ratings,
                fill: true,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scale: {
                ticks: {
                    beginAtZero: true,
                    max: 5
                }
            }
        }
    });
</script>

</body>
</html>
