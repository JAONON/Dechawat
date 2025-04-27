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
$stepFilter = isset($_POST['stepFilter']) ? $_POST['stepFilter'] : null;

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

if(!$stepFilter){
    $stepFilter = $projectStatus[0]['status_id'];
}

// get comment
$sql = "
    SELECT 
        comment.*, 
        comment_img.image_name ,
        member.name,
        member.lastname
    FROM comment 
    LEFT JOIN comment_img ON comment_img.comment_id = comment.comment_id
    LEFT JOIN member ON comment.member_id = member.member_id
    WHERE 
        project_id = :project_id
    AND status_id = :filter
    ORDER BY comment.created_at DESC
";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
$stmt->bindParam(':filter', $stepFilter, PDO::PARAM_INT);
$stmt->execute();
$Arrcomment = $stmt->fetchAll(PDO::FETCH_ASSOC);
$commentId = array_column($Arrcomment, 'comment_id');

$comment = [];
foreach ($Arrcomment as $key => $value) {
    $comment[$value["status_id"]][] = [
        'comment_id' => $value['comment_id'],
        'username' => $value['name']." ".$value['lastname'],
        'comment' => $value['message'],
        'image' => $value['image_name'],
        'timestamp' => $value['created_at'],
        'step_id' => $value['status_id'],
    ];
}

// get sub_comment
if (empty($commentId)) {
    $commentId = [null];
}

// เตรียมแปลง array เป็น string
$commentIdSafe = array_map(function($id) {
    return ($id === null) ? 'NULL' : (int)$id;
}, $commentId);

$sql = "
    SELECT 
        sub_comment.*,
        member.name,
        member.lastname
    FROM sub_comment 
    JOIN member ON sub_comment.member_id = member.member_id
    WHERE 
        project_id = :project_id
    AND comment_id IN (".implode(',', $commentIdSafe).")
    ORDER BY created_at ASC
";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
$stmt->execute();
$ArrSubcomment = $stmt->fetchAll(PDO::FETCH_ASSOC);

$subcomment = [];
foreach ($ArrSubcomment as $key2 => $value2) {
    $subcomment[] = [
        "sub_comment_id" => $value2["sub_comment_id"],
        "parent_comment_id" => $value2["comment_id"],
        "username" => $value2["name"]." ".$value2["lastname"],
        "comment" => $value2["message"],
        "timestamp" => $value2["created_at"],
    ];
}

$projectData = $project;
$image = $arrImageProject;
$status = $projectStatus;
$comments = $comment;
$subcomments = $subcomment;
$selectStatusSearch = $stepFilter;

include './viewprojectdetail.php';