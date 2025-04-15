<?php
    require_once '../../db_connection.php';
    session_start();

    // Get project_id from POST
    $project_id = $_POST['project_id'];

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT * FROM project WHERE project_id = :project_id");
    $stmt->bindValue(":project_id", $project_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $img = $conn->prepare("SELECT * FROM project_image WHERE project_id = :project_id");
    $img->bindValue(":project_id", $project_id);
    $img->execute();
    $image = $img->fetchAll(PDO::FETCH_ASSOC);

    $project = $result;
    $images = $image;

    include './viewdetail.php';