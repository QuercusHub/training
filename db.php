<?php

function getConnection(){
  
    $host = 'localhost';
    $dbname = 'test';
    $charset = 'utf8';
    $user = 'root';
    $password = '';
    
    $opt = [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    $db = new PDO($dsn, $user, $password, $opt);
    
    return $db;
  
    }
