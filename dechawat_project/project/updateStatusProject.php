<?php
require_once '../db_connection.php';
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

    // get status
    $sql = "SELECT * FROM status WHERE project_id = :project_id AND success = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':project_id', $projectId, PDO::PARAM_INT);
    $stmt->execute();
    $projectStatus = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $countStatus = count($projectStatus);
    
    if ($countStatus > 0) {
        echo "
            <script>
                alert('Cannot Update Project Success, There are still steps that are not completed.');
                window.location = '../project/getproject.php?project_id=" . urlencode($projectId) . "';
            </script>
        ";
        exit();
    }
    // Prepare SQL query to insert project
    $sql = "
        UPDATE
            project 
        SET 
            status = :status
        WHERE
            project_id = :project_id
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':project_id', $projectId);
    $stmt->bindValue(':status', "complete");
    $stmt->execute();
    
    echo "
        <form id='redirectForm' method='POST' action='../project/getproject.php'>
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