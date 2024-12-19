<?php
include 'db_connection.php'; // Kết nối cơ sở dữ liệu

// Kiểm tra và cập nhật trạng thái đơn hàng nếu có yêu cầu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['order_status'];

    $update_sql = "UPDATE orders SET order_status = '$new_status' WHERE id = $order_id";
    if (mysqli_query($conn, $update_sql)) {
        $success_message = "Cập nhật trạng thái thành công!";
    } else {
        $error_message = "Cập nhật trạng thái thất bại: " . mysqli_error($conn);
    }
}

// Truy vấn tất cả đơn hàng
$sql = "SELECT id, user_id, name, phone, email, address, note, payment_method, cart_items, order_status, created_at, total_price FROM orders";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Đơn Hàng</title>
    <link href="css/styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
/* Định dạng bảng */
.table {
    background-color: #fff;
    border: 1px solid #020101;
    border-radius: 8px;
}

/* Định dạng header bảng */
.table th {
    background-color: #343a40;
    color: white;
    text-align: center;
}

/* Định dạng cho các hàng trong bảng */
.table td {
    vertical-align: middle;
    text-align: center;
}

/* Cải thiện không gian và border cho các ô */
.table-bordered td, .table-bordered th {
    border: 1px solid #020101;
}

/* Cải thiện các nút và form */
.btn {
    border-radius: 4px;
}

.form-select {
    border-radius: 4px;
}

/* Responsive cho bảng */
@media (max-width: 768px) {
    .table thead {
        display: none;
    }

    .table tr {
        margin-bottom: 10px;
        display: block;
        width: 100%;
    }

    .table td {
        display: block;
        width: 100%;
        text-align: right;
        padding-left: 50%;
        position: relative;
    }

    .table td::before {
        content: attr(data-label);
        position: absolute;
        left: 0;
        top: 0;
        font-weight: bold;
        text-transform: uppercase;
    }

    /* Nút Cập Nhật sẽ chiếm toàn bộ chiều rộng */
    .form-select, .btn {
        width: 100%;
        margin-top: 5px;
    }
}

    </style>
</head>
<body>
<div class="container py-5">
        <h1 class="text-center mb-4">Quản Lý Đơn Hàng</h1>

        <!-- Hiển thị thông báo -->
        <?php if (isset($success_message)) { ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php } ?>
        <?php if (isset($error_message)) { ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php } ?>

        <!-- Bảng đơn hàng -->
        <table class="table table-bordered table-responsive">
            <thead class="table-dark">
                <tr>
                    <th>ID Người Dùng</th>
                    <th>Tên</th>
                    <th>Điện Thoại</th>
                    <th>Email</th>
                    <th>Địa Chỉ</th>
                    <th>Ghi Chú</th>
                    <th>Phương Thức Thanh Toán</th>
                    <th>Giỏ Hàng</th>
                    <th>Số Lượng</th>
                    <th>Tổng Tiền</th>
                    <th>Trạng Thái</th>
                    <th>Ngày Tạo</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { 
                    $cart_items = $row['cart_items'];
                    $cart_items_display = '';
                    $quantities_display = '';

                    if (!empty($cart_items)) {
                        $cart_items_array = json_decode($cart_items, true);
                        if (is_array($cart_items_array)) {
                            $product_ids = array_keys($cart_items_array);
                            $quantities = array_values($cart_items_array);

                            $product_names = [];
                            $product_prices = [];
                            if (!empty($product_ids)) {
                                $product_ids_str = implode(',', $product_ids);
                                $product_names_query = "SELECT id, product_name, product_current_price FROM products WHERE id IN ($product_ids_str)";
                                $product_names_result = mysqli_query($conn, $product_names_query);

                                while ($product_row = mysqli_fetch_assoc($product_names_result)) {
                                    $product_names[$product_row['id']] = $product_row['product_name'];
                                    $product_prices[$product_row['id']] = $product_row['product_current_price'];
                                }
                            }

                            $cart_items_display = implode(', ', $product_names);
                            $quantities_display = implode(', ', $quantities);

                            $total_price = 0;
                            foreach ($product_ids as $index => $product_id) {
                                $total_price += $product_prices[$product_id] * $quantities[$index];
                            }
                        }
                    }
                ?>
                    <tr>
                        <td data-label="ID Người Dùng"><?php echo $row['user_id']; ?></td>
                        <td data-label="Tên"><?php echo $row['name']; ?></td>
                        <td data-label="Điện Thoại"><?php echo $row['phone']; ?></td>
                        <td data-label="Email"><?php echo $row['email']; ?></td>
                        <td data-label="Địa Chỉ"><?php echo $row['address']; ?></td>
                        <td data-label="Ghi Chú"><?php echo $row['note']; ?></td>
                        <td data-label="Phương Thức Thanh Toán"><?php echo $row['payment_method']; ?></td>
                        <td data-label="Giỏ Hàng"><?php echo nl2br($cart_items_display); ?></td>
                        <td data-label="Số Lượng"><?php echo nl2br($quantities_display); ?></td>
                        <td data-label="Tổng Tiền"><?php echo number_format($total_price, 2, ',', '.') . ' VND'; ?></td>
                        <td data-label="Trạng Thái">
                            <form action="quanlydonhang.php" method="POST" class="d-flex">
                                <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                <select name="order_status" class="form-select form-select-sm">
                                    <option value="Chưa xử lý" <?php echo $row['order_status'] === 'Chưa xử lý' ? 'selected' : ''; ?>>Chưa xử lý</option>
                                    <option value="Đang xử lý" <?php echo $row['order_status'] === 'Đang xử lý' ? 'selected' : ''; ?>>Đang xử lý</option>
                                    <option value="Đã giao" <?php echo $row['order_status'] === 'Đã giao' ? 'selected' : ''; ?>>Đã giao</option>
                                    <option value="Đã hủy" <?php echo $row['order_status'] === 'Đã hủy' ? 'selected' : ''; ?>>Đã hủy</option>
                                </select>
                        </td>
                        <td data-label="Ngày Tạo"><?php echo $row['created_at']; ?></td>
                        <td>
                            <button type="submit" name="update_status" class="btn btn-primary btn-sm w-100">Cập Nhật</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
