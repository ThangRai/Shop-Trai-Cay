<?php
session_start(); // Bắt đầu phiên làm việc

include 'db_connection.php'; // Kết nối cơ sở dữ liệu

// Lấy danh sách menu từ cơ sở dữ liệu
$menus = mysqli_query($conn, "SELECT * FROM menus");

// Lấy danh sách logo từ cơ sở dữ liệu
$logos = mysqli_query($conn, "SELECT * FROM logos");

$products = mysqli_query($conn, "SELECT * FROM products");

// Lấy danh sách bài viết từ cơ sở dữ liệu
$sql = "SELECT * FROM articles ORDER BY published_date DESC"; // Sắp xếp theo ngày đăng
$result = mysqli_query($conn, $sql);

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
    <title>Trang Chủ</title>
    <!-- Thêm Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="shortcut icon" href="uploads/fahicon.jpg" type="image/x-icon">
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

    <!-- Giới Thiệu -->
    <section class="introduction py-5">
        <div class="container">
        <a href="gioithieu.php" target="_blank">
        <h2 class="title">Giới thiệu</h2>
        </a>            
            <div class="row align-items-center">
                <!-- Cột 1: Ảnh -->
                <div class="col-lg-6">
                    <img src="uploads/gioithieu.png" class="img-fluid" alt="Giới thiệu hình ảnh">
                </div>
                <!-- Cột 2: Văn bản -->
                <div class="col-lg-6">
                    <h2>Giới Thiệu Chúng Tôi</h2>
                    <p>Chúng tôi là một công ty chuyên cung cấp các dịch vụ chất lượng cao và giải pháp sáng tạo cho khách hàng. Với đội ngũ nhân viên giàu kinh nghiệm, chúng tôi cam kết mang lại những sản phẩm và dịch vụ tốt nhất.</p>
                    <p>Chúng tôi tập trung vào việc phát triển bền vững và xây dựng mối quan hệ lâu dài với khách hàng. Sự hài lòng của khách hàng là ưu tiên hàng đầu của chúng tôi.</p>
                    <p>Hãy cùng chúng tôi khám phá thêm các dịch vụ và sản phẩm của công ty!</p>
                </div>
            </div>
        </div>
    </section>


    <div class="container mt-5">
    <h2 class="title">Sản Phẩm</h2>
    <div class="row">
        <?php while ($product = mysqli_fetch_assoc($products)) { 
            // Tính phần trăm giảm giá
            $discount = 0;
            if ($product['product_price'] > 0) {
                $discount = round(((($product['product_price'] - $product['product_current_price']) / $product['product_price']) * 100), 2);
            }
        ?>
            <div class="col-md-3">
                <div class="product-card">
                    <!-- Thẻ a bao quanh phần sản phẩm nhưng không bao gồm nút -->
                    <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="text-decoration-none text-dark">
                        <div class="position-relative">
                            <!-- Hiển thị % giảm giá -->
                            <?php if ($discount > 0) { ?>
                                <span class="discount-badge">-<?php echo $discount; ?>%</span>
                            <?php } ?>
                            <img src="<?php echo $product['product_image']; ?>" alt="<?php echo $product['product_name']; ?>" class="product-image">
                        </div>
                        <div class="product-name"><?php echo $product['product_name']; ?></div>
                        
                        <!-- Hiển thị giá gốc và giá hiện tại -->
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="product-price text-decoration-line-through me-2">
                                <?php echo number_format($product['product_price'], 0, ',', '.') . ' VND'; ?>
                            </div>
                            <div class="product-current-price text-success">
                                <?php echo number_format($product['product_current_price'], 0, ',', '.') . ' VND'; ?>
                            </div>
                        </div>
                    </a>

                    <!-- Nút "Thêm vào giỏ hàng" nằm ngoài thẻ <a> -->
                    <form action="cart.php" method="POST" class="add-to-cart-form mt-2">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" class="btn btn-success add-to-cart-btn">Thêm vào giỏ hàng</button>
                    </form>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

    <div id="cart-popup" class="cart-popup">
        <div class="cart-popup-content">
            <p>Sản phẩm đã được thêm vào giỏ hàng!</p>
            <button id="close-popup" class="close-popup-btn">Đóng</button>
        </div>
    </div>

    <!-- Phần tin tức -->
    <div class="container py-5">
    <a href="tintuc.php" target="_blank">
        <h2 class="title">Tin tức</h2>
    </a>
        <div class="row">
            <?php while ($article = mysqli_fetch_assoc($result)) { ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card">
                        <!-- Ảnh đại diện -->
                        <img src="uploads/<?php echo $article['image']; ?>" class="card-img-top" alt="<?php echo $article['title']; ?>">

                        <div class="card-body">
                            <!-- Tiêu đề bài viết -->
                            <h5 class="card-title">
                                <?php 
                                // Giới hạn tiêu đề tối đa 50 ký tự
                                $title = $article['title'];
                                if (strlen($title) > 50) {
                                    echo substr($title, 0, 50) . '...';
                                } else {
                                    echo $title;
                                }
                                ?>
                            </h5>
                            <!-- Mô tả ngắn -->
                            <p class="card-text"><?php echo substr($article['description'], 0, 100); ?>...</p>
                            
                            <!-- Thông tin tác giả và ngày đăng -->
                            <p class="text-muted">
                                <small>
                                    Tác giả: <?php echo $article['author']; ?> | Ngày đăng: <?php echo date('d/m/Y', strtotime($article['published_date'])); ?>
                                </small>
                            </p>

                            <!-- Nút xem thêm -->
                            <a href="baiviet.php?id=<?php echo $article['id']; ?>" class="btn btn-primary">Xem thêm</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="container py-5">
    <a href="lienhe.php" target="_blank">
        <h2 class="title">Liên hệ</h2>
    </a>
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

<!-- Phần Bản đồ -->
<div class="container py-5">
    <h2 class="title">Bản đồ</h2>
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3894.8324265095875!2d108.30764557585289!3d12.527257924462795!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x317193b603af7f3b%3A0x71c85315931a80b0!2zVGjhuq9uZyBSYWk!5e0!3m2!1svi!2s!4v1734422958114!5m2!1svi!2s" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
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

    <!-- Thêm Bootstrap JS -->
    <script>
document.addEventListener("DOMContentLoaded", function() {
    // Chọn tất cả các phần tử có class '.title'
    const titles = document.querySelectorAll('.title');
    
    titles.forEach(function(title) {
        title.classList.add('show'); // Thêm class 'show' cho tất cả các phần tử này
    });
});
</script>

<script>
   document.addEventListener("DOMContentLoaded", function () {
    const forms = document.querySelectorAll('.add-to-cart-form'); // Lấy tất cả các form thêm giỏ hàng
    const popup = document.getElementById('cart-popup'); // Lấy popup

    forms.forEach(form => {
        form.addEventListener('submit', function (event) {
            event.preventDefault(); // Ngăn việc tải lại trang

            const formData = new FormData(form);

            fetch('cart.php', {
                method: 'POST',
                body: formData,
            })
                .then(response => response.text())
                .then(data => {
                    console.log(data); // Hiển thị phản hồi từ server (nếu cần)
                    popup.style.display = 'block'; // Hiển thị popup
                    setTimeout(() => popup.style.display = 'none', 1500); // Ẩn popup sau 3 giây
                })
                .catch(error => console.error('Lỗi:', error));
        });
    });
});

</script>
<script type="text/javascript" src="https://itexpress.vn/API/js/noel.js"></script>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script> -->
    <?php
include 'top.php'; // Kết nối cơ sở dữ liệu 
include 'buttonlienhe.php'; // Kết nối cơ sở dữ liệu 
include 'footer.php'; // Kết nối cơ sở dữ liệu
?>
</body>
</html>
