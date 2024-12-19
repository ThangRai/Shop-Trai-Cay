<?php
// Kiểm tra trạng thái của session, chỉ gọi session_start nếu chưa có session
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Khởi tạo session nếu chưa có
}

include 'db_connection.php'; // Kết nối cơ sở dữ liệu

// Kiểm tra xem người dùng đã đăng nhập chưa
if (isset($_SESSION['user_id'])) {
    // Kiểm tra nếu chưa ghi nhận truy cập trong phiên làm việc
    if (!isset($_SESSION['access_logged'])) {
        // Lấy thông tin người dùng từ session
        $user_name = $_SESSION['user_name']; // Tên người dùng lưu trong session
        $access_date = date('Y-m-d H:i:s'); // Lấy thời gian hiện tại
        $ip_address = $_SERVER['REMOTE_ADDR']; // Lấy địa chỉ IP của người truy cập
        $user_agent = $_SERVER['HTTP_USER_AGENT']; // Lấy thông tin trình duyệt của người truy cập

        // Thực hiện ghi nhận vào cơ sở dữ liệu
        $sql = "INSERT INTO access_logs (access_date, ip_address, user_agent, user_name) 
                VALUES ('$access_date', '$ip_address', '$user_agent', '$user_name')";

        if (!mysqli_query($conn, $sql)) {
            // Nếu có lỗi thì in ra lỗi
            error_log("Error recording access: " . mysqli_error($conn));
        } else {
            // Đánh dấu là đã ghi nhận truy cập trong phiên làm việc này
            $_SESSION['access_logged'] = true;
        }
    }
}
?>
