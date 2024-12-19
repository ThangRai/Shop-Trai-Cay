<?php
session_start(); // Bắt đầu phiên làm việc
include 'db_connection.php'; // Kết nối cơ sở dữ liệu

// Lấy danh sách sản phẩm từ cơ sở dữ liệu
$products = mysqli_query($conn, "SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Sản Phẩm</title>
    <!-- Thêm Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #343a40;
        }

        .table img {
            border-radius: 8px;
        }

        .btn {
            margin-right: 5px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .btn-warning {
            color: #fff;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        .btn-danger:hover {
            background-color: #bd2130;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            td img {
                width: 80px;
            }

            table {
                font-size: 14px;
            }

            .btn {
                padding: 5px 10px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Danh Sách Sản Phẩm</h2>
        
        <!-- Thêm Sản phẩm mới -->
        <a href="them_san_pham.php" class="btn btn-primary mb-3">Thêm Sản Phẩm Mới</a>
        
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Tên Sản Phẩm</th>
                        <th>Giá Gốc</th>
                        <th>Giá Hiện Tại</th>
                        <th>Ảnh</th>
                        <th>Mô Tả</th>
                        <th>Chi Tiết</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product = mysqli_fetch_assoc($products)) { ?>
                        <tr>
                            <td><?php echo $product['product_name']; ?></td>
                            <td><?php echo number_format($product['product_price'], 2); ?> VND</td>
                            <td><?php echo number_format($product['product_current_price'], 2); ?> VND</td>
                            <td><img src="<?php echo $product['product_image']; ?>" width="100" alt="Product Image"></td>
                            <td><?php echo $product['product_description']; ?></td>
                            <td><?php echo $product['product_detail']; ?></td>
                            <td>
                                <a href="sua_san_pham.php?id=<?php echo $product['id']; ?>" class="btn btn-warning btn-sm">Sửa</a>
                                <a href="xoa_san_pham.php?id=<?php echo $product['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Thêm Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
