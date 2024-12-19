<?php
session_start();  // Bắt đầu phiên làm việc với session

include 'db_connection.php';

// Lấy danh sách menu từ cơ sở dữ liệu
$menus = mysqli_query($conn, "SELECT * FROM menus");

// Lấy danh sách logo từ cơ sở dữ liệu
$logos = mysqli_query($conn, "SELECT * FROM logos");

$message = "";

// Cấu hình Telegram
$telegramBotToken = '6608663537:AAExeC77L9XmTSK3lpW0Q3zt_kGfC1qKZfA';
$telegramChatID = '5901907211';

// Xử lý khi form được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $messageContent = mysqli_real_escape_string($conn, $_POST['message']);

    // Lưu thông tin vào cơ sở dữ liệu
    $sql = "INSERT INTO contacts (name, email, phone, message) VALUES ('$name', '$email', '$phone', '$messageContent')";
    if (mysqli_query($conn, $sql)) {
        // Gửi thông tin lên Telegram
        $telegramMessage = "💬 *Thông tin liên hệ mới:* \n" . 
            "👤 *Họ và tên:* $name\n" .
            "📧 *Email:* $email\n" .
            "📞 *Số điện thoại:* $phone\n" .
            "📝 *Nội dung:* $messageContent";

        // Gửi request đến Telegram API
        $telegramURL = "https://api.telegram.org/bot$telegramBotToken/sendMessage";
        $data = [
            'chat_id' => $telegramChatID,
            'text' => $telegramMessage,
            'parse_mode' => 'Markdown'
        ];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ],
        ];
        $context  = stream_context_create($options);
        $response = file_get_contents($telegramURL, false, $context);

        if ($response === FALSE) {
            $message = "Đã xảy ra lỗi khi gửi thông tin lên Telegram.";
        } else {
            // Lưu thông báo vào session và chuyển hướng về trang hiện tại
            $_SESSION['message'] = "Cảm ơn bạn đã liên hệ với chúng tôi. Chúng tôi sẽ phản hồi sớm nhất!";
            header("Location: index.php");  // Redirect về chính trang này để tránh gửi lại form khi tải lại trang
            exit();
        }
    } else {
        // Lỗi khi lưu vào CSDL
        $message = "Đã xảy ra lỗi khi lưu thông tin vào CSDL. Vui lòng thử lại sau.";
    }
}

