<?php
session_start(); // Bắt đầu phiên làm việc


include "db_connection.php";
include "truycap.php";

// Truy vấn số lượng đơn hàng theo trạng thái
$sql = "
SELECT 
    order_status, 
    COUNT(*) AS count
FROM orders
WHERE order_status IN ('Chưa xử lý', 'Đang xử lý', 'Đã giao', 'Đã hủy') 
GROUP BY order_status
";
$result = mysqli_query($conn, $sql);

// Lưu số liệu vào mảng
$order_statuses = ['Chưa xử lý', 'Đang xử lý', 'Đã giao', 'Đã hủy'];
$order_counts = [0, 0, 0, 0]; // Mặc định số lượng đơn hàng cho từng trạng thái là 0

while ($row = mysqli_fetch_assoc($result)) {
$status = $row['order_status'];
$count = $row['count'];

// Lưu số lượng vào mảng dựa trên trạng thái
if ($status == 'Chưa xử lý') {
    $order_counts[0] = $count;
} elseif ($status == 'Đang xử lý') {
    $order_counts[1] = $count;
} elseif ($status == 'Đã giao') {
    $order_counts[2] = $count;
} elseif ($status == 'Đã hủy') {
    $order_counts[3] = $count;
}
}

// Truy vấn doanh thu theo ngày cho các đơn hàng đã hoàn thành
$sql = "
SELECT 
    DATE(created_at) AS order_date, 
    SUM(total_price) AS revenue
FROM orders
WHERE order_status = 'Đã giao'
GROUP BY DATE(created_at)
ORDER BY DATE(created_at) DESC
";
$result = mysqli_query($conn, $sql);

// Lưu dữ liệu doanh thu vào mảng
$dates = [];
$revenues = [];
while ($row = mysqli_fetch_assoc($result)) {
$dates[] = $row['order_date']; // Lưu ngày
$revenues[] = $row['revenue']; // Lưu doanh thu
}

// Nếu không có dữ liệu, sử dụng dữ liệu mặc định
if (empty($dates)) {
$dates = ['2024-12-01', '2024-12-02', '2024-12-03', '2024-12-04', '2024-12-05'];
$revenues = [12000000, 15000000, 10000000, 20000000, 18000000];
}


// Truy vấn số lượt truy cập theo tháng
$sql = "SELECT MONTH(access_date) AS month, COUNT(*) AS total_access
        FROM access_logs
        WHERE YEAR(access_date) = YEAR(CURDATE())  -- Lọc theo năm hiện tại
        GROUP BY MONTH(access_date)
        ORDER BY MONTH(access_date)";

$result = mysqli_query($conn, $sql);

$months = [];
$total_accesses = [];

// Lấy dữ liệu về tháng và số lượt truy cập
while ($row = mysqli_fetch_assoc($result)) {
    $months[] = $row['month'];  // Tháng
    $total_accesses[] = $row['total_access'];  // Số lượt truy cập
}


