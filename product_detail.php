<?php
session_start();
include 'db_connection.php'; // Kết nối cơ sở dữ liệu


// Lấy danh sách menu từ cơ sở dữ liệu
$menus = mysqli_query($conn, "SELECT * FROM menus");

// Lấy danh sách logo từ cơ sở dữ liệu
$logos = mysqli_query($conn, "SELECT * FROM logos");

// Lấy danh sách sản phẩm từ cơ sở dữ liệu (giới hạn 4 sản phẩm)
$products = mysqli_query($conn, "SELECT * FROM products");

// Lấy ID sản phẩm từ URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Truy vấn chi tiết sản phẩm
$sql = "SELECT * FROM products WHERE id = $product_id";
$result = mysqli_query($conn, $sql);

// Nếu sản phẩm không tồn tại
if (mysqli_num_rows($result) == 0) {
    echo "Sản phẩm không tồn tại.";
    exit();
}

// Lấy thông tin sản phẩm
$product = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản Phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
    <link rel="shortcut icon" href="uploads/fahicon.jpg" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .product-detail {
            margin-top: 50px;
        }
        .product-image {
            max-width: 100%;
            border-radius: 8px;
        }
        .product-info {
            margin-top: 20px;
        }
        .product-price {
            font-size: 24px;
            color: #ff5722;
        }
        .text-decoration-line-through {
            color: #999;
            margin-right: 10px;
        }
        @media (min-width: 768px) {
    .col-md-9 {
        flex: 0 0 auto;
        width: 75%;
        display: flex;
        flex-direction: column;
        flex-wrap: wrap;
        align-content: center;
        justify-content: center;
        align-items: flex-start;
    }
}
    </style>
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


    <div class="container product-detail mt-5">
    <div class="row">
        <!-- Hình ảnh sản phẩm -->
        <div class="col-md-4">
            <img src="<?php echo $product['product_image']; ?>" alt="<?php echo $product['product_name']; ?>" class="product-image img-fluid">
        </div>

        <!-- Thông tin sản phẩm -->
        <div class="col-md-8">
            <h2><?php echo $product['product_name']; ?></h2>
            <p class="product-price">
                <?php if ($product['product_price'] > $product['product_current_price']) { ?>
                    <span class="text-decoration-line-through">
                        <?php echo number_format($product['product_price'], 0, ',', '.') . ' VND'; ?>
                    </span>
                <?php } ?>
                <span class="text-success">
                    <?php echo number_format($product['product_current_price'], 0, ',', '.') . ' VND'; ?>
                </span>
            </p>
            <p><?php echo nl2br($product['product_description']); ?></p>

            <!-- Nút thêm vào giỏ hàng -->
            <form class="add-to-cart-form">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <button type="submit" class="btn btn-success">Thêm vào giỏ hàng</button>
            </form>

            <!-- Nút quay lại -->
            <a href="index.php" class="btn btn-secondary mt-3">Quay lại</a>
        </div>
        <!-- Hiển thị chi tiết sản phẩm -->
        <h3>Chi Tiết Sản Phẩm</h3>
            <p><?php echo nl2br($product['product_detail']); ?></p>
    </div>


    <div id="cart-popup" class="cart-popup">
        <div class="cart-popup-content">
            <p>Sản phẩm đã được thêm vào giỏ hàng!</p>
            <button id="close-popup" class="close-popup-btn">Đóng</button>
        </div>
    </div>



       <!-- Thêm Bootstrap JS -->
       
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <?php
include 'top.php'; // Kết nối cơ sở dữ liệu 
include 'buttonlienhe.php'; // Kết nối cơ sở dữ liệu 
include 'footer.php'; // Kết nối cơ sở dữ liệu?>
</body>
</html>
