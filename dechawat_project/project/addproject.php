<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Add Project</title>
    <style>
        .preview img {
            max-width: 150px;
            margin: 5px;
        }
        .sidebar{
            height: 100%;
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
                <h1>Add New Project</h1>
                <form action="saveproject.php" method="post" enctype="multipart/form-data">
                    <label for="project_name">Project Name:</label><br>
                    <input class="form-control" type="text" id="project_name" name="project_name" required><br>

                    <label for="project_description">Project Description:</label><br>
                    <textarea class="form-control" id="project_description" name="project_description" rows="4" cols="50" required></textarea><br>
                    
                    <label for="project_price">Project Price:</label><br>
                    <input class="form-control" type="number" id="project_price" name="project_price" required><br>

                    <label for="contract">Employment Contract:</label><br>
                    <input class="form-control" type="file" id="contact_images" name="contact_images[]" multiple accept="image/*" onchange="previewImages3()"><br>
                    <div class="preview" id="contact_preview"></div>

                    <label for="plan_images">Plan Images:</label><br>
                    <input class="form-control" type="file" id="plan_images" name="plan_images[]" multiple accept="image/*" onchange="previewImages()"><br>
                    <div class="preview" id="plan_preview"></div>

                    <label for="blueprint_images">Project Images:</label><br>
                    <input class="form-control" type="file" id="blueprint_images" name="blueprint_images[]" multiple accept="image/*" onchange="previewImages2()"><br>
                    <div class="preview" id="image_preview"></div>

                    <script>
                        document.querySelector('form').addEventListener('submit', function(event) {
                            const projectName = document.getElementById('project_name').value.trim();
                            const projectDescription = document.getElementById('project_description').value.trim();
                            const projectPrice = document.getElementById('project_price').value.trim();
                            const contract = document.getElementById('contract').value.trim();
                            const projectDate = document.getElementById('project_date').value.trim();
                            const blueprintImages = document.getElementById('blueprint_images').files;

                            if (!projectName || !projectDescription || !projectPrice || !contract || !projectDate) {
                                alert('Please fill out all required fields.');
                                event.preventDefault();
                                return;
                            }

                            if (blueprintImages.length === 0) {
                                alert('Please upload at least one blueprint image.');
                                event.preventDefault();
                                return;
                            }

                            for (const file of blueprintImages) {
                                const fileType = file.type;
                                if (fileType !== 'image/png' && fileType !== 'image/jpeg') {
                                    alert('Only PNG and JPG files are allowed for blueprint images.');
                                    event.preventDefault();
                                    return;
                                }
                            }
                        });
                    </script>

                    <label for="project_date">Date:</label><br>
                    <input class="form-control" type="date" id="project_date" name="project_date" required><br>
                    <div id="status_container">
                        <div class="form-group">
                            <label for="project_status_1">Project Status:</label>
                            <div class="row">
                                <div class="col-md-9">
                                    <input class="form-control" type="text" id="project_status_1" name="project_status[]" required>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-secondary" onclick="removeStatus(this)">-</button>
                                </div>
                                <div class="form-group mt-3 col-md-9">
                                    <label for="date_status">Status Date:</label>
                                    <input class="form-control" type="date" id="date_status" name="date_status[]" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary mt-4" onclick="addStatus()">+ Add Status</button>
                        
                    <script>
                        let statusCount = 1;

                        function addStatus() {
                            statusCount++;
                            const statusContainer = document.getElementById('status_container');
                            const newStatusDiv = document.createElement('div');
                            newStatusDiv.classList.add('form-group', 'mt-4');
                            newStatusDiv.innerHTML = `
                            <div class="row">
                                <div class="col-md-9">
                                    <label for="project_status_${statusCount}">Project Status:</label>
                                    <input class="form-control" type="text" id="project_status_${statusCount}" name="project_status[]" required>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="button" class="btn btn-secondary" onclick="removeStatus(this)">-</button>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-9">
                                        <label for="date_status_${statusCount}">Status Date:</label>
                                        <input class="form-control" type="date" id="date_status_${statusCount}" name="date_status[]" required>
                                    </div>
                                </div>
                            </div>
                            `;
                            statusContainer.appendChild(newStatusDiv);
                        }

                        function removeStatus(button) {
                            const statusContainer = document.getElementById('status_container');
                            const statusGroups = statusContainer.querySelectorAll('.form-group');
                            if (statusGroups.length > 1) {
                                const statusGroup = button.closest('.form-group');
                                statusGroup.remove();
                            } else {
                                alert('At least one status is required.');
                            }
                        }
                    </script>
                    <div class="col-md-3" style="margin: 10px 0 10px 0;">
                        <button class="form-control" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewImages() {
            const preview = document.getElementById('plan_preview');
            preview.innerHTML = '';
            const files = document.getElementById('plan_images').files;

            for (const file of files) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        }

        function previewImages2() {
            const preview = document.getElementById('image_preview');
            preview.innerHTML = '';
            const files = document.getElementById('blueprint_images').files;

            for (const file of files) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        }

        function previewImages3() {
            const preview = document.getElementById('contact_preview');
            preview.innerHTML = '';
            const files = document.getElementById('contact_images').files;

            for (const file of files) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>