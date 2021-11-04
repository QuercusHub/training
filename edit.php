<?php
require_once 'function.php';
session_start();
if (isset($_POST)){
    $id = $_POST["id"];
    $name = $_POST["name"];
    $job = $_POST["job"];
    $phone = $_POST["phone"];
    $adress = $_POST["adress"];
    update_user_profile($id, $name, $job, $phone, $adress);
}

redirect_to("users.php");