<?php
require_once 'function.php';
session_start();


if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $job = $_POST["job"];
    $phone = $_POST["phone"];
    $adress = $_POST["adress"];
    $email = $_POST["email"];
    $pass = password_hash($_POST['pass'], PASSWORD_BCRYPT);
    $status = $_POST["status"];
    $vk = $_POST["vk"];
    $telegram = $_POST["telegram"];
    $instagram = $_POST["instagram"];

    $path = 'uploads/' . time() . $_FILES["avatar"]["name"];
    move_uploaded_file($_FILES["avatar"]["tmp_name"], $path);

    $user = get_user_by_email($email);

    if ($user){
        set_flash_message('edit', 'Этот <strong>Email</strong> уже занят другим пользователем, введите другой адрес!');
        redirect_to('users.php');
    }else{
        create_user($name, $job, $phone, $adress, $email, $pass, $status, $path, $vk, $telegram, $instagram);
    }
}
if (isset($_POST["set_status"])){
    set_status($_POST["id"], $_POST["status"]);

}

