<?php
require_once "db.php";
require_once "function.php";
session_start();

$email = $_POST['email'];
$pass = password_hash($_POST['pass'], PASSWORD_BCRYPT);

$user = get_user_by_email($email);

if ($user){
    set_flash_message('message', 'Этот <strong>Email</strong> уже занят другим пользователем, введите другой адрес!');
    redirect_to('page_register.php');
}

add_user($email, $pass);














