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