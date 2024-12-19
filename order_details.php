<?php
session_start();
include 'db_connection.php'; // Kết nối cơ sở dữ liệu

// Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    echo "Bạn cần đăng nhập để xem chi tiết đơn hàng!";
    exit;
}

$order_id = $_GET['id']; // Lấy ID đơn hàng từ URL
$user_id = $_SESSION['user_id'];

// Truy vấn lấy đơn hàng chi tiết
$sql = "SELECT * FROM orders WHERE user_id = $user_id AND id = $order_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $order = mysqli_fetch_assoc($result);
    $cart_items = json_decode($order['cart_items'], true);
    $total_price = $order['total_price'];
    $order_status = $order['order_status'];
    $created_at = $order['created_at'];

    echo "<div class='container mt-5'>";
    echo "<h2 class='text-center mb-4'>Chi Tiết Đơn Hàng #" . $order_id . "</h2>";
    echo "<p><strong>Ngày Tạo:</strong> " . $created_at . "</p>";
    echo "<p><strong>Trạng Thái:</strong> " . $order_status . "</p>";
    echo "<p><strong>Tổng Giá:</strong> " . number_format($total_price, 2) . " VND</p>";

    // Hiển thị chi tiết sản phẩm trong đơn hàng
    if (is_array($cart_items)) {
        echo "<table class='table table-bordered table-striped'>";
        echo "<thead><tr><th>Tên Sản Phẩm</th><th>Số Lượng</th><th>Giá</th><th>Tổng</th></tr></thead>";
        echo "<tbody>";

        foreach ($cart_items as $item) {
            $product_name = isset($item['name']) ? $item['name'] : 'Không có tên sản phẩm';
            $quantity = isset($item['quantity']) ? $item['quantity'] : 0;
            $price = isset($item['price']) ? $item['price'] : 0;
            $total = $quantity * $price;

            echo "<tr>";
            echo "<td>$product_name</td>";
            echo "<td>$quantity</td>";
            echo "<td>" . number_format($price, 2) . " VND</td>";
            echo "<td>" . number_format($total, 2) . " VND</td>";
            echo "</tr>";
        }

        echo "</tbody></table>";
    }

    echo "</div>";
} else {
    echo "<p class='text-center'>Đơn hàng không tồn tại hoặc không phải của bạn.</p>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Đơn Hàng</title>
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

        .table th {
            background-color: #17a2b8;
            color: white;
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

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
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
