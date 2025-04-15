<?php
    require_once '../../db_connection.php';
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

    // Query to get all projects
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $searchText = isset($_GET['searchText']) ? '%'.$_GET['searchText'].'%' : '%%';
    $searchDate = isset($_GET['searchDate']) ? $_GET['searchDate'] : '';
    $searchStatus = isset($_GET['searchStatus']) ? $_GET['searchStatus'] : '';

    $limit = 6;
    $offset = ($page - 1) * $limit;

    $sql = "
        SELECT 
            a.*,
            b.project_image_name
        FROM project a
        LEFT JOIN project_image b 
            ON a.project_id = b.project_id
            AND b.project_image_id = (
                SELECT MIN(project_image_id)
                FROM project_image 
                WHERE project_id = a.project_id
            )
        WHERE 
            a.is_deleted = 0
        AND a.name LIKE :searchText";
    if($searchDate){
        $sql .= " AND a.date_project = :searchDate";
    }
    if($searchStatus){
        $sql .= " AND a.status = :searchStatus";
    }
    $sql .= "
        ORDER BY a.created_at DESC
        LIMIT :limit OFFSET :offset
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':searchText', $searchText, PDO::PARAM_STR);
    if($searchDate){
        $stmt->bindValue(':searchDate', $searchDate, PDO::PARAM_STR);
    }
    if($searchStatus){
        $stmt->bindValue(':searchStatus', $searchStatus, PDO::PARAM_STR);
    }
    $stmt->execute();
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $count = "
        SELECT 
            COUNT(*) as total
        FROM project a
        WHERE 
            a.is_deleted = 0
        AND a.name LIKE :searchText";
    if($searchDate){
        $count .= " AND a.date_project = :searchDate";
    }
    if($searchStatus){
        $count .= " AND a.status = :searchStatus";
    }
    $getCount = $conn->prepare($count);
    $getCount->bindValue(':searchText', $searchText, PDO::PARAM_STR);
    if($searchDate){
        $getCount->bindValue(':searchDate', $searchDate, PDO::PARAM_STR);
    }
    if($searchStatus){
        $getCount->bindValue(':searchStatus', $searchStatus, PDO::PARAM_STR);
    }
    $getCount->execute();
    $totals = $getCount->fetch(PDO::FETCH_ASSOC);
    $total = $totals['total'];

    include './viewproject.php';