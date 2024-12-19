<?php
session_start(); // Bắt đầu phiên làm việc

include 'db_connection.php'; // Kết nối cơ sở dữ liệu

// Kiểm tra nếu form đăng nhập được gửi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $remember = isset($_POST['remember']) ? true : false; // Kiểm tra nếu người dùng muốn lưu mật khẩu

    // Kiểm tra email tồn tại trong cơ sở dữ liệu
    $sql = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    // Kiểm tra nếu có người dùng
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Kiểm tra nếu mật khẩu đúng
        if ($password == $user['password']) {
            // Nếu đăng nhập thành công, lưu thông tin người dùng vào session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role']; // Lưu vai trò vào session

            // Nếu người dùng chọn "Remember Me", lưu thông tin đăng nhập vào cookie
            if ($remember) {
                setcookie('user_email', $email, time() + (86400 * 30), "/"); // Lưu email trong 30 ngày
                setcookie('user_password', $password, time() + (86400 * 30), "/"); // Lưu mật khẩu trong 30 ngày
            }

            // Kiểm tra nếu người dùng là admin hoặc user và chuyển hướng tương ứng
            if ($user['role'] == 'admin') {
                header("Location: admin.php"); // Chuyển đến trang quản trị
            } else {
                header("Location: index.php"); // Chuyển đến trang người dùng
            }
            exit();
        } else {
            // Nếu mật khẩu sai
            $error_message = "Email hoặc mật khẩu không hợp lệ!";
        }
    } else {
        // Nếu email không tồn tại
        $error_message = "Email không tồn tại!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Login - Admin/User</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                                    <div class="card-body">
                                    <form action="login.php" method="POST">
                                            <!-- Hiển thị thông báo lỗi nếu có -->
                                            <?php if (isset($error_message)) { ?>
                                                <div class="alert alert-danger" role="alert">
                                                    <?php echo $error_message; ?>
                                                </div>
                                            <?php } ?>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputEmail" name="email" type="email" placeholder="name@example.com" required />
                                            <label for="inputEmail">Email address</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Password" required />
                                            <label for="inputPassword">Password</label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" id="inputRememberPassword" name="remember" type="checkbox" />
                                            <label class="form-check-label" for="inputRememberPassword">Remember Password</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <a class="small" href="password.php">Forgot Password?</a>
                                            <button type="submit" class="btn btn-primary">Login</button>
                                        </div>
                                    </form>
                                    </div>
                                    <div class="card-footer text-center py-3">
                                        <div class="small"><a href="register.php">Need an account? Sign up!</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
