<?php
session_start(); // Bắt đầu phiên làm việc
include 'db_connection.php'; // Kết nối cơ sở dữ liệu

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user_id'])) {
    echo "
    <div style='display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; font-family: Arial, sans-serif;'>
        <h2>Bạn cần đăng nhập để xem thông tin cá nhân!</h2>
        <a href='login.php' style='margin-top: 20px; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Đăng Nhập</a>
    </div>";
    exit;
}

// Lấy thông tin người dùng từ bảng users
$user_id = $_SESSION['user_id'];
$sql = "SELECT last_name, email, password FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result); // Lấy thông tin người dùng
} else {
    echo "Không tìm thấy thông tin người dùng.";
    exit;
}

// Xử lý cập nhật thông tin (nếu có)
$success_message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); // Không mã hóa mật khẩu

    // Cập nhật thông tin người dùng
    $update_sql = "
        UPDATE users 
        SET last_name = '$last_name', email = '$email', password = '$password' 
        WHERE id = $user_id";

    if (mysqli_query($conn, $update_sql)) {
        $success_message = "Cập nhật thông tin thành công!";
    } else {
        $success_message = "Cập nhật thất bại: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Cá Nhân</title>
    <!-- Thêm Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Thông Tin Cá Nhân</h2>
    <form method="POST" id="profileForm">
        <!-- Tên -->
        <div class="mb-3">
            <label for="last_name" class="form-label">Họ và Tên</label>
            <input type="text" class="form-control" id="last_name" name="last_name" 
                   value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
        </div>
        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" 
                   value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>

        <!-- Mật khẩu -->
        <div class="mb-3">
            <label for="password" class="form-label">Mật Khẩu</label>
            <input type="text" class="form-control" id="password" name="password" 
                   value="<?php echo htmlspecialchars($user['password']); ?>" required>
        </div>
        <!-- Nút Lưu -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmModal">
            Lưu Thay Đổi
        </button>
    </form>
</div>

<!-- Modal Xác Nhận -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Xác nhận cập nhật</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn lưu các thay đổi này không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" form="profileForm" class="btn btn-primary">Đồng ý</button>
            </div>
        </div>
    </div>
</div>

<!-- Hiển thị thông báo sau khi cập nhật -->
<?php if (!empty($success_message)) { ?>
    <div class="alert alert-success mt-3 container"><?php echo $success_message; ?></div>
<?php } ?>

<!-- Thêm Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<?php
include 'hotro.php'; // Kết nối cơ sở dữ liệu 
?>
</body>
</html>
