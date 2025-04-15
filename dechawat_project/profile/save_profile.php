<?php
// Include database connection
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
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {

        $uploadDir = realpath(__DIR__ . '/../asset/image/profile') . '/';
        $memberId = $_SESSION['member']['member_id'];
        $memberFolder = $uploadDir . $memberId;

        // Check if the member folder exists, if not, create it
        if (!is_dir($memberFolder)) {
            if (!mkdir($memberFolder, 0777, true)) {
                die("Failed to create directory: $memberFolder");
            }
        }

        // Generate a new file name
        $datetime = date('YmdHis');
        $fileExtension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
        $newFileName = "member_logo_{$datetime}." . $fileExtension;

        // Move the uploaded file to the member folder
        $destination = $memberFolder . '/' . $newFileName;
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $destination)) {
            // File uploaded successfully, return the new file name
            $uploadedFileName = $newFileName;
        } else {
            echo "Error uploading file.";
            exit();
        }
    }
    
    $member_id = $_SESSION['member']['member_id'];
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $lastname = isset($_POST['lastname']) ? trim($_POST['lastname']) : '';
    $birthDayDate = isset($_POST['birthday']) ? trim($_POST['birthday']) : '';
    $number = isset($_POST['number']) ? trim($_POST['number']) : '';
    $gender = isset($_POST['gender']) ? trim($_POST['gender']) : '';
    $phonenumber = isset($_POST['phonenumber']) ? trim($_POST['phonenumber']) : '';
    $member_logo = isset($_POST['member_logo']) ? trim($_POST['member_logo']) : '';
    $finalFileName = !empty($newFileName) ? $newFileName : $member_logo;
    // Prepare SQL query to update member
    $sql = "
        UPDATE 
            member 
        SET 
            name = :name, 
            lastname = :lastname, 
            birthday_date = :birthDayDate,
            phone_number = :phonenumber,
            gender = :gender, 
            number_id = :number,
            member_logo = :newFileName
        WHERE 
            member_id = :member_id
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':member_id', $member_id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':birthDayDate', $birthDayDate);
    $stmt->bindParam(':number', $number);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':phonenumber', $phonenumber);
    $stmt->bindParam(':newFileName', $finalFileName);
    
    // Execute the query
    if ($stmt->execute()) {
        header('Location: ../profile/get_profile.php');
        exit;
    } else {
        $errorInfo = $stmt->errorInfo();
        header('Location: ../profile/get_profile.php?status=error&msg=' . urlencode($errorInfo[2]));
        exit;
    }

    // Close the statement
    unset($stmt);
} else {
    echo "Invalid request method.";
}

// Close the database connection
// Close the database connection
unset($conn);
?>