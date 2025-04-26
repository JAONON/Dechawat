<?php
require_once '../../db_connection.php';
session_start();
// Ensure $conn is a valid PDO instance
if (!$conn instanceof PDO) {
    die('Database connection error.');
}
// Check if the user is logged in
if (!isset($_SESSION['member'])) {
    header('Location: ../login/login.php');
    exit();
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stepId = $_POST["step"];
    $projectId = $_POST["project_id"];
    
    // Prepare SQL query to insert project
    $sql = "
        UPDATE
            status 
        SET 
            success = 1
        WHERE
            status_id = :status_id
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':status_id', $stepId);
    $stmt->execute();
    
    echo "
        <form id='redirectForm' method='POST' action='./getdetail.php'>
            <input type='hidden' name='project_id' value='" . htmlspecialchars($projectId, ENT_QUOTES, 'UTF-8') . "'>
        </form>
        <script>
            document.getElementById('redirectForm').submit();
        </script>
    ";
    
    // Close the database connection
    $conn = null;
} else {
    echo 'Invalid request method.';
}