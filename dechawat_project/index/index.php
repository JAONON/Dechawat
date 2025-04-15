<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #f4f4f4;
            padding: 15px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin: 10px 0;
        }
        .sidebar ul li a {
            text-decoration: none;
            color: #333;
            display: block;
            padding: 8px;
            border-radius: 4px;
        }
        .sidebar ul li a:hover {
            background-color: #ddd;
        }
        .sidebar ul li ul {
            margin-left: 15px;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 0.25rem;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .pagination{
            justify-content: center;
        }
        .pl-0{
            padding-left: 0;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 pl-0">
                <?php require_once '../sidebar/sidebar.php'; ?>
            </div>
            <div class="col-md-9">
                <div class="content">
                    <?php
                    $totalItems = 20;
                    $itemsPerPage = 10;
                    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $startIndex = ($currentPage - 1) * $itemsPerPage;
                    $endIndex = min($startIndex + $itemsPerPage, $totalItems);

                    echo '<div class="card-container" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 20px;">';
                    for ($i = $startIndex + 1; $i <= $endIndex; $i++) {
                        echo '<div class="card">';
                        echo '<h2>Card ' . $i . '</h2>';
                        echo '<p>This is card number ' . $i . '.</p>';
                        echo '</div>';
                    }
                    echo '</div>';

                    $totalPages = ceil($totalItems / $itemsPerPage);
                    echo '<div class="pagination" style="margin-top: 20px; text-align: center;">';
                    for ($page = 1; $page <= $totalPages; $page++) {
                        if ($page == $currentPage) {
                            echo '<span style="margin: 0 5px; font-weight: bold;">' . $page . '</span>';
                        } else {
                            echo '<a href="?page=' . $page . '" style="margin: 0 5px; text-decoration: none; color: #007bff;">' . $page . '</a>';
                        }
                    }
                    echo '</div>';
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>