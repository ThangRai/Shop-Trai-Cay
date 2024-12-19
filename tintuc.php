<?php

// Kết nối cơ sở dữ liệu
include 'db_connection.php'; 

// Lấy danh sách bài viết từ cơ sở dữ liệu
$sql = "SELECT * FROM articles ORDER BY published_date DESC"; // Sắp xếp theo ngày đăng
$result = mysqli_query($conn, $sql);

// Lấy danh sách menu từ cơ sở dữ liệu
$menus = mysqli_query($conn, "SELECT * FROM menus");

// Lấy danh sách logo từ cơ sở dữ liệu
$logos = mysqli_query($conn, "SELECT * FROM logos");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tin Tức</title>
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
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
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

    <!-- Phần tin tức -->
    <div class="container py-5">
    <h2 class="title">Tin tức</h2>

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

    <!-- Thêm Bootstrap JS -->
    <script>
    // Chạy khi trang được tải xong
    document.addEventListener("DOMContentLoaded", function() {
        // Thêm class 'show' vào các phần tử có class 'title'
        const title = document.querySelector('.title');
        title.classList.add('show');
    });
</script>
    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<?php
include 'top.php'; // Kết nối cơ sở dữ liệu 
include 'buttonlienhe.php'; // Kết nối cơ sở dữ liệu 
include 'footer.php'; // Kết nối cơ sở dữ liệu
?>
</body>
</html>
