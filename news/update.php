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
    $title = (isset($_POST["title"]) && !empty($_POST["title"])) ? $_POST["title"] : "";
    $old_image = isset($_POST["old_image"]) ? $_POST["old_image"] : "";
}