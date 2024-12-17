<?php

session_start();
require_once 'db_connect.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $userType = $_POST['userType'];
        
        error_log("Login attempt - Username: $username, Type: $userType");
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND user_type = ?");
        $stmt->execute([$username, $userType]);
        $user = $stmt->fetch();
        
        if ($user && $password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_type'] = $user['user_type'];
            
            $redirect = ($user['user_type'] === 'teacher') ? 'teacher_dashboard.php' : 'student_dashboard.php';
            
            error_log("Login successful - Redirecting to: $redirect");
            
            echo json_encode([
                'success' => true,
                'redirect' => $redirect,
                'user_type' => $user['user_type']
            ]);
        } else {
            error_log("Login failed for username: $username");
            echo json_encode([
                'success' => false,
                'message' => 'Invalid username or password'
            ]);
        }
    } catch(PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Server error occurred'
        ]);
    }
    exit();
}

?>
