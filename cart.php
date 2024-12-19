<?php
session_start();
include 'db_connection.php'; // Kết nối cơ sở dữ liệu


// Lấy danh sách menu từ cơ sở dữ liệu
$menus = mysqli_query($conn, "SELECT * FROM menus");

// Lấy danh sách logo từ cơ sở dữ liệu
$logos = mysqli_query($conn, "SELECT * FROM logos");


// Kiểm tra nếu sản phẩm được thêm vào giỏ hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);

    // Nếu sản phẩm chưa có trong giỏ hàng, đặt số lượng ban đầu là 1
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = 1;
    } else {
        // Nếu sản phẩm đã có, tăng số lượng
        $_SESSION['cart'][$product_id]++;
    }

    // Trả về phản hồi (nếu dùng AJAX, có thể gửi JSON hoặc văn bản)
    echo "Sản phẩm đã được thêm vào giỏ hàng.";
    exit();
}


// Xử lý cập nhật số lượng sản phẩm trong giỏ hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $product_id => $quantity) {
        $product_id = intval($product_id);
        $quantity = intval($quantity);
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$product_id]); // Xóa sản phẩm nếu số lượng <= 0
        } else {
            $_SESSION['cart'][$product_id] = $quantity; // Cập nhật số lượng
        }
    }
}

// Kiểm tra giỏ hàng rỗng
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<h3>Giỏ hàng của bạn đang trống!</h3>";
    echo '<a href="index.php" class="btn btn-primary">Tiếp tục mua sắm</a>';
    exit();
}

// Truy vấn thông tin sản phẩm trong giỏ hàng
$product_ids = implode(',', array_keys($_SESSION['cart']));
$query = "SELECT * FROM products WHERE id IN ($product_ids)";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Lỗi truy vấn: " . mysqli_error($conn));
}

$cart_items = [];
while ($row = mysqli_fetch_assoc($result)) {
    $cart_items[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="cart.css">
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

    <div class="container mt-5">
        <h2>Giỏ hàng của bạn</h2>
        <form action="cart.php" method="POST">
            <input type="hidden" name="update_cart" value="1">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Hình ảnh</th>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tổng giá</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total_price = 0; 
                    foreach ($cart_items as $item) { 
                        $product_id = $item['id'];
                        $quantity = $_SESSION['cart'][$product_id];
                        $subtotal = $item['product_current_price'] * $quantity;
                        $total_price += $subtotal;
                    ?>
                    <tr>
                        <td><img src="<?php echo $item['product_image']; ?>" alt="<?php echo $item['product_name']; ?>" style="width: 80px; height: auto;"></td>
                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td><?php echo number_format($item['product_current_price'], 0, ',', '.') . ' VND'; ?></td>
                        <td>
                            <input type="number" name="quantities[<?php echo $product_id; ?>]" value="<?php echo $quantity; ?>" min="0" class="form-control" style="width: 80px;">
                        </td>
                        <td><?php echo number_format($subtotal, 0, ',', '.') . ' VND'; ?></td>
                        <td>
                            <a href="remove_from_cart.php?product_id=<?php echo $product_id; ?>" class="btn btn-danger btn-sm">Xóa</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <div class="d-flex justify-content-between">
                <h4>Tổng cộng: <?php echo number_format($total_price, 0, ',', '.') . ' VND'; ?></h4>
                <div>
                    <a href="sanpham.php" class="btn btn-secondary">Tiếp tục mua sắm</a>
                    <button type="submit" class="btn btn-primary">Cập nhật giỏ hàng</button>
                    <a href="checkout.php" class="btn btn-success">Thanh toán</a>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <?php
include 'top.php'; // Kết nối cơ sở dữ liệu 
include 'buttonlienhe.php'; // Kết nối cơ sở dữ liệu 
include 'footer.php'; // Kết nối cơ sở dữ liệu
?>
</body>
</html>
