<?php
$page_title = "Assignments";
$current_page = "teacher_assignments";

// Include the template
include 'teacher_template.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - LMS Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="teacher_dashboard.css">
</head>
<body>
    <div class="container">
        <nav class="sidebar">
            <div class="profile-section">
                <img src="<?php echo htmlspecialchars($teacher['profile_image'] ?? 'assets/images/default-avatar.png'); ?>" 
                     alt="Profile" class="profile-image">
                <h3><?php echo htmlspecialchars($teacher['name'] ?? 'Unknown Teacher'); ?></h3>
                <p><?php echo htmlspecialchars($teacher['subject'] ?? 'No Subject'); ?></p>
            </div>
            <ul class="nav-links">
                <li><a href="teacher_dashboard.php"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
                <li><a href="teacher_classes.php"><i class="fas fa-users"></i><span>My Classes</span></a></li>
                <li><a href="teacher_assignments.php" class="active"><i class="fas fa-book"></i><span>Assignments</span></a></li>
                <li><a href="teacher_attendance.php"><i class="fas fa-calendar-check"></i><span>Attendance</span></a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
            </ul>
        </nav>

        <main class="main-content">
            <div class="header">
                <h1>Assignments</h1>
            </div>

            <div class="quick-stats">
                <div class="stat-card">
                    <h3>15</h3>
                    <p>Total Assignments</p>
                </div>
                <div class="stat-card">
                    <h3>5</h3>
                    <p>Pending Review</p>
                </div>
                <div class="stat-card">
                    <h3>10</h3>
                    <p>Completed</p>
                </div>
                <div class="stat-card">
                    <h3>3</h3>
                    <p>Due This Week</p>
                </div>
            </div>

            <div class="content-section">
                <div class="section-header">
                    <h2>Assignment List</h2>
                    <button class="btn-primary">
                        <i class="fas fa-plus"></i> Create Assignment
                    </button>
                </div>
                <!-- Assignment list content here -->
            </div>
        </main>
    </div>
</body>
</html>

        
