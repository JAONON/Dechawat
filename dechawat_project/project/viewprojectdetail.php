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
    <div class="container-fluid pl-0">
        <div class="row">
            <div class="col-md-3 pl-0">
                <?php require_once '../sidebar/sidebar.php'; ?>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <h2>Project Progress</h2>
                    <ul class="stepper">
                        <?php 
                        foreach ($status as $index => $step) {
                            $stepNumber = $step["status_id"];
                            $num = $index + 1;
                            $isActive = $step["success"] ? 'active' : '';
                            echo "<li class='step-item $isActive'" . ($isActive ? '' : " onclick='updateStep($stepNumber)'") . ">";
                            echo "<div><span class='step-number'>$num</span></div>";
                            echo "<div class='step-title'>Step ".$num.":".$step['status_name']."</div>";
                            echo "</li>";
                        }
                        ?>
                    </ul>
                </div>
                <div class="row">
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
                <style>
                    .stepper {
                        list-style: none;
                        padding: 0;
                        display: flex;
                        justify-content: space-between;
                    }
                    .step-item {
                        text-align: center;
                        flex: 1;
                        position: relative;
                        cursor: pointer;
                    }
                    .step-item::after {
                        content: '';
                        position: absolute;
                        top: 50%;
                        right: 0;
                        width: 100%;
                        height: 2px;
                        background-color: #ddd;
                        z-index: -1;
                    }
                    .step-item.active::after {
                        background-color: #0d6efd;
                    }
                    .step-number {
                        display: inline-block;
                        width: 30px;
                        height: 30px;
                        line-height: 30px;
                        border-radius: 50%;
                        background-color: #ddd;
                        color: #fff;
                        margin-bottom: 5px;
                    }
                    .step-item.active .step-number {
                        background-color: #0d6efd;
                    }
                    .step-title {
                        font-size: 14px;
                    }
                </style>
                <script>
                    function updateStep(stepNumber) {
                        if (confirm('Are you sure you want to update the project to Step ?')) {
                            // Redirect to the update step page
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = 'updatestatus.php';

                            const input = document.createElement('input');

                            input.type = 'hidden';
                            input.name = 'step';
                            input.value = stepNumber;

                            const projectIdInput = document.createElement('input');
                            projectIdInput.type = 'hidden';
                            projectIdInput.name = 'project_id';
                            projectIdInput.value = '<?php echo htmlspecialchars($project_id); ?>';
                            form.appendChild(projectIdInput);

                            form.appendChild(input);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    }
                </script>
            </div>
        </div>
    </div>
</body>
</html>