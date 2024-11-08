
<header id="header">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="dashboard.php">
            <img src="./image/kld-logo-removebg-preview.png" class="logo-shadow" alt="Brand Logo" />
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav" id="navbar-links">
                <?php if ($is_admin): ?>
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="MyProfile.php">My Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="AlumniProfiles.php">Alumni Profiles</a></li>
                    <li class="nav-item"><a class="nav-link" href="ManageAccess.php">Manage Access</a></li>
                    <li class="nav-item"><a class="nav-link" href="NewsArticle.php">News & Articles</a></li>
                    <li class="nav-item"><a class="nav-link" href="Events.php">Events</a></li>
                    <li class="nav-item"><a class="nav-link" href="JobListing.php">Job Listing</a></li>
                <li class="nav-item"><a class="nav-link" href="Forums.php">Forums</a></li>

                <?php else: ?>

                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="MyProfile.php">My Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="NewsArticle.php">News & Articles</a></li>
                    <li class="nav-item"><a class="nav-link" href="Events.php">Events</a></li>
                    <li class="nav-item"><a class="nav-link" href="JobListing.php">Job Listing</a></li>
                    <li class="nav-item"><a class="nav-link" href="Forums.php">Forums</a></li>
                    
                <?php endif; ?>
            </ul>
            <div class="dropdown ml-auto">
                <div class="circular-display" title="Profile">
                    <img src="image/<?php echo htmlspecialchars($profile['profile_picture']); ?>" class="circular-display" alt="Profile Picture">
                </div>
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Options
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <p class="dropdown-item"><strong>Alumni ID:</strong> <?php echo htmlspecialchars($alumni_id); ?></p>
                    <p class="dropdown-item"><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
                    <p class="dropdown-item"><strong>Role:</strong> <?php echo $is_admin ? 'Admin' : 'User'; ?></p>
                    <a href="#" class="dropdown-item" onclick="return confirmLogout()">Logout</a>

<script>
    function confirmLogout() {
        var confirmAction = confirm("Are you sure you want to logout?");
        if (confirmAction) {
            window.location.href = 'Survey.php'; // Redirect to logout if confirmed
        } else {
            return false; // Prevent the default action (logout) if cancelled
        }
    }
</script>

                </div>
            </div>
        </div>
    </nav>
</header>

<style>
    /* Custom styles for logo shadow */
    .logo-shadow {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        width: 45px; /* Adjust size as needed */
        height: 40px; /* Ensure it's square for a circular effect */
        border-radius: 50%; /* Make it circular */
        object-fit: cover; /* Ensure the image covers the space */
        border: 2px solid #f2b129; /* Optional: Add a border for style */
    }

    .logo-shadow:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.7);
    }

    .dropdown-menu {
        margin-top: -7px; /* Move dropdown options up by 5px */
    }

    /* Active link style */
    .nav-link.active {
       
        color: #fff; /* Change text color for active link */
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const navbarLinks = document.querySelectorAll("#navbar-links .nav-link");
        const currentUrl = window.location.href;

        navbarLinks.forEach(link => {
            if (link.href === currentUrl) {
                link.classList.add("active");
            }
        });
    });
</script>
