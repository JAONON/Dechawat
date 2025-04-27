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
    
    $comment = $_POST['comment'];
    $project_id = $_POST['project_id'];
    $member_id = $_SESSION['member']['member_id'];
    $status_id = $_POST['status_id'];
    $image = $_FILES['image'];

    $sql = "
        INSERT INTO
            comment 
        SET 
            project_id = :project_id,
            member_id = :member_id,
            status_id = :status_id,
            message = :message,
            updated_at = NOW()
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':project_id', $project_id);
    $stmt->bindValue(':member_id', $member_id);
    $stmt->bindValue(':status_id', $status_id);
    $stmt->bindParam(':message', $comment);
    $stmt->execute();
    $lastInsertId = $conn->lastInsertId();

    if($_FILES){
        // Define the directory path
        $uploadDir = '../asset/image/comment/' . $lastInsertId . '/';

        // Check if the directory exists, if not, create it
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $arrPic = $_FILES['image'];
        
        // Loop through each uploaded file
        // foreach ($arrPic['tmp_name'] as $key => $tmpName) {
            $datetime = date('YmdHis');
            $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $newFileName = "project_image_{$datetime}." . $fileExtension;
            
            $targetFilePath = $uploadDir . $newFileName;
            
            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                // Prepare SQL query to insert project
                $sqlImage = "
                    INSERT INTO 
                        comment_img 
                    SET 
                        comment_id = :comment_id,
                        image_name = :image_name, 
                        updated_at = NOW()
                ";
                $stmt = $conn->prepare($sqlImage);
                $stmt->bindValue(':comment_id', $lastInsertId);
                $stmt->bindParam(':image_name', $newFileName);
                $stmt->execute();
            }
        // }
    }
    
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