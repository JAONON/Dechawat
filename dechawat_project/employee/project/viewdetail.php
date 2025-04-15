<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    .sidebar{
        height: 100%;
    }
</style>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <?php require '../../sidebar/sidebar.php'; ?>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <h1 class="mt-4"><?php echo htmlspecialchars($project['name']); ?></h1>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($project['description']); ?></p>
            <p><strong>Price:</strong> <?php echo number_format($project['price'], 2); ?> THB</p>
            <p><strong>Employment Contract:</strong> <?php echo htmlspecialchars($project['employment_contract']); ?> months</p>
            <p><strong>Start Date:</strong> <?php echo htmlspecialchars($project['date_project']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($project['status']); ?></p>
            <p><strong>Created At:</strong> <?php echo htmlspecialchars($project['created_at']); ?></p>

            <h3 class="mt-4">Project Images</h3>
            <div class="row">
                <?php foreach ($images as $image): ?>
                    <div class="col-md-4 mb-3">
                        <img src="../../asset/image/<?php echo $image['project_id'] . '/' . htmlspecialchars($image['project_image_name']); ?>" 
                             class="img-fluid rounded" alt="Project Image">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>