<?php
session_start();
require_once 'db_connect.php';
require_once 'auth_check.php';

// Check session
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'teacher') {
    header('Location: index.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['attendance'])) {
    try {
        $pdo->beginTransaction();

        // Delete existing records for today to avoid duplicates
        $delete_stmt = $pdo->prepare("
            DELETE FROM attendance_Records 
            WHERE student_id = ? 
            AND DATE(date) = CURRENT_DATE
        ");

        // Insert new attendance record
        $insert_stmt = $pdo->prepare("
            INSERT INTO attendance_Records (
                student_id, 
                date, 
                status, 
                check_in_time
            ) VALUES (
                ?, 
                CURRENT_DATE, 
                ?, 
                CURRENT_TIME
            )
        ");

        // Get the class information
        $class_query = $pdo->prepare("
            SELECT class FROM students WHERE id = ? LIMIT 1
        ");

        foreach ($_POST['attendance'] as $student_id => $status) {
            // Get student's class
            $class_query->execute([$student_id]);
            $student_class = $class_query->fetchColumn();

            if ($student_class) {
                // First delete today's record for this student
                $delete_stmt->execute([$student_id]);
                
                // Then insert the new record
                $insert_stmt->execute([
                    $student_id,
                    $status
                ]);
            }
        }

        $pdo->commit();
        
        // Redirect back with success message
        $class_id = $_GET['class_id'] ?? '';
        header('Location: teacher_attendance.php?class_id=' . $class_id . '&success=1');
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        // Redirect back with error message
        $class_id = $_GET['class_id'] ?? '';
        header('Location: teacher_attendance.php?class_id=' . $class_id . '&error=' . urlencode($e->getMessage()));
        exit();
    }
} else {
    // If no POST data, redirect back to attendance page
    header('Location: teacher_attendance.php');
    exit();
} 