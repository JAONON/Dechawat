<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<form method="post" action="save_profile.php" enctype="multipart/form-data">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <?php require_once '../sidebar/sidebar.php'; ?>
            </div>
            <div class="col-md-9 mt-5">
                <div class="card" style="max-width: 100%; margin: auto;">
                    <div class="row g-0">
                        <div class="col-md-4" style="padding: 20px;">
                            <div style="position: relative; display: inline-block;">
                                <img id="profileImage" src="<?php echo $memberData['member_logo'] ? '../asset/image/'.$_SESSION['member']['member_id'].'/'.$memberData['member_logo'] : '../asset/image/master/avatar.png' ?>" class="img-fluid rounded-start" alt="Profile Picture" style="width: 300px; height: auto; cursor: pointer;">
                                <div id="hoverOverlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); display: none; justify-content: center; align-items: center; color: white; font-size: 24px; cursor: pointer; pointer-events: none;">
                                    <i class="bi bi-camera"></i> Change Picture
                                </div>
                                <input type="file" id="fileInput" name="profile_picture" style="display: none;" accept="image/*">
                                <input type="hidden" name="member_logo" value="<?php echo $memberData['member_logo'] ?? '' ?>">
                            </div>
                            <script>
                                const profileImage = document.getElementById('profileImage');
                                const hoverOverlay = document.getElementById('hoverOverlay');
                                const fileInput = document.getElementById('fileInput');

                                profileImage.addEventListener('mouseover', () => {
                                    hoverOverlay.style.display = 'flex';
                                    hoverOverlay.style.pointerEvents = 'auto';
                                });

                                profileImage.addEventListener('mouseout', () => {
                                    hoverOverlay.style.display = 'none';
                                    hoverOverlay.style.pointerEvents = 'none';
                                });

                                hoverOverlay.addEventListener('click', () => {
                                    fileInput.click();
                                });

                                fileInput.addEventListener('change', (event) => {
                                    const file = event.target.files[0];
                                    if (file) {
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            profileImage.src = e.target.result;
                                        };
                                        reader.readAsDataURL(file);
                                    }
                                });
                            </script>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">Profile</h5>
                                <div class="mb-3">
                                    <label for="name" class="form-label"><strong>Name:</strong></label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $memberData["name"] ?? '' ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="lastname" class="form-label"><strong>Lastname:</strong></label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $memberData["lastname"] ?? '' ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="birthday" class="form-label"><strong>Birthday Date:</strong></label>
                                    <input type="date" class="form-control" id="birthday" name="birthday" value="<?php echo $memberData['birthday_date'] ?? '' ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="number" class="form-label"><strong>Number No:</strong></label>
                                    <input type="text" class="form-control" id="number" name="number" value="<?php echo $memberData['number_id'] ?>" placeholder="12345678901234">
                                </div>
                                <div class="mb-3">
                                    <label for="gender" class="form-label"><strong>Gender:</strong></label>
                                    <input type="radio" class="form-check-input" id="gender" name="gender" value="male" <?php echo $memberData['gender'] == "male" ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="radioDisabled">Male</label>
                                    <input type="radio" class="form-check-input" id="gender" name="gender" value="female" <?php echo $memberData['gender'] == "female" ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="radioDisabled">Female</label>
                                </div>
                                <div class="mb-3">
                                    <label for="phonenumber" class="form-label"><strong>PhoneNumber:</strong></label>
                                    <input type="text" class="form-control" id="phonenumber" name="phonenumber" value="<?php echo $memberData['phone_number'] ?? '' ?>" placeholder="0987654321">
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>