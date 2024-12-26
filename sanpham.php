<?php
session_start();
include 'db_connection.php'; // Kết nối cơ sở dữ liệu


// Lấy danh sách menu từ cơ sở dữ liệu
$menus = mysqli_query($conn, "SELECT * FROM menus");

// Lấy danh sách logo từ cơ sở dữ liệu
$logos = mysqli_query($conn, "SELECT * FROM logos");

// Lấy danh sách sản phẩm từ cơ sở dữ liệu (giới hạn 4 sản phẩm)
$products = mysqli_query($conn, "SELECT * FROM products");
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



       <!-- Thêm Bootstrap JS -->
       <script>
    // Chạy khi trang được tải xong
    document.addEventListener("DOMContentLoaded", function() {
        // Thêm class 'show' vào các phần tử có class 'title'
        const title = document.querySelector('.title');
        title.classList.add('show');
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <?php
include 'top.php'; // Kết nối cơ sở dữ liệu 
include 'buttonlienhe.php'; // Kết nối cơ sở dữ liệu 
include 'footer.php'; // Kết nối cơ sở dữ liệu?>
</body>
</html>
