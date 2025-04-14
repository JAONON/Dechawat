<?php
// Include database connection
require_once '../db_connection.php';

// Ensure $conn is a valid PDO instance
if (!$conn instanceof PDO) {
    die('Database connection error.');
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = trim($_POST["username"]);
    $password = md5(trim($_POST["password"])); // Hash the password
    $name = trim($_POST["name"]);
    $lastname = trim($_POST["lastname"]);
    $birthday = trim($_POST["birthday"]);
    $number_id = trim($_POST["number_id"]);

    // Prepare and execute the SQL query
    $sql = "
        INSERT INTO 
            member 
        SET
            username = :username, 
            password = :password, 
            name = :name, 
            lastname = :lastname, 
            birthday_date = :birthday,
            number_id = :number_id
    ";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':birthday', $birthday);
        $stmt->bindParam(':number_id', $number_id);
        
        if ($stmt->execute()) {
            echo 'Registration successful!';
            echo "<script>window.location.href = '../login/login.php'</script>";
        } else {
            echo 'Error: ' . $stmt->errorInfo()[2];
        }

        $stmt->closeCursor();
    } else {
        echo 'Error: ' . $conn->errorInfo()[2];
        echo "<script>window.reload()'</script>";
    }

    // Close the database connection
    $conn = null;
} else {
    echo 'Invalid request method.';
}