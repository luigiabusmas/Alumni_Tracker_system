<?php 
require 'session.php'; 





// Load Composer's autoloader
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to generate a random password
function generateRandomPassword($length = 8) {
    return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length);
}

// Check if the user is verified before sending the email
function sendVerificationEmail($user, $plainPassword) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'alumnitracker2024@gmail.com';
        $mail->Password   = 'czeu ishy akdn eeik';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('alumnitracker2024@gmail.com', 'KLD Alumni Tracker');
        $mail->addAddress($user['email'], $user['username']);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'KLD Alumni Tracker system access request granted';
        $mail->Body = ' 
        Congratulations! Your account has been verified.<br>
        Your username is: ' . $user['username'] . '<br>
        Password is: ' . $plainPassword . '<br>
        Access the link to login: www.google.com';
        
        $mail->AltBody = 'Congratulations! Your account has been verified.';

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Function to verify user access
function verifyUserAccess($conn, $userId) {
    // Generate a random password and hash it
    $plainPassword = generateRandomPassword();
    $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

    // Update the user's verification status and set the new password
    $stmt = $conn->prepare("UPDATE users_access SET is_verified = 1,is_active=1, userpassword = ? WHERE id = ?");
    $stmt->bind_param("si", $hashedPassword, $userId);

    if ($stmt->execute()) {
        // Fetch user data after verification update
        $userStmt = $conn->prepare("SELECT * FROM users_access WHERE id = ?");
        $userStmt->bind_param("i", $userId);
        $userStmt->execute();
        $user = $userStmt->get_result()->fetch_assoc();

        // Send email with the plain password if the user is now verified
        if ($user && $user['is_verified'] == 1) {
            sendVerificationEmail($user, $plainPassword);
        }
        return true;
    }
    return false;
}










// Function to unverify user access
function unverifyUserAccess($conn, $userId) {
    $stmt = $conn->prepare("UPDATE users_access SET is_verified = 0 WHERE id = ?");
    $stmt->bind_param("i", $userId);
    return $stmt->execute();
}

// Function to delete user access
function deleteUser($conn, $userId) {
    $stmt = $conn->prepare("DELETE FROM users_access WHERE id = ?");
    $stmt->bind_param("i", $userId);
    return $stmt->execute();
}

// Function to add user by alumni_id
// Function to add user by alumni_id
function addUserByAlumniId($conn, $alumniId) {
    // Check if alumni_id exists in alumni_profile_table
    $stmt = $conn->prepare("SELECT * FROM alumni_profile_table WHERE alumni_id = ?");
    $stmt->bind_param("s", $alumniId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Check if alumni_id already exists in users_access table
        $checkStmt = $conn->prepare("SELECT * FROM users_access WHERE alumni_id = ?");
        $checkStmt->bind_param("s", $alumniId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            $_SESSION['notification'] = "This Alumni ID has already been added.";
        } else {
            $alumniData = $result->fetch_assoc();

            // Prepare to insert into users_access
            $insertStmt = $conn->prepare("INSERT INTO users_access (alumni_id, username, email, is_verified, is_active) VALUES (?, ?, ?, 0, 0)");
            $insertStmt->bind_param("sss", $alumniData['alumni_id'], $alumniData['alumni_id'], $alumniData['email']);

            if ($insertStmt->execute()) {
                $_SESSION['notification'] = "User added successfully.";
            } else {
                $_SESSION['notification'] = "Failed to add user.";
            }
        }
    } else {
        $_SESSION['notification'] = "Invalid Alumni ID.";
    }
}


// Handle GET requests for verification, unverification, and delete
if (isset($_GET['action']) && isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action === 'verify') {
        if (verifyUserAccess($conn, $userId)) {
            $_SESSION['notification'] = "User verified successfully.";
        } else {
            $_SESSION['notification'] = "Failed to verify user.";
        }
    } elseif ($action === 'unverify') {
        if (unverifyUserAccess($conn, $userId)) {
            $_SESSION['notification'] = "User unverified successfully.";
        } else {
            $_SESSION['notification'] = "Failed to unverify user.";
        }
    } elseif ($action === 'delete') {
        if (deleteUser($conn, $userId)) {
            $_SESSION['notification'] = "User deleted successfully.";
        } else {
            $_SESSION['notification'] = "Failed to delete user.";
        }
    }

    header("Location: ManageAccess.php");
    exit();
}

