<?php
// koneksi.php
$host = "localhost";
$user = "root"; 
$pass = ""; 
$db   = "db_casp";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi database jebol: " . mysqli_connect_error());
}
?>
