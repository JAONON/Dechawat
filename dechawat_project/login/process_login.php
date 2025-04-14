<?php
// Start session
session_start();

// Include database connection file
require_once '../db_connection.php';

if (!$conn instanceof PDO) {
    die('Database connection error.');
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get username and password from POST request
    $username = trim($_POST['username']);
    $password = md5(trim($_POST['password'])); // Get the raw password
    
    // Validate input
    if (empty($username) || empty($password)) {
        header('Location: ../login.php');
        exit();
    }

    // Prepare SQL query to check user credentials
    $sql = "
        SELECT 
            * 
        FROM 
            member 
        WHERE 
            username = :username
        AND password = :password
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Debugging code removed
    if($user){
        // Redirect to dashboard or home page
        $_SESSION["member"] = $user;
        header('Location: ../index/index.php');
        exit();
    } else {
        header('Location: ../login/login.php');
        exit();
    }
} else {
    // Redirect to login page if accessed directly
    header('Location: ../login/login.php');
    exit();
}
?>