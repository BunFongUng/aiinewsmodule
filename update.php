<?php
session_start();
require_once("../../config/User.php");
require_once("../../config/Database.php");

$user = new User();
$database = new Database();
$conn = $database->getConnection();

if(!$user->is_logged_in()) {
    $user->redirect("../index.php");
}

if(isset($_POST["update"])) {
    $required_errors_message = array();
    $upload_errors_message = array();
    $update_id = (isset($_POST["update_id"])) ? $_POST["update_id"] : "";
    $title = (isset($_POST["title"]) && !empty($_POST["title"])) ? $_POST["title"] : "";
    $old_image = isset($_POST["old_image"]) ? $_POST["old_image"] : "";
    $post_date = (isset($_POST["post_date"]) && !empty($_POST["post_date"])) ? $_POST["post_date"] : "";
    $description = (isset($_POST["desc"]) && !empty($_POST["desc"])) ? $_POST["desc"] : "";
    $contents = (isset($_POST["contents"]) && !empty($_POST["contents"])) ? $_POST["contents"] : "";

    $temp_folder = $_FILES["news_image"]["tmp_name"];
    $image_type = $_FILES["news_image"]["type"];
    $image_name = $_FILES["news_image"]["name"];
    $folder_upload = "../uploads/";

    if($title == "") {
        $required_errors_message["title_required"] = "Title Filed Required!";
    }

    if($post_date == "") {
        $required_errors_message["post_date_required"] = "Post Date Field Required!";
    }

    if($description == "") {
        $required_errors_message["description_required"] = "Description Field Required!";
    }

    if($contents == "") {
        $required_errors_message["contents_required"] = "Contents Field Required!";
    }

    if(empty($required_errors_message)) {
        if(empty($_FILES["news_image"]["name"])) {
            $sql_update = "UPDATE news SET title = :title, image = :old_image, post_date = :post_date, des = :description, content = :content WHERE id = :update_id";
            $stmt = $conn->prepare($sql_update);
            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":old_image", $old_image);
            $stmt->bindParam(":post_date", $post_date);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":content", $contents);
            $stmt->bindParam(":update_id", $update_id);
            if($stmt->execute()) {
                $user->redirect("index.php");
            }
        } else {
            if(($image_type != "image/jpg") && ($image_type !="image/jpeg") && ($image_type !="image/png") && ($image_type !="image/gif")) {
                $upload_errors_message["extension"] = "Sorry, Only JPG, JPEG, PNG, GIF are allow!";
            } else {
                if(move_uploaded_file($temp_folder, $folder_upload.$image_name)) {
                    $sql_update_with_new_image = "UPDATE news SET title = :title, image = :image_name, post_date = :post_date, des = :description, content =:contents WHERE id = :update_id";
                    $stmt2 = $conn->prepare($sql_update_with_new_image);
                    $stmt2->bindParam(":title", $title);
                    $stmt2->bindParam(":image_name", $image_name);
                    $stmt2->bindParam(":post_date", $post_date);
                    $stmt2->bindParam(":description", $description);
                    $stmt2->bindParam(":contents", $contents);
                    $stmt2->bindParam(":update_id", $update_id);
                    if($stmt2->execute()) {
                        $user->redirect("index.php");
                    }
                }
            }
        }
    }
}