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

// Pagination settings
$items_per_page = 20;
$offset = ($page - 1) * $items_per_page;

// COUNT NO LIMIT
$sql = "SELECT COUNT(*) as total FROM project WHERE member_id = :member_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':member_id', $member_id, PDO::PARAM_INT);
$stmt->execute();
$countAll = $stmt->fetch(PDO::FETCH_ASSOC);

$getLimit = "
    SELECT * 
    FROM project 
    WHERE member_id = :member_id 
    AND name LIKE :searchText
";

// เพิ่มเงื่อนไขแบบปลอดภัย
if ($searchDate) {
    $getLimit .= " AND date_project = :searchDate";
}
if ($searchStatus) {
    $getLimit .= " AND status = :searchStatus";
}

$getLimit .= " 
ORDER BY created_at DESC
LIMIT :offset, :items_per_page";

$stmtLimit = $conn->prepare($getLimit);

// Bind ค่าพื้นฐาน
$stmtLimit->bindValue(':member_id', $member_id, PDO::PARAM_INT);
$stmtLimit->bindValue(':searchText', "%$searchText%", PDO::PARAM_STR);
$stmtLimit->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
$stmtLimit->bindValue(':items_per_page', (int)$items_per_page, PDO::PARAM_INT);

// Bind date ถ้ามี
if ($searchDate) {
    $stmtLimit->bindValue(':searchDate', $searchDate, PDO::PARAM_STR);
}
if ($searchStatus) {
    $stmtLimit->bindValue(':searchStatus', $searchStatus, PDO::PARAM_STR);
}

$stmtLimit->execute();
$project = $stmtLimit->fetchAll(PDO::FETCH_ASSOC);

$total = $countAll['total'];
$arrProjects = $project;

include './projectlist.php';