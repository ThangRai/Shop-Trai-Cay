<?php
include 'db_connection.php'; // Kết nối CSDL

// Kiểm tra `id` từ URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Xóa bản ghi trong CSDL
    $query = "DELETE FROM contact_info WHERE id = $id";
    mysqli_query($conn, $query);
}

// Quay lại trang danh sách
header("Location: thongtinlienhe.php");
exit();
?>