// Kiểm tra nếu có thông báo từ session
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);  // Xóa thông báo sau khi đã hiển thị
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên Hệ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="shortcut icon" href="uploads/fahicon.jpg" type="image/x-icon">
    <link rel="stylesheet" href="index.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="index.php">
                <?php while ($logo = mysqli_fetch_assoc($logos)) { ?>
                    <img src="uploads/<?php echo $logo['logo_image']; ?>" alt="<?php echo $logo['logo_name']; ?>" class="logo">
                <?php } ?>
            </a>

            <!-- Nút toggle cho mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Danh sách menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <?php 
                    $current_page = basename($_SERVER['PHP_SELF']); // Lấy tên trang hiện tại
                    while ($menu = mysqli_fetch_assoc($menus)) { 
                        // Lấy tên file của menu link
                        $menu_file = basename($menu['menu_link']);
                        ?>
                        <li class="nav-item <?php echo ($current_page == $menu_file) ? 'active' : ''; ?>">
                            <a class="nav-link" href="<?php echo $menu['menu_link']; ?>"><?php echo $menu['menu_name']; ?></a>
                        </li>
                    <?php } ?>
                </ul>
            </div>

            <!-- Các icon nằm dưới logo trên mobile -->
            <div class="navbar-icons d-flex flex-column flex-lg-row align-items-center">
                <!-- Nút tìm kiếm -->
                <a href="#" class="nav-link" data-bs-toggle="collapse" data-bs-target="#searchForm" aria-expanded="false" aria-controls="searchForm">
                    <i class="bi bi-search"></i>
                </a>

                <!-- Form tìm kiếm ẩn -->
                <div class="collapse position-absolute search-container" id="searchForm">
                    <form class="d-flex flex-column" action="search.php" method="GET">
                        <input class="form-control" type="search" name="q" placeholder="Tìm kiếm..." aria-label="Search">
                        <button class="btn btn-primary" type="submit">Tìm</button>
                    </form>
                </div>

                <!-- Giỏ hàng -->
                <a href="cart.php" class="nav-link position-relative">
                    <i class="bi bi-cart"></i>
                    <span id="cart-item-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                    </span>
                </a>
                <!-- Kiểm tra nếu người dùng đã đăng nhập -->
                <?php if (isset($_SESSION['user_id'])) { ?>
                    <!-- Dropdown người dùng đã đăng nhập -->
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $_SESSION['user_name']; ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="profile.php">Thông tin</a></li>
                            <li><a class="dropdown-item" href="orders.php">Sản phẩm đã mua</a></li>
                            <li><a class="dropdown-item" href="logout.php">Đăng xuất</a></li>
                        </ul>
                    </div>
                <?php } else { ?>
                    <!-- Nếu người dùng chưa đăng nhập, hiển thị icon đăng nhập -->
                    <a href="login.php" class="nav-link">
                        <i class="bi bi-person"></i>
                    </a>
                <?php } ?>
            </div>
        </div>
    </nav>
    
   <!-- ảnh silde -->
    <?php
    // Lấy danh sách các slide từ cơ sở dữ liệu
    $slides = mysqli_query($conn, "SELECT * FROM slides");
    ?>
        <!-- Slideshow Section -->
        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php
                $firstSlide = true;
                while ($slide = mysqli_fetch_assoc($slides)) {
                    $activeClass = $firstSlide ? 'active' : ''; // Đảm bảo slide đầu tiên được hiển thị
                    $firstSlide = false;
                ?>
                    <div class="carousel-item <?php echo $activeClass; ?>">
                        <img src="<?php echo $slide['slide_image']; ?>" class="d-block w-100" alt="<?php echo $slide['slide_title']; ?>">
                    </div>
                <?php } ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>


<div class="container py-5">
    <h2 class="title">Liên hệ</h2>
    <div class="row">
        <!-- Cột 1: Thông tin cửa hàng -->
        <div class="col-md-6 store-info">
            <h3>Thông tin cửa hàng</h3>
            <p><i class="bi bi-geo-alt"></i> 123 Đường ABC, Quận 1, TP.HCM</p>
            <p><i class="bi bi-envelope"></i> contact@company.com</p>
            <p><i class="bi bi-telephone"></i> 0123 456 789</p>
            <p><i class="bi bi-clock"></i> 8:00 - 17:00 (Thứ 2 - Thứ 6)</p>
        </div>

        <!-- Cột 2: Form liên hệ -->
        <div class="col-md-6">
            <h3>Liên hệ với chúng tôi</h3>
            <form action="lienhe.php" method="POST">
                <div class="input-container mb-3">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Họ và tên" required>
                    <i class="bi bi-person"></i>
                </div>
                <div class="input-container mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                    <i class="bi bi-envelope"></i>
                </div>
                <div class="input-container mb-3">
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Số điện thoại" required>
                    <i class="bi bi-telephone"></i>
                </div>
                <div class="input-container mb-3">
                    <input type="text" class="form-control" id="message" name="message" placeholder="Nội dung" required>
                    <i class="bi bi-chat-dots"></i>
                </div>
                <button type="submit" class="gui">Gửi liên hệ</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal thông báo -->
<?php if ($message != ""): ?>
    <div class="modal-message" style="display: block;">
        <p><?php echo $message; ?></p>
        <button onclick="window.location.reload();">Đóng</button>
    </div>
<?php endif; ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Chọn tất cả các phần tử có class '.title'
    const titles = document.querySelectorAll('.title');
    
    titles.forEach(function(title) {
        title.classList.add('show'); // Thêm class 'show' cho tất cả các phần tử này
    });
});
</script>
<?php
include 'top.php'; // Kết nối cơ sở dữ liệu 
include 'buttonlienhe.php'; // Kết nối cơ sở dữ liệu 
include 'footer.php'; // Kết nối cơ sở dữ liệu
?>
</body>
</html>
