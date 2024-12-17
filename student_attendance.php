<?php
session_start();
include('db_connect.php');  // Changed to include
include('auth_check.php');  // Changed to include

// Basic error checking
if (!isset($pdo)) {
    die("Database connection not established");
}

// Check authentication
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'student') {
    header("Location: login.php");
    exit();
}

// Get student data
try {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        $student = [
            'name' => $_SESSION['username'] ?? 'Unknown',
            'roll_no' => 'N/A',
            'class' => 'N/A',
            'profile_image' => 'assets/images/avatar.png'
        ];
    }

    // Get attendance records
    $stmt = $pdo->prepare("
        SELECT date, status, check_in_time 
        FROM attendance_records 
        WHERE student_id = ? 
        ORDER BY date DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $attendance_records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate statistics
    $stats = [
        'present' => 0,
        'absent' => 0,
        'late' => 0,
        'total' => count($attendance_records)
    ];

    foreach ($attendance_records as $record) {
        $stats[$record['status']]++;
    }

    $attendance_rate = $stats['total'] > 0 ? 
        round(($stats['present'] / $stats['total']) * 100) : 0;

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance</title>
    <!-- External CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Your CSS files -->
    <link rel="stylesheet" href="assets/css/common.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/student_attendance.css">
</head>
<body>
    <div class="container">
        <nav class="sidebar">
            <div class="logo">
                <h2>LMS Portal</h2>
            </div>
            <div class="user-info">
                <img src="<?php echo htmlspecialchars($student['profile_image']); ?>" alt="User Avatar" class="avatar">
                <h3><?php echo htmlspecialchars($_SESSION['username'] ?? 'Unknown'); ?></h3>
                <p>Student</p>
            </div>
            <ul class="nav-links">
                <li><a href="student_dashboard.php">Dashboard</a></li>
                <li><a href="student_attendance.php" class="active">Attendance</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>

        <main class="main-content">
            <div class="attendance-stats">
                <div class="stat-box">
                    <h4>Present</h4>
                    <p><?php echo $stats['present']; ?></p>
                </div>
                <div class="stat-box">
                    <h4>Absent</h4>
                    <p><?php echo $stats['absent']; ?></p>
                </div>
                <div class="stat-box">
                    <h4>Late</h4>
                    <p><?php echo $stats['late']; ?></p>
                </div>
                <div class="stat-box">
                    <h4>Attendance Rate</h4>
                    <p><?php echo $attendance_rate; ?>%</p>
                </div>
            </div>

            <div class="attendance-records">
                <h3>Recent Attendance</h3>
                <table border="1">
                    <tr>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Check-in Time</th>
                    </tr>
                    <?php foreach ($attendance_records as $record): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($record['date']); ?></td>
                        <td><?php echo htmlspecialchars($record['status']); ?></td>
                        <td><?php echo htmlspecialchars($record['check_in_time'] ?? 'N/A'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </main>
    </div>
    <!-- Add JavaScript files at the bottom of body -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/student_attendance.js"></script>
</body>
</html> 
