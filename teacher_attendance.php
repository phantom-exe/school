<?php
$page_title = "Attendance";
$current_page = "teacher_attendance";

// Include the template
include 'teacher_template.php';

// Fetch classes taught by this teacher
try {
    $class_query = "SELECT id, class_name FROM classes WHERE teacher_id = ?";
    $stmt = $pdo->prepare($class_query);
    $stmt->execute([$teacher['id']]);
    $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // If class is selected, fetch students
    $selected_class = $_GET['class_id'] ?? null;
    
    $students = [];
    if ($selected_class) {
        $student_query = "
            SELECT 
                s.id, 
                s.roll_no,
                s.class,
                u.username,
                GROUP_CONCAT(
                    CONCAT(
                        DATE_FORMAT(ar.date, '%Y-%m-%d'),
                        ' | ',
                        ar.status,
                        ' | ',
                        TIME_FORMAT(ar.check_in_time, '%H:%i')
                    ) 
                    ORDER BY ar.date DESC, ar.check_in_time DESC
                ) as attendance_history,
                MAX(CASE WHEN DATE(ar.date) = CURRENT_DATE THEN ar.status END) as today_status
            FROM students s
            JOIN users u ON s.user_id = u.id
            LEFT JOIN attendance_Records ar ON s.id = ar.student_id
            WHERE s.class = ?
            GROUP BY s.id, s.roll_no, s.class, u.username
            ORDER BY s.roll_no
        ";
        
        // Debug the query
        echo "Query: " . $student_query . "<br>";
        echo "Selected Class: " . $selected_class . "<br>";
        
        $stmt = $pdo->prepare($student_query);
        $stmt->execute([$selected_class]);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Debug the results
        foreach ($students as $student) {
            echo "Student ID: " . $student['id'] . ", History: " . ($student['attendance_history'] ?? 'No history') . "<br>";
        }
    }
} catch (Exception $e) {
    die("Database error: " . $e->getMessage());
}

