<?php
$page_title = "My Classes";
$current_page = "teacher_classes";

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
    <div class="container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="profile-section">
                <img src="<?php echo htmlspecialchars($teacher['profile_image'] ?? 'assets/images/default-avatar.png'); ?>" 
                     alt="Profile" class="profile-image">
                <h3><?php echo htmlspecialchars($teacher['name'] ?? 'Unknown Teacher'); ?></h3>
                <p><?php echo htmlspecialchars($teacher['subject'] ?? 'No Subject'); ?></p>
            </div>
            <ul class="nav-links">
                <li><a href="teacher_dashboard.php"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
                <li><a href="teacher_classes.php" class="active"><i class="fas fa-users"></i><span>My Classes</span></a></li>
                <li><a href="teacher_assignments.php"><i class="fas fa-book"></i><span>Assignments</span></a></li>
                <li><a href="teacher_attendance.php"><i class="fas fa-calendar-check"></i><span>Attendance</span></a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
            </ul>
        </nav>
        


    </div>
        <main class="main-content">
            <div class="header">
                <h1>My Classes</h1>
            </div>
            <div class="quick-stats">
                <div class="stat-card">
                    <h3>25</h3>
                    <p>Total Students</p>
                </div>
                <div class="stat-card">
                    <h3>5</h3>
                    <p>Active Classes</p>
                </div>
                <div class="stat-card">
                    <h3>12</h3>
                    <p>Hours Today</p>
                </div>
                <div class="stat-card">
                    <h3>4</h3>
                    <p>Classes Today</p>
                </div>
            </div>
            <div class="content-section">
                <div class="section-header">
                    <h2>Class List</h2>
                    <button class="btn-primary">
                        <i class="fas fa-plus"></i> Add New Class
                    </button>
                </div>
                <div class="class-list">
                    <?php
                    // Assuming you have a query to fetch classes
                    if (isset($classes) && !empty($classes)) {
                        foreach ($classes as $class) {
                            // Display class information
                            echo '<div class="class-card">';
                            echo '<h3>' . htmlspecialchars($class['name']) . '</h3>';
                            // Add more class details as needed
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="no-data-message">';
                        echo '<i class="fas fa-users-slash"></i>';
                        echo '<p>No students found for this class</p>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>






    
