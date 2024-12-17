<?php
session_start();
require_once 'auth_check.php';

// Verify user is logged in and is a student
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'student') {
    header('Location: index.html');
    exit();
}

$page_title = "Dashboard";
$current_page = "student_dashboard";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - LMS Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="common.css">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="logo">
                <h2>LMS Portal</h2>
            </div>
            <div class="user-info">
                <img src="assets/images/avatar.png" alt="User Avatar" class="avatar">
                <h3><?php echo htmlspecialchars($_SESSION['username']); ?></h3>
                <p><?php echo ucfirst(htmlspecialchars($_SESSION['user_type'])); ?></p>
            </div>
            <ul class="nav-links">
                <li><a href="student_dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="student_attendance.php"><i class="fas fa-calendar-check"></i> Attendance</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <div class="dashboard-content">
                <h1>Welcome Back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <i class="fas fa-book"></i>
                        <div class="stat-info">
                            <h3>5</h3>
                            <p>Active Courses</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-tasks"></i>
                        <div class="stat-info">
                            <h3>3</h3>
                            <p>Pending Tasks</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-trophy"></i>
                        <div class="stat-info">
                            <h3>85%</h3>
                            <p>Average Grade</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-clock"></i>
                        <div class="stat-info">
                            <h3>12</h3>
                            <p>Hours Spent</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
