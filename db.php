<?php
/* Database connection settings */
//$host = 'localhost';
//$user = 'root';
//$pass = '';
//$db = 'elearningsystem';
//$mysqli = new mysqli($host,$user,$pass,$db) or die($mysqli->error);


$server = "localhost";
$user = "root";
$password = "";
$database="elearningsystem";
$connection = mysqli_connect($server,$user,$password,$database) or die ("could not connect to database");
?>