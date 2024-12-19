<?php
session_start();
include 'db_connection.php'; // Kết nối cơ sở dữ liệu

// Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    echo "Bạn cần đăng nhập để xem đơn hàng!";
    exit;
}

// Lấy ID người dùng từ session
$user_id = $_SESSION['user_id'];

// Truy vấn lấy tất cả đơn hàng của người dùng
$sql = "SELECT * FROM orders WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);

// Kiểm tra nếu có đơn hàng
if (mysqli_num_rows($result) > 0) {
    echo "<div class='container mt-5'>";
    echo "<h2 class='text-center mb-4'>Danh Sách Đơn Hàng Của Bạn</h2>";
    echo "<table class='table table-striped table-bordered'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>ID Đơn Hàng</th>";
    echo "<th>Ngày Tạo</th>";
    echo "<th>Tổng Giá</th>";
    echo "<th>Trạng Thái</th>";
    echo "<th>Chi Tiết</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    // Lặp qua tất cả các đơn hàng và hiển thị chúng
    while ($order = mysqli_fetch_assoc($result)) {
        $cart_items = json_decode($order['cart_items'], true);
        $total_price = $order['total_price'];
        $order_status = $order['order_status'];
        $order_id = $order['id'];
        $created_at = $order['created_at'];

        // Hiển thị thông tin đơn hàng
        echo "<tr>";
        echo "<td>#{$order_id}</td>";
        echo "<td>{$created_at}</td>";
        echo "<td>" . number_format($total_price, 2) . " VND</td>";
        echo "<td>{$order_status}</td>";
        echo "<td><a href='order_details.php?id={$order_id}' class='btn btn-info btn-sm'>Xem Chi Tiết</a></td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
    echo "</div>";
} else {
    echo "<p class='text-center'>Không có đơn hàng nào được tìm thấy.</p>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Đơn Hàng</title>
    <!-- Thêm Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
        }

        .table th, .table td {
            text-align: center;
        }

        .btn-info {
            color: #fff;
            background-color: #17a2b8;
            border-color: #17a2b8;
        }

        .btn-info:hover {
            background-color: #138496;
            border-color: #117a8b;
        }

        h2 {
            color: #333;
            font-size: 1.75rem;
        }

        p {
            font-size: 1.25rem;
            color: #666;
        }

        @media (max-width: 767px) {
            .table th, .table td {
                font-size: 12px;
                padding: 5px;
            }

            h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<!-- Nội dung trang PHP sẽ được chèn vào đây -->

<!-- Thêm Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
