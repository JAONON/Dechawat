<?php

// นับจำนวนแต่ละสถานะ
$success = 0;
$pending = 0;
foreach ($arrProjects as $project) {
    if ($project['status'] === 'complete') {
        $success++;
    } else {
        $pending++;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Project Status Graph</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid"></div>
        <div class="row">
            <div class="col-md-3 pl-0">
                <?php require_once '../sidebar/sidebar.php'; ?>
            </div>
            <div class="col-md-9">
                <h2 class="mt-4">กราฟแสดงจำนวน Project ที่ Success และยังไม่ Success</h2>
                <form method="get" class="mb-3">
                    <label for="filter">แสดงข้อมูล:</label>
                    <select name="filter" id="filter" class="form-select w-auto d-inline-block" onchange="this.form.submit()">
                        <option value="all" <?php if ($_GET['filter'] ?? 'all' === 'all') echo 'selected'; ?>>ทั้งหมด</option>
                        <option value="this_month" <?php if (($_GET['filter'] ?? '') === 'this_month') echo 'selected'; ?>>เดือนนี้</option>
                        <option value="last_month" <?php if (($_GET['filter'] ?? '') === 'last_month') echo 'selected'; ?>>เดือนที่แล้ว</option>
                        <option value="last_7_days" <?php if (($_GET['filter'] ?? '') === 'last_7_days') echo 'selected'; ?>>7 วันที่แล้ว</option>
                    </select>
                </form>
                <?php
                // ฟังก์ชันช่วยสำหรับการกรองวันที่
                function filterByDate($project, $filter) {
                    if (!isset($project['created_at'])) return false;
                    $date = strtotime($project['created_at']);
                    $now = strtotime(date('Y-m-d'));
                    switch ($filter) {
                        case 'this_month':
                            return date('Y-m', $date) === date('Y-m');
                        case 'last_month':
                            return date('Y-m', $date) === date('Y-m', strtotime('first day of last month'));
                        case 'last_7_days':
                            return $date >= strtotime('-7 days', $now);
                        default:
                            return true;
                    }
                }

                $filter = $_GET['filter'] ?? 'all';
                $success = 0;
                $pending = 0;
                $totalCompletePrice = 0;
                
                foreach ($arrProjects as $project) {
                    $isFiltered = filterByDate($project, $filter);
                    if ($project['status'] === 'complete' && $isFiltered) {
                        $success++;
                        $totalCompletePrice += isset($project['price']) ? floatval($project['price']) : 0;
                    } elseif ($project['status'] !== 'complete' && $isFiltered) {
                        $pending++;
                    }
                }
                ?>
                <div class="alert alert-success">
                    <strong>สรุปราคางานที่เสร็จแล้ว:</strong> <?php echo number_format($totalCompletePrice, 2); ?> บาท
                </div>
                <canvas id="statusChart" width="400" height="200"></canvas>
                <script>
                    const ctx = document.getElementById('statusChart').getContext('2d');
                    const statusChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Success', 'Not Success'],
                            datasets: [{
                                label: 'จำนวนโครงการ',
                                data: [<?php echo $success; ?>, <?php echo $pending; ?>],
                                backgroundColor: [
                                    'rgba(54, 162, 235, 0.7)',
                                    'rgba(255, 99, 132, 0.7)'
                                ],
                                borderColor: [
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 99, 132, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: { beginAtZero: true }
                            }
                        }
                    });
                </script>
            </div>
        </div>
</body>
</html>