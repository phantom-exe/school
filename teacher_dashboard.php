<?php
session_start();
require_once 'db_connect.php';

// Basic error checking
if (!$pdo) {
    die("Database connection failed");
}

// Check session
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'teacher') {
    die("Not authorized as teacher");
}

try {
    // Simple query to test teacher fetch
    $stmt = $pdo->prepare("
        SELECT * 
        FROM teachers 
        WHERE user_id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $teacher = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$teacher) {
        die("Teacher not found for user_id: " . $_SESSION['user_id']);
    }

    // Simple stats query
    $stats = [
        'total_students' => 0,
        'classes_today' => 0,
        'total_assignments' => 0,
        'avg_attendance_rate' => 0
    ];

} catch (Exception $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary-color: #4a90e2;
            --secondary-color: #f5f6fa;
            --text-color: #2c3e50;
            --sidebar-width: 250px;
        }

        body {
            background-color: var(--secondary-color);
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            position: fixed;
            height: 100vh;
        }

        .profile-section {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #eee;
        }

        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .nav-links {
            margin-top: 30px;
            list-style: none;
        }

        .nav-links li {
            margin-bottom: 10px;
        }

        .nav-links a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: var(--text-color);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-links a:hover, .nav-links a.active {
            background: var(--primary-color);
            color: white;
        }

        .nav-links i {
            margin-right: 10px;
            width: 20px;
        }

        /* Main Content Area */
        .main-content {
            flex: 1;
            padding: 20px;
            margin-left: var(--sidebar-width);
            background: var(--secondary-color);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .quick-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-card h3 {
            font-size: 24px;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .stat-card p {
            color: var(--text-color);
            font-size: 14px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                padding: 10px;
            }

            .profile-section {
                padding: 10px 0;
            }

            .profile-image {
                width: 40px;
                height: 40px;
            }

            .nav-links a span {
                display: none;
            }

            .main-content {
                margin-left: 70px;
            }

            .quick-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .quick-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
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
                <li><a href="teacher_dashboard.php" class="active"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
                <li><a href="teacher_classes.php"><i class="fas fa-users"></i><span>My Classes</span></a></li>
                <li><a href="teacher_assignments.php"><i class="fas fa-book"></i><span>Assignments</span></a></li>
                <li><a href="teacher_attendance.php"><i class="fas fa-calendar-check"></i><span>Attendance</span></a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <div class="header">
                <h1>Welcome, <?php echo htmlspecialchars($teacher['name'] ?? 'Teacher'); ?></h1>
            </div>

            <div class="quick-stats">
                <div class="stat-card">
                    <h3><?php echo $stats['total_students']; ?></h3>
                    <p>Total Students</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $stats['classes_today']; ?></h3>
                    <p>Classes Today</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $stats['total_assignments']; ?></h3>
                    <p>Assignments</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $stats['avg_attendance_rate']; ?>%</h3>
                    <p>Attendance Rate</p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>