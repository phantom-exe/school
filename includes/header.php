<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - LMS Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/common.css">
    <?php if(isset($page_css)): ?>
        <link rel="stylesheet" href="assets/css/<?php echo $page_css; ?>.css">
    <?php endif; ?>
    <link rel="stylesheet" href="assets/css/footer.css">
</head>
<body>
    <div class="container">
        <!-- Common Sidebar -->
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
                <li><a href="dashboard.php" class="<?php echo ($current_page === 'dashboard') ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i> Dashboard
                </a></li>
                <li><a href="student_attendance.php" class="<?php echo ($current_page === 'attendance') ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-check"></i> Attendance
                </a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav> 