// Kiểm tra nếu biến session chứa thông tin đăng nhập
if (isset($_SESSION['user_id'])) {
    // Nếu người dùng đã đăng nhập
    echo "Bạn đã đăng nhập. ID người dùng là: " . $_SESSION['user_id'];
} else {
    // Nếu người dùng chưa đăng nhập
    echo "Bạn chưa đăng nhập.";
    // Bạn có thể chuyển hướng người dùng đến trang đăng nhập nếu chưa đăng nhập
    header('Location: login.php');
    exit();
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
        <title>Dashboard - SB Admin</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        td {
    padding: 0 !important;
}
    </style>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="admin.php">Start Bootstrap</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
                </div>
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user fa-fw"></i> 
            <?php
                // Kiểm tra nếu người dùng đã đăng nhập và hiển thị tên người dùng
                if (isset($_SESSION['user_name'])) {
                    echo $_SESSION['user_name'];
                } else {
                    echo 'Guest'; // Nếu chưa đăng nhập, hiển thị "Guest"
                }
            ?>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="#!">Settings</a></li>
            <li><a class="dropdown-item" href="#!">Activity Log</a></li>
            <li><hr class="dropdown-divider" /></li>
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
        </ul>
    </li>
</ul>

        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link" href="admin.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <div class="sb-sidenav-menu-heading">Interface</div>

                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLogo" aria-expanded="false" aria-controls="collapseLogo">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Logo
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLogo" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="logo.php">Logo - Banner</a>
                                    <a class="nav-link" href="slideshow.php">Slide Show</a>
                                </nav>
                            </div>

                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Layouts
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="danhsachmenu.php">Danh sách menu</a>
                                    <a class="nav-link" href="layout-sidenav-light.html">Light Sidenav</a>
                                </nav>
                            </div>
                            <!-- Sản phẩm  -->
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseSanpham" aria-expanded="false" aria-controls="collapseSanpham">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Sản Phẩm
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseSanpham" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="danhsachsanpham.php">Danh sách sản phẩm</a>
                                    <a class="nav-link" href="layout-sidenav-light.html">Light Sidenav</a>
                                </nav>
                            </div>

                            <!-- Dịch vụ  -->
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseDichvu" aria-expanded="false" aria-controls="collapseDichvu">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Danh sách dịch vụ
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseDichvu" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="thongtindichvu.php">Dịch vụ</a>
                                    <a class="nav-link" href="layout-sidenav-light.html">Light Sidenav</a>
                                </nav>
                            </div>

                            <!-- Bài viết  -->
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsebaiviet" aria-expanded="false" aria-controls="collapsebaiviet">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Danh sách Bài viết
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapsebaiviet" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="danhsachbaiviet.php">Bài viết</a>
                                    <a class="nav-link" href="layout-sidenav-light.html">Light Sidenav</a>
                                </nav>
                            </div>

                            <!-- Thông tin liên hệ  -->
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseThongtinlienhe" aria-expanded="false" aria-controls="collapseThongtinlienhe">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Thông tin liên hệ
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseThongtinlienhe" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="thongtinlienhe.php">Thông tin liên hệ</a>
                                    <a class="nav-link" href="layout-sidenav-light.html">Light Sidenav</a>
                                </nav>
                            </div>

                            <!-- Liên hệ  -->
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapselienhe" aria-expanded="false" aria-controls="collapselienhe">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Liên hệ
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapselienhe" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="quanlylienhe.php">Liên hệ</a>
                                </nav>
                            </div>

                            <!-- Đơn hàng  -->
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsedonhang" aria-expanded="false" aria-controls="collapsedonhang">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Đơn hàng
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapsedonhang" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="quanlydonhang.php">Đơn hàng</a>
                                </nav>
                            </div>

                            <!-- Chat  -->
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsechat" aria-expanded="false" aria-controls="collapsechat">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Chat
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapsechat" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="chat.php">Chat</a>
                                </nav>
                            </div>


                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                Pages
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Authentication
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="login.html">Login</a>
                                            <a class="nav-link" href="register.html">Register</a>
                                            <a class="nav-link" href="password.html">Forgot Password</a>
                                        </nav>
                                    </div>
                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                                        Error
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="401.html">401 Page</a>
                                            <a class="nav-link" href="404.html">404 Page</a>
                                            <a class="nav-link" href="500.html">500 Page</a>
                                        </nav>
                                    </div>
                                </nav>
                            </div>
                            <div class="sb-sidenav-menu-heading">Addons</div>
                            <a class="nav-link" href="charts.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                Charts
                            </a>
                            <a class="nav-link" href="tables.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Tables
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        Start Bootstrap
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <ol class="breadcrumb mb-4">
                        </ol>
                        <div class="row">
                        <?php
                            include 'db_connection.php'; // Kết nối cơ sở dữ liệu

                            // Truy vấn SQL để lấy tổng số sản phẩm
                            $sql = "SELECT COUNT(*) AS total_products FROM products";
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($result);

                            // Lấy tổng số lượng sản phẩm
                            $total_products = $row['total_products'];
                            ?>

                            <!-- HTML phần card -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">Tổng Sản Phẩm: <?php echo $total_products; ?></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="danhsachsanpham.php">Xem chi tiết</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>

                            <!-- tổng danh sách bài viết -->

                            <?php
                            include 'db_connection.php'; // Kết nối cơ sở dữ liệu

                            // Truy vấn SQL để lấy tổng số bài viết
                            $sql = "SELECT COUNT(*) AS total_articles FROM articles";
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($result);

                            // Lấy tổng số bài viết
                            $total_articles = $row['total_articles'];
                            ?>

                            <!-- HTML phần card -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body">Tổng số bài viết: <?php echo $total_articles; ?></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="danhsachbaiviet.php">Xem chi tiết</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- tổng số khách hàng -->
                    
                            <?php
                            include 'db_connection.php'; // Kết nối cơ sở dữ liệu

                            // Truy vấn SQL để lấy tổng số khách hàng
                            $sql = "SELECT COUNT(*) AS total_customers FROM users WHERE role = 'user'";
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($result);

                            // Lấy tổng số khách hàng
                            $total_customers = $row['total_customers'];
                            ?>

                            <!-- HTML phần card -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">Tổng số khách hàng: <?php echo $total_customers; ?></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="danhsachkhachhang.php">Xem chi tiết</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tổng số đơn hàng -->

                            <?php
                            include 'db_connection.php'; // Kết nối cơ sở dữ liệu

                            // Truy vấn tổng số đơn hàng
                            $sql = "SELECT COUNT(*) AS total_orders FROM orders";
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($result);

                            // Lấy tổng số đơn hàng
                            $total_orders = $row['total_orders'];
                            ?>

                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">Tổng số đơn hàng: <?php echo $total_orders; ?></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">Xem chi tiết</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Thống kê doanh thu -->
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-area me-1"></i>
                                        Thống Kê Doanh Thu
                                    </div>
                                    <div class="card-body">
                                        <!-- Biểu đồ diện tích -->
                                        <canvas id="myAreaChart" width="100%" height="40"></canvas>
                                    </div>
                                </div>
                            </div>

                            <!-- Card để chứa biểu đồ -->
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Thống Kê Đơn Hàng Theo Trạng Thái
                                    </div>
                                    <div class="card-body">
                                        <!-- Biểu đồ thanh -->
                                        <canvas id="myBarChart" width="100%" height="40"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-xl-6">
    <div class="card mb-4">
        <div class="card-header">
            <!-- Thay đổi biểu tượng ở đây -->
            <i class="fas fa-chart-line me-1"></i> <!-- Đây là biểu tượng mới -->
            Thống Kê Truy Cập Theo Tháng
        </div>
        <div class="card-body">
            <!-- Biểu đồ thanh -->
            <canvas id="accessChart" width="100%" height="40"></canvas>
        </div>
    </div>
</div>
<?php
$sql = "SELECT access_date, ip_address, user_agent, user_name FROM access_logs ORDER BY access_date DESC";
$result = mysqli_query($conn, $sql);
?>
<div class="col-xl-6">
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-history me-1"></i>
            Lịch Sử Truy Cập
        </div>
        <div class="card-body">
            <!-- Bảng hiển thị lịch sử truy cập -->
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Tên</th>
                        <th>Ngày Giờ Truy Cập</th>
                        <th>Địa Chỉ IP</th>
                        <th>Trình Duyệt</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row['user_name']; ?></td>
                            <td><?php echo $row['access_date']; ?></td>
                            <td><?php echo $row['ip_address']; ?></td>
                            <td><?php echo $row['user_agent']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
                        </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2023</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
        <script>
            // Dữ liệu doanh thu từ PHP
            var dateLabels = <?php echo json_encode($dates); ?>;
            var revenueData = <?php echo json_encode($revenues); ?>;

            // Lấy phần tử canvas
            var ctx = document.getElementById('myAreaChart').getContext('2d');

            // Vẽ biểu đồ diện tích (Area Chart) bằng Chart.js
            var myAreaChart = new Chart(ctx, {
                type: 'line', // Biểu đồ loại line (để tạo hiệu ứng diện tích)
                data: {
                    labels: dateLabels, // Ngày
                    datasets: [{
                        label: 'Doanh Thu (VND)',
                        data: revenueData, // Dữ liệu doanh thu
                        fill: true, // Hiệu ứng fill để tạo diện tích
                        borderColor: 'rgba(75, 192, 192, 1)', // Màu đường biên
                        backgroundColor: 'rgba(75, 192, 192, 0.2)', // Màu nền (diện tích bên dưới đường)
                        tension: 0.4 // Độ cong của đường
                    }]
                },
                options: {
                    responsive: true, // Biểu đồ sẽ tự động thay đổi kích thước theo độ phân giải
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return 'Doanh thu: ' + tooltipItem.raw.toLocaleString() + ' VND'; // Hiển thị tooltip dạng VND
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true, // Bắt đầu trục y từ 0
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString() + ' VND'; // Hiển thị doanh thu dạng VND
                                }
                            }
                        }
                    }
                }
            });
        </script>

        <script>
            // Dữ liệu từ PHP
            var orderStatuses = <?php echo json_encode($order_statuses); ?>;
            var orderCounts = <?php echo json_encode($order_counts); ?>;

            // Lấy phần tử canvas
            var ctx = document.getElementById('myBarChart').getContext('2d');

            // Vẽ biểu đồ thanh (Bar Chart) bằng Chart.js
            var myBarChart = new Chart(ctx, {
                type: 'bar', // Biểu đồ thanh
                data: {
                    labels: orderStatuses, // Các trạng thái đơn hàng
                    datasets: [{
                        label: 'Số Lượng Đơn Hàng',
                        data: orderCounts, // Dữ liệu số lượng đơn hàng
                        backgroundColor: 'rgba(75, 192, 192, 0.5)', // Màu nền của thanh
                        borderColor: 'rgba(75, 192, 192, 1)', // Màu biên của thanh
                        borderWidth: 1 // Độ dày của biên thanh
                    }]
                },
                options: {
                    responsive: true, // Biểu đồ sẽ tự động thay đổi kích thước theo độ phân giải
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return 'Số lượng: ' + tooltipItem.raw; // Hiển thị tooltip với số lượng đơn hàng
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true, // Bắt đầu trục y từ 0
                            ticks: {
                                stepSize: 1, // Thiết lập khoảng cách giữa các nhãn trên trục Y là số nguyên
                                callback: function(value) {
                                    return Number.isInteger(value) ? value : ''; // Hiển thị chỉ số nguyên, bỏ qua số thập phân
                                }
                            }
                        }
                    }
                }
            });
        </script>

