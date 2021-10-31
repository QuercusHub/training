<?php
require_once "db.php";
require_once "function.php";
session_start();

$email = $_POST['email'];
$pass = $_POST['pass'];

$user = get_user_by_email($email);

if (!$user){
    set_flash_message('message', 'Пользователь не найден');
    redirect_to('page_login.php');
}

login($email, $pass);


