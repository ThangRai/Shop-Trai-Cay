<?php
session_start();
include 'db_connection.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    
    // Xóa sản phẩm
    $sql = "DELETE FROM products WHERE id = '$product_id'";
    if (mysqli_query($conn, $sql)) {
        echo "Sản phẩm đã được xóa!";
        header("Location: danhsachsanpham.php");
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
}
?>
