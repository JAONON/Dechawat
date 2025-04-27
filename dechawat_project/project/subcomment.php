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
    
    $subcomment = $_POST['subcomment'];
    $project_id = $_POST['project_id'];
    $comment_id = $_POST['comment_id'];
    $member_id = $_SESSION['member']['member_id'];

    $sql = "
        INSERT INTO
            sub_comment 
        SET 
            comment_id = :comment_id,
            project_id = :project_id,
            member_id = :member_id,
            message = :message,
            updated_at = NOW()
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':comment_id', $comment_id);
    $stmt->bindValue(':project_id', $project_id);
    $stmt->bindValue(':member_id', $member_id);
    $stmt->bindParam(':message', $subcomment);
    $stmt->execute();
    
    echo "
        <form id='redirectForm' method='POST' action='../project/getdetailproject.php'>
            <input type='hidden' name='project_id' value='" . htmlspecialchars($project_id, ENT_QUOTES, 'UTF-8') . "'>
        </form>
        <script>
            document.getElementById('redirectForm').submit();
        </script>
    ";
    
    // Close the database connection
    $conn = null;