<?php

/*
===============================
* connection to the database server
*
===============================
*/

/*Localhost server info*/
$dsn = 'mysql:host=localhost;dbname=blogger';
$user = 'root';
$pass = '';
$option = array(
  PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);

try{
  $conn = new PDO ($dsn, $user, $pass, $option);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $error){
  echo 'field to connect' . $error->getMessage();
}
