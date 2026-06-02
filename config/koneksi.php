<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "app_damkar";

$conn = mysqli_connect(
    $host,
    $user,
    $password,
    $database
);

if(!$conn){

    die("Koneksi gagal");

}

?>