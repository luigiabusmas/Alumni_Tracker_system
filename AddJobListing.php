<?php 
require 'session.php'; 

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input data
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $job_type = mysqli_real_escape_string($conn, $_POST['job_type']);
    $salary_range = mysqli_real_escape_string($conn, $_POST['salary_range']);
    $experience_level = mysqli_real_escape_string($conn, $_POST['experience_level']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $program = mysqli_real_escape_string($conn, $_POST['program']);
    $courses = mysqli_real_escape_string($conn, $_POST['courses']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $created_at = date('Y-m-d H:i:s');
    $updated_at = $created_at;

    // Insert data into the database
    $sql = "INSERT INTO job_listings (title, company_name, description, author_id, location, job_type, salary_range, experience_level, status, program, courses, start_date, end_date, created_at, updated_at) 
            VALUES ('$title', '$company_name', '$description', '$alumni_id', '$location', '$job_type', '$salary_range', '$experience_level', '$status', '$program', '$courses', '$start_date', '$end_date', '$created_at', '$updated_at')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('New job listing created successfully.');
                window.location.href = 'AddJobListing.php'; 
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Job Listing</title>
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

        h2 {
            color: #333;
        }

        .form-section {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
        }

        .form-section h3 {
            color: #007bff;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ced4da;
            padding: 10px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .text-center {
            text-align: center;
        }
        .dropdown-checkbox {
            position: relative;
            width: 100%;
        }

        .dropdown-checkbox select {
            display: none;
        }

        .dropdown-checkbox .select-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid #ced4da;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            background-color: #fff;
        }

        .dropdown-checkbox .options {
            display: none;
            position: absolute;
            width: 100%;
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: 5px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 10;
        }

        .dropdown-checkbox.active .options {
            display: block;
        }

        .dropdown-checkbox .option {
            padding: 8px 12px;
            cursor: pointer;
        }

        .dropdown-checkbox .option:hover {
            background-color: #f0f0f0;
        }

        .dropdown-checkbox input[type="checkbox"] {
            margin-right: 8px;
        }

        .dropdown-checkbox .selected-items {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .dropdown-checkbox .selected-items span {
            background-color: #007bff;
            color: #fff;
            border-radius: 3px;
            padding: 3px 8px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <h2>Create Job Listing</h2>
    <a href="JobListing.php" class="btn btn-secondary">Back</a>
    <form action="AddJobListing.php" method="POST">
        <div class="form-group">
            <label for="title">Job Title</label>
            <input type="text" id="title" name="title" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="company_name">Company Name</label>
            <input type="text" id="company_name" name="company_name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="description">Job Description</label>
            <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
        </div>


        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" id="location" name="location" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="job_type">Job Type</label>
            <select id="job_type" name="job_type" class="form-control">
                <option value="Full-Time">Full-Time</option>
                <option value="Part-Time">Part-Time</option>
                <option value="Contract">Contract</option>
                <option value="Temporary">Temporary</option>
                <option value="Internship">Internship</option>
                <option value="Freelance">Freelance</option>
            </select>
        </div>




        <div class="form-group">
            <label for="salary_range">Salary Range</label>
            <input type="text" id="salary_range" name="salary_range" class="form-control">
        </div>

        <div class="form-group">
            <label for="experience_level">Experience Level</label>
            <select id="experience_level" name="experience_level" class="form-control">
                <option value="Entry Level">Entry Level</option>
                <option value="Mid Level">Mid Level</option>
                <option value="Senior Level">Senior Level</option>
            </select>
        </div>

        <div class="form-group">
        <label for="program">Program</label>
        <input type="hidden" id="selectedPrograms" name="program">
        <div class="dropdown-checkbox" id="program-dropdown">
            <div class="select-box">Select Program <i class="fas fa-caret-down"></i></div>
            <div class="options">
                <div class="option"><input type="checkbox" value="Computer Science"> Computer Science</div>
                <div class="option"><input type="checkbox" value="Information Technology"> Information Technology</div>
                <div class="option"><input type="checkbox" value="Business Administration"> Business Administration</div>
                <div class="option"><input type="checkbox" value="Mechanical Engineering"> Mechanical Engineering</div>
                <div class="option"><input type="checkbox" value="Electrical Engineering"> Electrical Engineering</div>
                <div class="option"><input type="checkbox" value="Civil Engineering"> Civil Engineering</div>
                <div class="option"><input type="checkbox" value="Marketing"> Marketing</div>
                <div class="option"><input type="checkbox" value="Finance"> Finance</div>
                <div class="option"><input type="checkbox" value="Nursing"> Nursing</div>
                <div class="option"><input type="checkbox" value="Psychology"> Psychology</div>
                <div class="option"><input type="checkbox" value="Education"> Education</div>
            </div>
        </div>
    </div>

    <!-- Courses Field with Checkbox Dropdown -->
    <div class="form-group">
        <label for="courses">Relevant Courses</label>
        <input type="hidden" id="selectedCourses" name="courses">
        <div class="dropdown-checkbox" id="courses-dropdown">
            <div class="select-box">Select Courses <i class="fas fa-caret-down"></i></div>
            <div class="options">
                <div class="option"><input type="checkbox" value="Data Structures"> Data Structures</div>
                <div class="option"><input type="checkbox" value="Web Development"> Web Development</div>
                <div class="option"><input type="checkbox" value="Project Management"> Project Management</div>
                <div class="option"><input type="checkbox" value="Artificial Intelligence"> Artificial Intelligence</div>
                <div class="option"><input type="checkbox" value="Machine Learning"> Machine Learning</div>
                <div class="option"><input type="checkbox" value="Financial Accounting"> Financial Accounting</div>
                <div class="option"><input type="checkbox" value="Marketing Strategies"> Marketing Strategies</div>
                <div class="option"><input type="checkbox" value="Human Resources"> Human Resources</div>
                <div class="option"><input type="checkbox" value="Digital Marketing"> Digital Marketing</div>
                <div class="option"><input type="checkbox" value="Thermodynamics"> Thermodynamics</div>
                <div class="option"><input type="checkbox" value="Psychology of Learning"> Psychology of Learning</div>
            </div>
        </div>
    </div>


        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" class="form-control">
                <option value="Draft">Open</option>
                <option value="Published">Close</option>
                <option value="Closed">Draft</option>
            </select>
        </div>

        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="datetime-local" id="start_date" name="start_date" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="datetime-local" id="end_date" name="end_date" class="form-control">
        </div>






        <div class="text-center">
            <button type="submit" class="btn btn-primary">Create Job Listing</button>
            <a href="JobListing.php" class="btn btn-secondary">Cancel</a>
        </div>
        </div>
        </form>
</div>
</div>
<?php include 'footer.php'; ?>
<script>
function initDropdownCheckbox(id, hiddenInputId) {
    const dropdown = document.getElementById(id);
    const selectBox = dropdown.querySelector('.select-box');
    const options = dropdown.querySelectorAll('.option input[type="checkbox"]');
    const selectedItems = [];
    const hiddenInput = document.getElementById(hiddenInputId);

    selectBox.addEventListener('click', () => {
        dropdown.classList.toggle('active');
    });

    options.forEach(option => {
        option.addEventListener('change', () => {
            const value = option.value;
            if (option.checked) {
                selectedItems.push(value);
            } else {
                const index = selectedItems.indexOf(value);
                if (index > -1) selectedItems.splice(index, 1);
            }
            // Update the display text and the hidden input field
            selectBox.textContent = selectedItems.length ? selectedItems.join(', ') : 'Select Items';
            hiddenInput.value = selectedItems.join(','); // Assign the selected items to hidden input
        });
    });

    document.addEventListener('click', (e) => {
        if (!dropdown.contains(e.target)) dropdown.classList.remove('active');
    });
}

// Initialize the dropdowns with hidden input fields
initDropdownCheckbox('program-dropdown', 'selectedPrograms');
initDropdownCheckbox('courses-dropdown', 'selectedCourses');

</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Use full jQuery -->
<script src="./resources/popper.min.js"></script>
<script src="./resources/bootstrap.min.js"></script>

</body>
</html>
