<?php

$koneksi = mysqli_connect("localhost", "root", "", "app_damkar");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}