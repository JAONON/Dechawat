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

// Get member_id and page from request
$member_id = $_SESSION['member']['member_id'];
$searchText = isset($_GET['searchText']) ? '%'.trim($_GET['searchText']).'%' : '%%';
$searchDate = isset($_GET['searchDate']) ? trim($_GET['searchDate']) : '';
$searchStatus = isset($_GET['searchStatus']) ? trim($_GET['searchStatus']) : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;


$getLimit = "
    SELECT * 
    FROM project 
    WHERE member_id = :member_id
";

$stmtLimit = $conn->prepare($getLimit);

// Bind ค่าพื้นฐาน
$stmtLimit->bindValue(':member_id', $member_id, PDO::PARAM_INT);
$stmtLimit->execute();
$project = $stmtLimit->fetchAll(PDO::FETCH_ASSOC);

$arrProjects = $project;

include './viewGraph.php';