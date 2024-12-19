<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'shop';

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}
?>
