<?php

// Pagination setup
$itemsPerPage = 6;
$totalItems = $total;
$totalPages = ceil($totalItems / $itemsPerPage);
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$currentPage = max(1, min($totalPages, $currentPage));
$startIndex = ($currentPage - 1) * $itemsPerPage;

// Filtered projects for current page
$paginatedProjects = array_slice($projects, $startIndex, $itemsPerPage);

// Search filters
$searchName = isset($_GET['searchText']) ? $_GET['searchText'] : '';
$searchDate = isset($_GET['searchDate']) ? $_GET['searchDate'] : '';
$searchStatus = isset($_GET['searchStatus']) ? $_GET['searchStatus'] : '';

if ($searchName || $searchDate || $searchStatus) {
    $paginatedProjects = array_filter($projects, function ($project) use ($searchName, $searchDate, $searchStatus) {
        return (!$searchName || stripos($project['name'], $searchName) !== false) &&
               (!$searchDate || $project['date_project'] === $searchDate) &&
               (!$searchStatus || $project['status'] === $searchStatus);
    });
    $totalItems = count($paginatedProjects);
    $totalPages = ceil($totalItems / $itemsPerPage);
    $paginatedProjects = array_slice($paginatedProjects, $startIndex, $itemsPerPage);
}
?>
<style>
    .sidebar{
        height: 100%;
    }
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-3">
            <?php require_once '../../sidebar/sidebar.php'; ?>
        </div>

        <!-- Main Content -->
        <div class="col-9">
            <h4>Project List</h4>

            <!-- Search Form -->
            <form method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="searchText" class="form-control" placeholder="Search by name" value="<?= htmlspecialchars($searchName) ?>">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="searchDate" class="form-control" value="<?= htmlspecialchars($searchDate) ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="searchStatus" class="form-control">
                            <option value="">Select Status</option>
                            <option value="pending" <?= $searchStatus === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="completed" <?= $searchStatus === 'completed' ? 'selected' : '' ?>>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </form>

            <!-- Project Cards -->
            <div class="row">
                <?php foreach ($projects as $project): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="<?= '../../asset/image/'.$project['project_id'].'/'.htmlspecialchars($project['project_image_name'] ?: 'default_image.png') ?>" class="card-img-top" alt="Project Image">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($project['name']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($project['description']) ?></p>
                                <form method="POST" action="getdetail.php">
                                    <input type="hidden" name="project_id" value="<?= $project['project_id'] ?>">
                                    <button type="submit" class="btn btn-primary">View</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <nav>
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&searchText=<?= urlencode($searchName) ?>&searchDate=<?= urlencode($searchDate) ?>&searchStatus=<?= urlencode($searchStatus) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>