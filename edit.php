<?php
require_once 'function.php';
session_start();
if (isset($_POST["edit"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $job = $_POST["job"];
    $phone = $_POST["phone"];
    $adress = $_POST["adress"];
    update_user_profile($id, $name, $job, $phone, $adress);
}

if (isset($_POST["security"])) {
    $id = $_POST["id"];
    $email = $_POST["email"];
    $pass = ($_POST['pass']);
    $confirm_password = $_POST["confirm_password"];


    if ($pass === $confirm_password) {
        update_security_profile($id, $email, $pass);
    } else {
        set_flash_message("edit", "Пароли не совпадают");
        redirect_to("users.php");
    }
}

if (isset($_POST["btn_avatar"])) {
    $path = 'uploads/' . time() . $_FILES["avatar"]["name"];
    move_uploaded_file($_FILES["avatar"]["tmp_name"], $path);

    save_path_avatar($_POST["id"], $path);
}