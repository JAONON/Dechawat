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
    // Retrieve data from the form
    $member_id = $_SESSION['member']['member_id'];
    $project_name = isset($_POST['project_name']) ? trim($_POST['project_name']) : '';
    $project_description = isset($_POST['project_description']) ? trim($_POST['project_description']) : '';
    $project_price = isset($_POST['project_price']) ? trim($_POST['project_price']) : '';
    $contract = isset($_POST['contract']) ? trim($_POST['contract']) : '';
    $project_date = isset($_POST['project_date']) ? trim($_POST['project_date']) : '';
    
    // Prepare SQL query to insert project
    $sql = "
        INSERT INTO 
            project 
        SET 
            member_id = :member_id,
            name = :project_name, 
            description = :project_description,
            price = :project_price,
            employment_contract = :contract,
            date_project = :date_project
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':project_name', $project_name);
    $stmt->bindParam(':project_description', $project_description);
    $stmt->bindParam(':project_price', $project_price);
    $stmt->bindParam(':contract', $contract);
    $stmt->bindParam(':date_project', $project_date);
    $stmt->bindParam(':member_id', $member_id);
    $stmt->execute();
    $lastInsertId = $conn->lastInsertId();
    
    if($lastInsertId && $_FILES){
        // Define the directory path
        $uploadDir = '../asset/image/project/' . $lastInsertId . '/';

        // Check if the directory exists, if not, create it
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $arrPic = $_FILES['blueprint_images'];
        
        // Loop through each uploaded file
        foreach ($arrPic['tmp_name'] as $key => $tmpName) {
            $datetime = date('YmdHis');
            $fileExtension = pathinfo($_FILES['blueprint_images']['name'][$key], PATHINFO_EXTENSION);
            $newFileName = "project_image_{$datetime}_{$key}." . $fileExtension;
            
            $targetFilePath = $uploadDir . $newFileName;
            
            // Move the uploaded file to the target directory
            if (move_uploaded_file($tmpName, $targetFilePath)) {
                // Prepare SQL query to insert project
                $sqlImage = "
                    INSERT INTO 
                        project_image 
                    SET 
                        project_id = :project_id,
                        member_id = :member_id,
                        project_image_name = :project_image_name, 
                        updated_at = NOW()
                ";
                $stmt = $conn->prepare($sqlImage);
                $stmt->bindParam(':project_image_name', $newFileName);
                $stmt->bindParam(':member_id', $member_id);
                $stmt->bindParam(':project_id', $lastInsertId);
                $stmt->execute();
            }
        }
    }
    echo 'Project saved successfully!';
    echo "<script>window.location.href = '../project/getproject.php'</script>";
    
    // Close the database connection
    $conn = null;
} else {
    echo 'Invalid request method.';
}