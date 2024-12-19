<?php
session_start(); // Bắt đầu phiên làm việc
include 'db_connection.php'; // Kết nối cơ sở dữ liệu

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_current_price = $_POST['product_current_price'];
    $product_description = $_POST['product_description'];
    $product_detail = $_POST['product_detail'];

    // Xử lý ảnh sản phẩm
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
    move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file);
    
    // Thêm sản phẩm vào cơ sở dữ liệu
    $sql = "INSERT INTO products (product_name, product_price, product_current_price, product_image, product_description, product_detail) 
            VALUES ('$product_name', '$product_price', '$product_current_price', '$target_file', '$product_description', '$product_detail')";

    if (mysqli_query($conn, $sql)) {
        echo "Sản phẩm đã được thêm thành công!";
        header("Location: danhsachsanpham.php");
    } else {
        echo "Lỗi: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sản Phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Thêm Sản Phẩm Mới</h2>
        <form action="them_san_pham.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="product_name" class="form-label">Tên Sản Phẩm</label>
                <input type="text" class="form-control" id="product_name" name="product_name" required>
            </div>
            <div class="mb-3">
                <label for="product_price" class="form-label">Giá Gốc</label>
                <input type="number" class="form-control" id="product_price" name="product_price" required>
            </div>
            <div class="mb-3">
                <label for="product_current_price" class="form-label">Giá Hiện Tại</label>
                <input type="number" class="form-control" id="product_current_price" name="product_current_price" required>
            </div>
            <div class="mb-3">
                <label for="product_image" class="form-label">Ảnh Sản Phẩm</label>
                <input type="file" class="form-control" id="product_image" name="product_image" required>
            </div>
            <div class="mb-3">
                <label for="product_description" class="form-label">Mô Tả</label>
                <textarea class="form-control" id="product_description" name="product_description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="product_detail" class="form-label">Chi Tiết Sản Phẩm</label>
                <textarea class="form-control" id="product_detail" name="product_detail" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Thêm Sản Phẩm</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
