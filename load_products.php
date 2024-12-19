<?php
include 'db_connection.php'; // Kết nối cơ sở dữ liệu

// Cấu hình phân trang
$products_per_page = 12; // Số lượng sản phẩm trên mỗi trang
$current_page = isset($_GET['page']) ? $_GET['page'] : 1; // Lấy số trang hiện tại
$offset = ($current_page - 1) * $products_per_page; // Tính vị trí bắt đầu sản phẩm

// Lấy danh sách sản phẩm từ cơ sở dữ liệu với phân trang
$sql = "SELECT * FROM products LIMIT $offset, $products_per_page";
$products = mysqli_query($conn, $sql);

// Hiển thị sản phẩm
while ($product = mysqli_fetch_assoc($products)) {
    // Tính phần trăm giảm giá
    $discount = 0;
    if ($product['product_price'] > 0) {
        $discount = round(((($product['product_price'] - $product['product_current_price']) / $product['product_price']) * 100), 2);
    }
    ?>
    <div class="col-md-3">
        <div class="product-card">
            <div class="position-relative">
                <!-- Hiển thị % giảm giá -->
                <?php if ($discount > 0) { ?>
                    <span class="discount-badge">-<?php echo $discount; ?>%</span>
                <?php } ?>
                <img src="<?php echo $product['product_image']; ?>" alt="<?php echo $product['product_name']; ?>" class="product-image">
            </div>
            <div class="product-name"><?php echo $product['product_name']; ?></div>
            
            <!-- Sử dụng d-flex để hiển thị giá gốc và giá hiện tại cùng một hàng -->
            <div class="d-flex justify-content-center align-items-center">
                <div class="product-price text-decoration-line-through me-2">
                    <?php echo number_format($product['product_price'], 0, ',', '.') . ' VND'; ?>
                </div>
                <div class="product-current-price text-success">
                    <?php echo number_format($product['product_current_price'], 0, ',', '.') . ' VND'; ?>
                </div>
            </div>
            
            <form action="cart.php" method="POST" class="add-to-cart-form">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <button type="submit" class="btn btn-success add-to-cart-btn">Thêm vào giỏ hàng</button>
            </form>
        </div>
    </div>
<?php } ?>
