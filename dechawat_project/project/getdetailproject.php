<?php
require_once '../db_connection.php';
session_start();
// Check if user is logged in
if (!isset($_SESSION['member'])) {
    header('Location: ../login/login.php');
    exit();
}

// Get POST data
$member_id = isset($_SESSION['member']['member_id']) ? $_SESSION['member']['member_id'] : null;
$project_id = isset($_POST['project_id']) ? $_POST['project_id'] : null;

// Prepare SQL query
$sql = "SELECT * FROM project WHERE member_id = :member_id AND project_id = :project_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':member_id', $member_id, PDO::PARAM_INT);
$stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
$stmt->execute();
$project = $stmt->fetch(PDO::FETCH_ASSOC);

// get status
$sql = "SELECT * FROM status WHERE project_id = :project_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
$stmt->execute();
$projectStatus = $stmt->fetchAll(PDO::FETCH_ASSOC);

$imageProject = "SELECT * FROM project_image WHERE member_id = :member_id AND project_id = :project_id";
$image = $conn->prepare($imageProject);
$image->bindParam(':member_id', $member_id, PDO::PARAM_INT);
$image->bindParam(':project_id', $project_id, PDO::PARAM_INT);
$image->execute();
$arrImageProject = $image->fetchAll(PDO::FETCH_ASSOC);

$image = $arrImageProject;
$projectData = $project;
$status = $projectStatus;

include './viewprojectdetail.php';