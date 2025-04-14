<?php
require_once '../db_connection.php';
session_start();

// 🔸 ฟังก์ชันดึงข้อมูลสมาชิก
function getMemberData(PDO $conn, int $id): ?array {
    $sql = "SELECT * FROM member WHERE member_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

// 🔹 ดึง ID สมาชิกจาก session
$memberId = $_SESSION['member']['member_id'] ?? null;

if (!$memberId) {
    die('ไม่พบข้อมูลสมาชิกใน session');
}

// 🔸 ดึงข้อมูลสมาชิกจากฐานข้อมูล
$memberData = getMemberData($conn, $memberId);

// 🔹 ส่งต่อไปยังหน้าที่จะแสดงผล
include '../profile/profile.php';
