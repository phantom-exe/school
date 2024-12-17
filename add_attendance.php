<?php
session_start();
require_once 'db_connect.php';
require_once 'auth_check.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'teacher') {
    header('Location: index.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO attendance_Records (
                student_id,
                date,
                status,
                check_in_time
            ) VALUES (?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $_POST['student_id'],
            $_POST['date'],
            $_POST['status'],
            $_POST['time']
        ]);

        header('Location: teacher_attendance.php?class_id=' . $_GET['class_id'] . '&success=1');
        exit();
    } catch (Exception $e) {
        header('Location: teacher_attendance.php?class_id=' . $_GET['class_id'] . '&error=' . urlencode($e->getMessage()));
        exit();
    }
}

header('Location: teacher_attendance.php');
exit(); 