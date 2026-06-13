<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "app_damkar";

<<<<<<< HEAD
$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
=======
$conn = mysqli_connect($host,$user,$password, $database);
if(!$conn){
>>>>>>> a4740392c1e99c441775feadbf89c51a7554897a

    die("Koneksi gagal");

}

?>