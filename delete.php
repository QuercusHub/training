<?php
require_once 'function.php';
session_start();
delete_user($_GET["id"]);