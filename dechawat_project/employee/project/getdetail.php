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

    // get status
    $sql = "SELECT * FROM status WHERE project_id = :project_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
    $stmt->execute();
    $projectStatus = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $projectData = $result;
    $images = $image;
    $status = $projectStatus;

    include './viewdetail.php';