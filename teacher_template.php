<?php
session_start();
require_once 'db_connect.php';
require_once 'auth_check.php';

// Check session
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'teacher') {
    header('Location: index.html');
    exit();
}

// Set page specific variables
$page_title = "Page Title"; // Change for each page
$current_page = "teacher_page"; // Change for each page

// // At the top of the file, after session_start()
// if (!$pdo) {
//     die("Database connection failed");
// }

// // Debug database connection
// try {
//     $pdo->query("SELECT 1");
//     echo "Database connection successful<br>";
// } catch (Exception $e) {
//     echo "Database connection error: " . $e->getMessage() . "<br>";
// }

try {
    // Fetch teacher data
    $stmt = $pdo->prepare("SELECT * FROM teachers WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $teacher = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Database error: " . $e->getMessage());
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
</head>
<body>
 

    <!-- Add page specific JavaScript here -->
</body>
</html> 