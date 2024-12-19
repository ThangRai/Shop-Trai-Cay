<?php
session_start();
include 'db_connection.php'; // Kết nối cơ sở dữ liệu

// Lấy thông tin sản phẩm từ ID
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM products WHERE id = '$product_id'");
    $product = mysqli_fetch_assoc($result);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_current_price = $_POST['product_current_price'];
    $product_description = $_POST['product_description'];
    $product_detail = $_POST['product_detail'];

    // Xử lý ảnh nếu có thay đổi
    if ($_FILES['product_image']['name'] != "") {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
        move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file);
        $product_image = $target_file;
    } else {
        $product_image = $product['product_image']; // Giữ ảnh cũ nếu không thay đổi
    }
    
    // Cập nhật sản phẩm
    $sql = "UPDATE products SET product_name='$product_name', product_price='$product_price', product_current_price='$product_current_price', 
            product_image='$product_image', product_description='$product_description', product_detail='$product_detail' WHERE id='$product_id'";

    if (mysqli_query($conn, $sql)) {
        echo "Sản phẩm đã được cập nhật!";
        header("Location: danhsachsanpham.php");
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Sản Phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Sửa Sản Phẩm</h2>
        <form action="sua_san_pham.php?id=<?php echo $product['id']; ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="product_name" class="form-label">Tên Sản Phẩm</label>
                <input type="text" class="form-control" id="product_name" name="product_name" value="<?php echo $product['product_name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="product_price" class="form-label">Giá Gốc</label>
                <input type="number" class="form-control" id="product_price" name="product_price" value="<?php echo $product['product_price']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="product_current_price" class="form-label">Giá Hiện Tại</label>
                <input type="number" class="form-control" id="product_current_price" name="product_current_price" value="<?php echo $product['product_current_price']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="product_image" class="form-label">Ảnh Sản Phẩm</label>
                <input type="file" class="form-control" id="product_image" name="product_image">
                <img src="<?php echo $product['product_image']; ?>" width="100" alt="Current Image">
            </div>
            <div class="mb-3">
                <label for="product_description" class="form-label">Mô Tả</label>
                <textarea class="form-control" id="product_description" name="product_description" rows="3" required><?php echo $product['product_description']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="product_detail" class="form-label">Chi Tiết</label>
                <textarea class="form-control" id="product_detail" name="product_detail" rows="5" required><?php echo $product['product_detail']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-warning">Cập Nhật Sản Phẩm</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
