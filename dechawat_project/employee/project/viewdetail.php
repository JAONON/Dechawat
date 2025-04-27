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
    .pl-0{
        padding-left: 0;
    }
</style>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 pl-0">
            <?php require '../../sidebar/sidebar.php'; ?>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="row">
                <h2>Project Progress</h2>
                <ul class="stepper">
                    <?php 
                    $statusId = null;
                    foreach ($status as $index => $step) {
                        $stepNumber = $step["status_id"];
                        $num = $index + 1;
                        $isActive = $step["success"] ? 'active' : '';
                        if($step["success"]){$statusId = $step["status_id"];}
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
                    <?php $image_path = "../../asset/image/project/" . $val['project_id'] .'/'. $val['project_image_name']; ?>
                    <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Project Image" style="max-width: 200px; display: block; margin-bottom: 10px;">
                <?php }; ?>
            </div>
            <div class="row">
                <h2>Leave a Comment</h2>
                <form action="comment.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="comment" class="form-label">Your Comment</label>
                        <textarea class="form-control" id="comment" name="comment" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Upload Image</label>
                        <input class="form-control" type="file" id="image" name="image" accept="image/*">
                    </div>
                    <input type="hidden" name="project_id" value="<?php echo htmlspecialchars($project_id); ?>">
                    <input type="hidden" name="status_id" value="<?php echo htmlspecialchars($statusId); ?>">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
            <div class="row">
                <div class="row">
                    <h2>Search Comments by Step</h2>
                    <form method="POST" action="getdetail.php">
                        <div class="mb-3">
                            <label for="stepFilter" class="form-label">Select Step</label>
                            <select class="form-select" id="stepFilter" name="stepFilter" onchange="this.form.submit()">
                                <?php foreach ($status as $key => $step): ?>
                                    <option value="<?php echo htmlspecialchars($step['status_id']); ?>" 
                                        <?php echo (isset($selectStatusSearch) && $selectStatusSearch == $step['status_id']) ? 'selected' : ''; ?>>
                                        Step <?php echo $key + 1 ?>: <?php echo htmlspecialchars($step['status_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <input type="hidden" name="project_id" value="<?php echo htmlspecialchars($project_id); ?>">
                    </form>
                </div>
                <?php
                if (isset($_GET['stepFilter']) && $_GET['stepFilter'] !== '') {
                    $filteredStep = $_GET['stepFilter'];
                    $commentsByStep = array_filter($commentsByStep, function ($key) use ($filteredStep) {
                        return $key == $filteredStep;
                    }, ARRAY_FILTER_USE_KEY);
                }
                ?>
                <h2>Comments by Step</h2>
                <div class="comments-by-step">
                    <?php 
                    // Mock data for subcomments
                    // Mock data for $commentsByStep
                    $commentsByStep = $comments;
                    $subcomments = $subcomments;

                    $findStatudId = array_column($status, 'status_id');
                    $findIndex = array_search($key, $findStatudId);
                    $status_name = $index !== false ? $status[$index]['status_name'] : null;

                    // Display comments with subcomments
                    if($commentsByStep){
                        foreach ($commentsByStep as $stepId => $stepComments) {
                            echo '<div class="step-comments">';
                            echo '<h3>Step ' . $status_name . '</h3>';
                            foreach ($stepComments as $comment) {
                                echo '<div class="comment">';
                                echo '<p><strong>' . htmlspecialchars($comment['username']) . '</strong> <small>(' . htmlspecialchars($comment['timestamp']) . ')</small></p>';
                                echo '<p>' . htmlspecialchars($comment['comment']) . '</p>';
                                if ($comment['image']) {
                                    $image_path = "../asset/image/comment/" . $comment['comment_id'] ."/". $comment['image'];
                                    echo '<img src="' . htmlspecialchars($image_path) . '" alt="Comment Image" style="max-width: 200px; display: block; margin-bottom: 10px;">';
                                }
    
                                // Display subcomments
                                echo '<div class="subcomments">';
                                foreach ($subcomments as $subcomment) {
                                    if ($subcomment['parent_comment_id'] == $comment['comment_id']) {
                                        echo '<div class="subcomment" style="margin-left: 20px; border-left: 2px solid #ddd; padding-left: 10px;">';
                                        echo '<p><strong>' . htmlspecialchars($subcomment['username']) . '</strong> <small>(' . htmlspecialchars($subcomment['timestamp']) . ')</small></p>';
                                        echo '<p>' . htmlspecialchars($subcomment['comment']) . '</p>';
                                        echo '</div>';
                                    }
                                }
                                echo '</div>';

                                echo '<div class="toggle-subcomment" style="margin: 10px 0 10px 0;">';
                                echo '<a href="javascript:void(0);" onclick="toggleSubcommentForm(' . htmlspecialchars($comment['comment_id']) . ')">คอมเม้น</a>';
                                echo '</div>';
                                echo '<div id="subcomment-form-' . htmlspecialchars($comment['comment_id']) . '" class="add-subcomment" style="margin-left: 20px; margin-top: 10px; display: none;">';
                                echo '<form action="subcomment.php" method="POST">';
                                echo '<div class="mb-3">';
                                echo '<label for="subcomment_' . htmlspecialchars($comment['step_id']) . '" class="form-label">Add Subcomment</label>';
                                echo '<textarea class="form-control" id="subcomment_' . htmlspecialchars($comment['step_id']) . '" name="subcomment" rows="2" required></textarea>';
                                echo '</div>';
                                echo '<input type="hidden" name="comment_id" value="' . $comment["comment_id"] . '">';
                                echo '<input type="hidden" name="project_id" value="' . $project_id . '">';
                                echo '<button type="submit" class="btn btn-secondary btn-sm">Submit</button>';
                                echo '</form>';
                                echo '</div>';
                                echo '</div>';

                                echo '<hr>';
                                echo '</div>';
                            }
                        }
                    }else{
                        echo '<p>No comments available for this step.</p>';
                    }
                    ?>
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

                    function toggleSubcommentForm(commentId) {
                        const form = document.getElementById(`subcomment-form-${commentId}`);
                        if (form.style.display === 'none' || form.style.display === '') {
                            form.style.display = 'block';
                        } else {
                            form.style.display = 'none';
                        }
                    }
                </script>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>