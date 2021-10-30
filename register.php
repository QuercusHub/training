<?php
require_once "function.php";
session_start();

$email = $_POST['email'];
$pass = password_hash($_POST['pass'], PASSWORD_BCRYPT);

$user = get_user_by_email($email);

if ($user){
    set_name_message('message', 'Этот <strong>Email</strong> уже занят другим пользователем, введите другой адрес!');
    redirect_to('public/page_register.php');
}else {
    add_user($email, $pass);
    //redirect_to('public/page_login.php');
}












