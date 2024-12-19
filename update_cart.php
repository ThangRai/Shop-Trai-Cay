<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = intval($_POST['quantity']); // Chuyển đổi số lượng thành số nguyên

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity > 0 ? $quantity : 1; // Đảm bảo số lượng >= 1
    }
}
header('Location: cart.php'); // Quay lại trang giỏ hàng
exit;
?>