// Handle add user by alumni_id
if (isset($_POST['alumni_id'])) {
    $alumniId = $_POST['alumni_id'];
    addUserByAlumniId($conn, $alumniId);
    header("Location: ManageAccess.php");
    exit();
}
// Update user access level (is_admin)
if (isset($_POST['user_id']) && isset($_POST['access_level'])) {
    $userId = intval($_POST['user_id']);
    $accessLevel = intval($_POST['access_level']);

    $stmt = $conn->prepare("UPDATE users_access SET is_admin = ? WHERE id = ?");
    $stmt->bind_param("ii", $accessLevel, $userId);

    if ($stmt->execute()) {
        $_SESSION['notification'] = "User access level updated successfully.";
    } else {
        $_SESSION['notification'] = "Failed to update user access level.";
    }

    header("Location: ManageAccess.php");
    exit();
}

// Fetch verified and unverified users
$verifiedUsersQuery = "SELECT * FROM users_access WHERE is_verified = 1";
$unverifiedUsersQuery = "SELECT * FROM users_access WHERE is_verified = 0";

$verifiedUsers = $conn->query($verifiedUsersQuery)->fetch_all(MYSQLI_ASSOC);
$unverifiedUsers = $conn->query($unverifiedUsersQuery)->fetch_all(MYSQLI_ASSOC);

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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="content container mt-4" id="dashboard-content">
 



    <div class="row">
    <!-- Add User Section -->
    <div class="col-md-12">
        <h3>Add User by Alumni ID</h3>
        <form action="ManageAccess.php" method="POST" onsubmit="return validateAlumniId()">
            <div class="form-group">
                <label for="alumni_id">Alumni ID:</label>
                <input type="text" class="form-control" id="alumni_id" name="alumni_id" placeholder="Please enter a valid alumni ID in the format 'KLDAA-#########' (e.g., KLDAA-202400001" required>
               
            </div>
            <button type="submit" class="btn btn-success">Add User</button>
        </form>
        <?php if (isset($_SESSION['notification'])): ?>
            <div class="alert alert-info mt-3">
                <?php echo $_SESSION['notification']; unset($_SESSION['notification']); ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<br>
<script>
    function validateAlumniId() {
        var alumniId = document.getElementById("alumni_id").value;
        var alumniIdPattern = /^KLDAA-\d{9}$/;  // Regex to match "KLDAA-" followed by 9 digits

        if (!alumniIdPattern.test(alumniId)) {
            alert("Please enter a valid alumni ID in the format 'KLDAA-#########' (e.g., KLDAA-202400001).");
            return false;  // Prevent form submission if validation fails
        }
        return true;  // Allow form submission if validation passes
    }
</script>

    <div class="row">
        <!-- Verified Users Column -->
        <div class="col-md-12">
            <h3>Verified Users</h3>
            <table id="verifiedUserTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Alumni ID</th>
                        <th>Username</th>
           
                        <th>Access Level</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($verifiedUsers as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['alumni_id']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
             
                            <td><?php echo $user['is_admin'] ? "Admin" : "User"; ?></td>
                            <td>
                                <a href="ManageAccess.php?action=unverify&id=<?php echo urlencode($user['id']); ?>" class="btn btn-warning">Unverify</a>
                                <button type="button" class="btn btn-info edit-access-btn" data-user-id="<?php echo urlencode($user['id']); ?>" data-current-access-level="<?php echo $user['is_admin']; ?>">Edit Access Level</button>
                                <a href="ManageAccess.php?action=delete&id=<?php echo urlencode($user['id']); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Unverified Users Column -->
        <div class="col-md-12">
            <h3>Unverified Users</h3>
            <table id="unverifiedUserTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Alumni ID</th>
                        <th>Username</th>
                
                        <th>Access Level</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($unverifiedUsers as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['alumni_id']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                   
                            <td><?php echo $user['is_admin'] ? "Admin" : "User"; ?></td>
                            <td>
                                <a href="ManageAccess.php?action=verify&id=<?php echo urlencode($user['id']); ?>" class="btn btn-primary">Verify</a>
                                <a href="ManageAccess.php?action=delete&id=<?php echo urlencode($user['id']); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<!-- Modal for Editing Access Level -->
<div class="modal fade" id="editAccessModal" tabindex="-1" aria-labelledby="editAccessModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAccessModalLabel">Edit Access Level</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editAccessForm" action="ManageAccess.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="userId" name="user_id" value="">
                    <div class="form-group">
                        <label for="accessLevel">Access Level</label>
                        <select class="form-control" id="accessLevel" name="access_level">
                            <option value="1">Admin</option>
                            <option value="0">User</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="./resources/popper.min.js"></script>
<script src="./resources/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        $('#verifiedUserTable').DataTable();
        $('#unverifiedUserTable').DataTable();
    });

    $(document).on('click', '.edit-access-btn', function() {
    var userId = $(this).data('user-id');
    var currentAccessLevel = $(this).data('current-access-level');
    
    // Set the values in the modal form
    $('#userId').val(userId);
    $('#accessLevel').val(currentAccessLevel);
    
    // Show the modal
    $('#editAccessModal').modal('show');
});


</script>

</body>
</html>
