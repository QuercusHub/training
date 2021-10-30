<?php
require_once "function.php";
session_start();

$email = $_POST['email'];
$pass = $_POST['pass'];

$user = get_user_by_email($email);

if (!$user){
    set_name_message('message', 'Пользователь не найден');
    redirect_to('public/page_login.php');
}else{
    login($email, $pass);
}