// Let's also add some sample data if the database is empty
if (empty($classes)) {
    try {
        // Insert sample class with all required fields
        $stmt = $pdo->prepare("
            INSERT INTO classes (
                teacher_id, 
                class_name, 
                subject, 
                class_Date, 
                start_time, 
                end_time, 
                room_number
            ) 
            VALUES 
            (?, 'Sample Class A', 'Mathematics', CURRENT_DATE, '09:00:00', '10:00:00', '101'),
            (?, 'Sample Class B', 'Mathematics', CURRENT_DATE, '11:00:00', '12:00:00', '102')
        ");
        $stmt->execute([$teacher['id'], $teacher['id']]);
        
        // Insert sample students
        $stmt = $pdo->prepare("
            INSERT INTO students (name, roll_no) 
            VALUES 
            ('John Doe', '001'),
            ('Jane Smith', '002'),
            ('Bob Wilson', '003')
        ");
        $stmt->execute();
        
        // Get the class IDs
        $class_id = $pdo->lastInsertId();
        
        // Link students to class
        $stmt = $pdo->prepare("
            INSERT INTO class_students (class_id, student_id)
            SELECT ?, id FROM students
        ");
        $stmt->execute([$class_id]);
        
        echo "Sample data inserted successfully!<br>";
        
        // Refresh the page to show new data
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (Exception $e) {
        echo "Error inserting sample data: " . $e->getMessage() . "<br>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - LMS Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="teacher_dashboard.css">
    <style>
        /* Additional styles for attendance management */
        .attendance-table {
            width: 100%;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-top: 20px;
        }

        .attendance-table th,
        .attendance-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .attendance-table th {
            background: #f8f9fa;
            font-weight: 600;
        }

        .status-select {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
            width: 120px;
        }

        .save-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .save-btn:hover {
            background: #218838;
        }

        .history-table {
            width: 100%;
            font-size: 0.9em;
        }
        .history-table tr {
            border-bottom: 1px solid #eee;
        }
        .history-table td {
            padding: 4px 8px;
        }
        .status-badge {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            color: white;
        }
        .status-badge.present {
            background-color: #28a745;
        }
        .status-badge.absent {
            background-color: #dc3545;
        }
        .status-badge.late {
            background-color: #ffc107;
            color: #000;
        }
        .no-records {
            color: #6c757d;
            font-style: italic;
        }
        .action-buttons {
            margin-bottom: 20px;
        }
        .add-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .add-btn:hover {
            background: #218838;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: black;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group select,
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
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
                <li><a href="teacher_assignments.php"><i class="fas fa-book"></i><span>Assignments</span></a></li>
                <li><a href="teacher_attendance.php" class="active"><i class="fas fa-calendar-check"></i><span>Attendance</span></a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
            </ul>
        </nav>

        <main class="main-content">
            <div class="header">
                <h1>Attendance Management</h1>
            </div>

            <div class="quick-stats">
                <div class="stat-card">
                    <h3>85%</h3>
                    <p>Average Attendance</p>
                </div>
                <div class="stat-card">
                    <h3>120</h3>
                    <p>Total Students</p>
                </div>
                <div class="stat-card">
                    <h3>15</h3>
                    <p>Late Today</p>
                </div>
                <div class="stat-card">
                    <h3>5</h3>
                    <p>Absent Today</p>
                </div>
            </div>

            <div class="content-section">
                <div class="section-header">
                    <h2>Today's Attendance</h2>
                    <div class="header-actions">
                        <form method="GET" action="">
                            <select name="class_id" id="classSelect" class="form-select" onchange="this.form.submit()">
                                <option value="">Select Class</option>
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?php echo $class['id']; ?>" <?php echo $selected_class == $class['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($class['class_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </div>
                </div>

                <?php if ($selected_class && $students): ?>
                    <div class="action-buttons">
                        <button type="button" class="add-btn" onclick="showAddAttendanceModal()">
                            <i class="fas fa-plus"></i> Add Past Attendance
                        </button>
                    </div>
                    <form method="POST" action="update_attendance.php">
                        <table class="attendance-table">
                            <thead>
                                <tr>
                                    <th>Roll No</th>
                                    <th>Name</th>
                                    <th>Today's Status</th>
                                    <th>Previous Records</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($student['roll_no']); ?></td>
                                        <td><?php echo htmlspecialchars($student['username']); ?></td>
                                        <td>
                                            <select name="attendance[<?php echo $student['id']; ?>]" class="status-select">
                                                <option value="present" <?php echo $student['today_status'] == 'present' ? 'selected' : ''; ?>>Present</option>
                                                <option value="absent" <?php echo $student['today_status'] == 'absent' ? 'selected' : ''; ?>>Absent</option>
                                                <option value="late" <?php echo $student['today_status'] == 'late' ? 'selected' : ''; ?>>Late</option>
                                            </select>
                                        </td>
                                        <td>
                                            <?php if ($student['attendance_history']): ?>
                                                <table class="history-table">
                                                    <?php 
                                                    $records = explode(',', $student['attendance_history']);
                                                    foreach ($records as $record): 
                                                        list($date, $status, $time) = explode('|', $record);
                                                    ?>
                                                        <tr>
                                                            <td><?php echo trim($date); ?></td>
                                                            <td>
                                                                <span class="status-badge <?php echo trim($status); ?>">
                                                                    <?php echo ucfirst(trim($status)); ?>
                                                                </span>
                                                            </td>
                                                            <td><?php echo trim($time); ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </table>
                                            <?php else: ?>
                                                <span class="no-records">No previous records</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <button type="submit" class="save-btn">Save Changes</button>
                    </form>

                    <!-- Add Modal for Past Attendance -->
                    <div id="addAttendanceModal" class="modal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <h2>Add Past Attendance Record</h2>
                            <form method="POST" action="add_attendance.php">
                                <div class="form-group">
                                    <label for="student">Select Student:</label>
                                    <select name="student_id" required>
                                        <?php foreach ($students as $student): ?>
                                            <option value="<?php echo $student['id']; ?>">
                                                <?php echo htmlspecialchars($student['roll_no'] . ' - ' . $student['username']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="date">Date:</label>
                                    <input type="date" name="date" required max="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="time">Time:</label>
                                    <input type="time" name="time" required>
                                </div>
                                <div class="form-group">
                                    <label for="status">Status:</label>
                                    <select name="status" required>
                                        <option value="present">Present</option>
                                        <option value="absent">Absent</option>
                                        <option value="late">Late</option>
                                    </select>
                                </div>
                                <button type="submit" class="save-btn">Add Record</button>
                            </form>
                        </div>
                    </div>
                <?php elseif ($selected_class): ?>
                    <p>No students found for this class.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        function showAddAttendanceModal() {
            document.getElementById('addAttendanceModal').style.display = 'block';
        }

        // Close modal when clicking the X
        document.querySelector('.close').onclick = function() {
            document.getElementById('addAttendanceModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target == document.getElementById('addAttendanceModal')) {
                document.getElementById('addAttendanceModal').style.display = 'none';
            }
        }
    </script>
</body>
</html> 