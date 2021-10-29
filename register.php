<?php
require_once "db.php";
require_once "function.php";
session_start();

$email = $_POST['email'];
$pass = $_POST['pass'];


if (!$email == 0 and !$pass == '') {
    add_user($email, $pass);
}else{
    set_name_message('message', 'Введите данные!!!');
    redirect_to('public/page_register.php');
}











