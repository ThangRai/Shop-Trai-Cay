<?php
session_start(); // Bắt đầu phiên làm việc

include 'db_connection.php'; // Kết nối cơ sở dữ liệu

// Lấy danh sách menu từ cơ sở dữ liệu
$menus = mysqli_query($conn, "SELECT * FROM menus");

// Lấy danh sách logo từ cơ sở dữ liệu
$logos = mysqli_query($conn, "SELECT * FROM logos");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Giới Thiệu</title>
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

    <!-- Giới Thiệu -->
    <section class="introduction py-5">
        <div class="container">
            <h2 class="title">Giới thiệu</h2>
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

    

    <!-- Thêm Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Chạy khi trang được tải xong
    document.addEventListener("DOMContentLoaded", function() {
        // Thêm class 'show' vào các phần tử có class 'title'
        const title = document.querySelector('.title');
        title.classList.add('show');
    });
</script>
<?php
include 'top.php'; // Kết nối cơ sở dữ liệu 
include 'buttonlienhe.php'; // Kết nối cơ sở dữ liệu 
include 'footer.php'; // Kết nối cơ sở dữ liệu?>
</body>
</html>