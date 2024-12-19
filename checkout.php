<?php
session_start();
include 'db_connection.php'; // Kết nối cơ sở dữ liệu

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Nếu chưa đăng nhập thì chuyển hướng đến trang login
    exit();
}

// Lấy thông tin người dùng
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Xử lý khi form được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy thông tin từ form thanh toán
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $note = mysqli_real_escape_string($conn, $_POST['note']);
    $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'COD';

    // Lấy thông tin giỏ hàng từ session
    $cart_items = json_encode($_SESSION['cart']); // Giữ ID và số lượng sản phẩm

    // Tính tổng số tiền
    $total_price = 0;
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        // Truy vấn thông tin sản phẩm từ bảng products
        $query = "SELECT product_current_price FROM products WHERE id = $product_id";
        $product_result = mysqli_query($conn, $query);
        $product = mysqli_fetch_assoc($product_result);
        
        if ($product) {
            // Tính tổng giá trị sản phẩm này
            $total_price += $product['product_current_price'] * $quantity;
        }
    }

    // Thêm thông tin đơn hàng vào cơ sở dữ liệu
    $query = "INSERT INTO orders (user_id, name, phone, email, address, note, payment_method, cart_items, order_status, total_price) 
              VALUES ('$user_id', '$name', '$phone', '$email', '$address', '$note', '$payment_method', '$cart_items', 'Chưa xử lý', '$total_price')";

    if (mysqli_query($conn, $query)) {
        // Xóa giỏ hàng sau khi hoàn tất thanh toán
        unset($_SESSION['cart']);
        
        // Chuyển hướng đến trang thanh toán thành công hoặc trang chi tiết đơn hàng
        header('Location: checkout.php?success=true');
        exit();
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
    <title>Thanh Toán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    /* Đặt nền màu cho toàn bộ trang */
body {
    font-family: math;
    background-color: #f4f7fc;
    color: #333;
}

/* Định dạng cho container */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 15px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Tiêu đề */
h2 {
    font-size: 30px;
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}

/* Hiển thị giỏ hàng */
table {
    width: 100%;
    margin-bottom: 30px;
    border-collapse: collapse;
}

th, td {
    padding: 12px;
    text-align: center;
}

th {
    background-color: #f1f3f5;
    color: #333;
}

td {
    background-color: #fafafa;
    border: 1px solid #e1e1e1;
}

td img {
    max-width: 80px;
    height: auto;
}

/* Nút thanh toán và các nút trong form */


button:hover, .btn:hover {
    background-color: #0056b3;
    border-color: #0056b3;
    cursor: pointer;
}

/* Định dạng các input */
input[type="text"], input[type="email"], input[type="tel"], textarea {
    border: 1px solid #ccc;
    border-radius: 4px;
    padding: 12px;
    font-size: 16px;
    width: 100%;
    margin-bottom: 15px;
    transition: all 0.3s ease;
}

input[type="text"]:focus, input[type="email"]:focus, input[type="tel"]:focus, textarea:focus {
    border-color: #007bff;
    outline: none;
}

/* Hiệu ứng hover cho các nút chọn phương thức thanh toán */
input[type="radio"]:checked + label {
    background-color: #007bff;
    color: white;
    border-radius: 4px;
    padding: 10px 15px;
    transition: background-color 0.3s ease;
}

input[type="radio"]:hover + label {
    background-color: #0056b3;
}

/* Phần ghi chú */
textarea {
    height: 150px;
}

/* Hiệu ứng cho form khi submit */
form {
    transition: opacity 0.5s ease-in-out;
}

/* Cảnh báo thanh toán thành công */
.alert {
    font-size: 16px;
    color: green;
    font-weight: bold;
    text-align: center;
}

/* Hiển thị giỏ hàng trong thanh toán */
table th, table td {
    text-align: left;
}

h4 {
    font-size: 22px;
    color: #007bff;
    font-weight: bold;
}

/* Các trường nhập liệu đẹp hơn */
input[type="text"], input[type="email"], input[type="tel"], select {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 10px;
    font-size: 16px;
}

select:focus, input[type="text"]:focus, input[type="email"]:focus, input[type="tel"]:focus {
    border-color: #007bff;
}

label {
    font-size: 16px;
    color: #555;
}

/* Hiệu ứng phóng to khi di chuột lên hình ảnh sản phẩm */
table td img:hover {
    transform: scale(1.1);
    transition: transform 0.3s ease;
}

</style>
<head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Thông Tin Thanh Toán</h2>

    <?php
    if (isset($_GET['success']) && $_GET['success'] == 'true') {
        echo '<div class="alert alert-success" role="alert">Thanh toán thành công! Cảm ơn bạn đã mua hàng.</div>';
    }
    ?>

    <h3>Sản phẩm trong giỏ hàng</h3>

    <?php
    // Kiểm tra giỏ hàng có tồn tại trong session hay không
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        // Chuyển đổi giỏ hàng thành chuỗi các product_id
        $product_ids = implode(',', array_keys($_SESSION['cart']));
        
        // Lấy thông tin sản phẩm từ bảng products
        $query = "SELECT * FROM products WHERE id IN ($product_ids)";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            echo '<table class="table table-bordered">';
            echo '<thead><tr><th>Hình ảnh</th><th>Sản phẩm</th><th>Giá</th><th>Số lượng</th><th>Tổng</th></tr></thead><tbody>';

            $total_price = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $product_id = $row['id'];
                $quantity = $_SESSION['cart'][$product_id];  // Lấy số lượng từ giỏ hàng
                $subtotal = $row['product_current_price'] * $quantity;  // Tính tổng giá cho sản phẩm này
                $total_price += $subtotal;

                echo '<tr>';
                echo '<td><img src="' . $row['product_image'] . '" alt="' . $row['product_name'] . '" style="width: 80px; height: auto;"></td>';
                echo '<td>' . htmlspecialchars($row['product_name']) . '</td>';
                echo '<td>' . number_format($row['product_current_price'], 0, ',', '.') . ' VND</td>';
                echo '<td>' . $quantity . '</td>';
                echo '<td>' . number_format($subtotal, 0, ',', '.') . ' VND</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
            echo '<h4>Tổng cộng: ' . number_format($total_price, 0, ',', '.') . ' VND</h4>';
        } else {
            echo '<p>Giỏ hàng của bạn đang trống.</p>';
        }
    }
    ?>

    <form action="checkout.php" method="POST">
        <div class="row">
            <!-- Họ tên và Số điện thoại -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="name" class="form-label">Họ và tên</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($user['name']) ? $user['name'] : ''; ?>" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="phone" class="form-label">Số điện thoại</label>
                    <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo isset($user['phone']) ? $user['phone'] : ''; ?>" required>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Email và Địa chỉ -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($user['email']) ? $user['email'] : ''; ?>" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="address" class="form-label">Địa chỉ</label>
                    <input type="text" class="form-control" id="address" name="address" required>
                </div>
            </div>
        </div>

        <!-- Ghi chú -->
        <div class="mb-3">
            <label for="note" class="form-label">Ghi chú</label>
            <textarea class="form-control" id="note" name="note" rows="3"></textarea>
        </div>

        <!-- Phương thức thanh toán -->
        <div class="mb-3">
            <label class="form-label">Phương thức thanh toán</label><br>
            <input type="radio" id="payment_method_cod" name="payment_method" value="COD" checked> Thanh toán khi nhận hàng (COD)<br>
            <input type="radio" id="payment_method_online" name="payment_method" value="Online"> Thanh toán trực tuyến
        </div>
        
        <button type="button" class="btn btn-secondary" onclick="window.history.back();">Quay lại</button>
        <button type="submit" class="btn btn-primary">Thanh toán</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
