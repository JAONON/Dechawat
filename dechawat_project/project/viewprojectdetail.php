<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Details</title>
</head>
<style>
    .pl-0{
        padding-left: 0;
    }
    .sidebar{
        height: 100%;
    }
</style>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 pl-0">
                <?php require_once '../sidebar/sidebar.php'; ?>
            </div>
            <div class="col-md-9">
                <h1>Project Details</h1>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($projectData['name']); ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($projectData['description']); ?></p>
                <p><strong>Price:</strong> <?php echo number_format($projectData['price']); ?> THB</p>
                <p><strong>Employment Contract:</strong> <?php echo htmlspecialchars($projectData['employment_contract']); ?></p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($projectData['date_project']); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($projectData['status']); ?></p>

                <h2>Project Images</h2>
                <?php foreach ($image as $val){ ?>
                    <?php $image_path = "../asset/image/project/" . $val['project_id'] .'/'. $val['project_image_name']; ?>
                    <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Project Image" style="max-width: 200px; display: block; margin-bottom: 10px;">
                <?php }; ?>
            </div>
        </div>
    </div>
</body>
</html>