<script>
    // Dữ liệu từ PHP
    var months = <?php echo json_encode($months); ?>;
    var totalAccesses = <?php echo json_encode($total_accesses); ?>;

    // Lấy phần tử canvas
    var ctx = document.getElementById('accessChart').getContext('2d'); // Cập nhật id canvas

    // Vẽ biểu đồ thanh (Bar Chart) bằng Chart.js
    var accessChart = new Chart(ctx, { // Cập nhật tên biến
        type: 'bar', // Biểu đồ thanh
        data: {
            labels: months.map(function(month) {
                // Chuyển đổi tháng (1-12) thành tên tháng
                var date = new Date(2024, month - 1); // Tạo đối tượng Date cho tháng
                return date.toLocaleString('default', { month: 'long' }); // Chuyển thành tên tháng
            }), // Các nhãn tháng (ví dụ: "January", "February", ...)
            datasets: [{
                label: 'Số Lượt Truy Cập',
                data: totalAccesses, // Dữ liệu số lượt truy cập
                backgroundColor: 'rgba(75, 192, 192, 0.5)', // Màu nền của thanh
                borderColor: 'rgba(75, 192, 192, 1)', // Màu biên của thanh
                borderWidth: 1 // Độ dày của biên thanh
            }]
        },
        options: {
            responsive: true, // Biểu đồ sẽ tự động thay đổi kích thước theo độ phân giải
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return 'Truy Cập: ' + tooltipItem.raw; // Hiển thị tooltip với số lượt truy cập
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true, // Bắt đầu trục y từ 0
                    ticks: {
                        stepSize: 1, // Thiết lập khoảng cách giữa các nhãn trên trục Y là số nguyên
                        callback: function(value) {
                            return value; // Hiển thị số nguyên trên trục Y
                        }
                    }
                }
            }
        }
    });
</script>




    </body>
</html>
