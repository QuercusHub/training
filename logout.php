<?php
session_start();

require_once 'function.php';
$_SESSION["auth"] = false;
unset($_SESSION["user"]);

redirect_to("page_login.php");