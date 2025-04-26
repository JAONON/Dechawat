<?php
    require_once '../../db_connection.php';
    session_start();

    // Get project_id from POST
    $project_id = $_POST['project_id'];

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT * FROM project WHERE project_id = :project_id");
    $stmt->bindValue(":project_id", $project_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $img = $conn->prepare("SELECT * FROM project_image WHERE project_id = :project_id");
    $img->bindValue(":project_id", $project_id);
    $img->execute();
    $image = $img->fetchAll(PDO::FETCH_ASSOC);

    // get status
    $sql = "SELECT * FROM status WHERE project_id = :project_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
    $stmt->execute();
    $projectStatus = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // get comment
    $sql = "
        SELECT 
            comment.*, 
            comment_img.image_name ,
            member.name,
            member.lastname
        FROM comment 
        LEFT JOIN comment_img ON comment_img.comment_id = comment.comment_id
        LEFT JOIN member ON comment.member_id = member.member_id
        WHERE 
            project_id = :project_id
        ORDER BY comment.created_at DESC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
    $stmt->execute();
    $Arrcomment = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $comment = [];
    foreach ($Arrcomment as $key => $value) {
            $comment[$value["status_id"]][] = [
                'comment_id' => $value['comment_id'],
                'username' => $value['name']." ".$value['lastname'],
                'comment' => $value['message'],
                'image' => $value['image_name'],
                'timestamp' => $value['created_at'],
                'step_id' => $value['status_id'],
            ];
    }
    
    // get sub_comment
    $sql = "
        SELECT 
            sub_comment.*,
            member.name,
            member.lastname
        FROM sub_comment 
        JOIN member ON sub_comment.member_id = member.member_id
        WHERE 
            project_id = :project_id
        ORDER BY created_at ASC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
    $stmt->execute();
    $ArrSubcomment = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $subcomment = [];
    foreach ($ArrSubcomment as $key2 => $value2) {
        $subcomment[] = [
            "sub_comment_id" => $value2["sub_comment_id"],
            "parent_comment_id" => $value2["comment_id"],
            "username" => $value2["name"]." ".$value2["lastname"],
            "comment" => $value2["message"],
            "timestamp" => $value2["created_at"],
        ];
    }
    $projectData = $result;
    $images = $image;
    $status = $projectStatus;
    $comments = $comment;
    $subcomments = $subcomment;

    include './viewdetail.php';