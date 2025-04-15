<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <style>
        .pl-0{
            padding-left: 0;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 pl-0">
                <?php require_once '../sidebar/sidebar.php'; ?>
            </div>
            <div class="col-md-9">
                <div class="container mt-5">
                    <h1 class="mb-4">Project List</h1>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" placeholder="Search by name" value="<?php echo isset($_GET['searchText']) ? htmlspecialchars($_GET['searchText']) : ''; ?>">
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" placeholder="Search by date" value="<?php echo isset($_GET['searchDate']) ? htmlspecialchars($_GET['searchDate']) : ''; ?>">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select">
                                <option value="" <?php echo !isset($_GET['searchStatus']) || $_GET['searchStatus'] === '' ? 'selected' : ''; ?>>Select Status</option>
                                <option value="pending" <?php echo isset($_GET['searchStatus']) && $_GET['searchStatus'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="inprogress" <?php echo isset($_GET['searchStatus']) && $_GET['searchStatus'] === 'inprogress' ? 'selected' : ''; ?>>In Progress</option>
                                <option value="complete" <?php echo isset($_GET['searchStatus']) && $_GET['searchStatus'] === 'complete' ? 'selected' : ''; ?>>Completed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary w-100" onclick="searchProjects()">Search</button>
                        </div>
                        <script>
                            function searchProjects() {
                                
                                const searchText = document.querySelector('input[placeholder="Search by name"]').value;
                                const searchDate = document.querySelector('input[type="date"]').value;
                                const searchStatus = document.querySelector('select').value;

                                const params = new URLSearchParams({ searchText, searchDate, searchStatus }).toString();
                                window.location.href = `getproject.php?${params}`;
                            }
                        </script>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Price</th>
                                <th>Date Estimate</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Example Row -->
                             <?php foreach ($arrProjects as $key => $value) { ?>
                                <tr>
                                    <td><?php echo $value['name'] ?></td>
                                    <td><?php echo $value['date_project'] ?></td>
                                    <td><?php echo '฿ '.$value['price'] ?></td>
                                    <td><?php echo $value['employment_contract'] ?></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">
                                            <form action="getdetailproject.php" method="post" style="display:inline;">
                                                <input type="hidden" name="project_id" value="<?php echo $value['project_id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-search"></i> View
                                                </button>
                                            </form>
                                        </a>
                                    </td>
                                </tr>
                             <?php } ?>
                            <!-- Add more rows dynamically here -->
                        </tbody>
                    </table>
                </div>
                <?php
                // Pagination logic
                $itemsPerPage = 20;
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $page = max($page, 1); // Ensure page is at least 1
                $offset = ($page - 1) * $itemsPerPage;

                // Fetch total number of items (replace with your database query)
                $totalItems = $total; // Example total items, replace with actual count from database
                $totalPages = ceil($totalItems / $itemsPerPage);

                // Fetch items for the current page (replace with your database query)
                $projects = []; // Replace with actual query to fetch projects with LIMIT and OFFSET

                // Display pagination
                echo '<nav>';
                echo '<ul class="pagination justify-content-center">';
                if ($page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="?page=' . ($page - 1) . '">Previous</a></li>';
                }
                for ($i = 1; $i <= $totalPages; $i++) {
                    $active = $i === $page ? 'active' : '';
                    echo '<li class="page-item ' . $active . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                }
                if ($page < $totalPages) {
                    echo '<li class="page-item"><a class="page-link" href="?page=' . ($page + 1) . '">Next</a></li>';
                }
                echo '</ul>';
                echo '</nav>';
                ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
</body>
</html>