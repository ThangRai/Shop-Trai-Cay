<?php
include 'db_connection.php';

// Thêm mục menu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_menu'])) {
    $menu_name = $_POST['menu_name'];
    $menu_link = $_POST['menu_link'];
    $sql = "INSERT INTO menus (menu_name, menu_link) VALUES ('$menu_name', '$menu_link')";
    mysqli_query($conn, $sql);
}

// Xóa mục menu
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM menus WHERE id = $id";
    mysqli_query($conn, $sql);
}

// Lấy danh sách menu
$menus = mysqli_query($conn, "SELECT * FROM menus");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Menu</title>
<style>
/* Reset các kiểu mặc định */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body styles */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f2f2f2;
    padding: 20px;
    color: #333;
    line-height: 1.6;
}

/* Tiêu đề trang */
h1 {
    text-align: center;
    font-size: 2.5em;
    margin-bottom: 30px;
    color: #333;
}

/* Form thêm menu */
form {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    margin: 0 auto;
    transition: transform 0.3s ease-in-out;
}

form:hover {
    transform: translateY(-5px);
}

/* Input và button trong form */
input[type="text"], button {
    width: 100%;
    padding: 12px;
    margin: 8px 0;
    border-radius: 6px;
    border: 1px solid #ddd;
    font-size: 1em;
    transition: all 0.3s ease;
}

/* Hiệu ứng khi hover hoặc focus vào input/button */
input[type="text"]:focus, button:focus {
    outline: none;
    border-color: #007bff;
}

button {
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #0056b3;
}

/* Danh sách menu */
.menu-list {
    margin-top: 30px;
}

.menu-item {
    background-color: #fff;
    padding: 15px;
    margin-bottom: 10px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.menu-item span {
    font-size: 1.1em;
    color: #333;
}

.menu-item .menu-name {
    flex: 1;
}

.menu-item .menu-link {
    flex: 1;
    color: #007bff;
    text-decoration: underline;
}

.menu-item .btn {
    padding: 8px 15px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    color: white;
    text-align: center;
    margin-left: 10px;
    transition: background-color 0.3s ease;
}

.edit-btn {
    background-color: #28a745;
}

.edit-btn:hover {
    background-color: #218838;
}

.delete-btn {
    background-color: #dc3545;
}

.delete-btn:hover {
    background-color: #c82333;
}

/* Hiển thị thông báo */
p {
    text-align: center;
    font-size: 1.2em;
    margin-top: 30px;
}

.success {
    color: #28a745;
}

.error {
    color: #dc3545;
}

/* Responsive design */
@media (max-width: 768px) {
    form {
        width: 90%;
    }

    input[type="text"], button {
        width: 100%;
    }

    .menu-item {
        flex-direction: column;
        align-items: flex-start;
    }

    .menu-item span {
        font-size: 1em;
        margin-bottom: 5px;
    }

    .menu-item .btn {
        width: 100%;
        margin-left: 0;
        margin-top: 5px;
    }
}

</style>
</head>
<body>
    <h1>Quản lý Menu</h1>

    <!-- Form thêm menu -->
    <form action="" method="post">
        <input type="text" name="menu_name" placeholder="Tên menu" required>
        <input type="text" name="menu_link" placeholder="Đường dẫn (e.g., about.php)" required>
        <button type="submit" name="add_menu">Thêm Menu</button>
    </form>

    <!-- Danh sách menu -->
    <div class="menu-list">
        <?php while ($menu = mysqli_fetch_assoc($menus)) { ?>
            <div class="menu-item">
                <span class="menu-name"><?php echo $menu['menu_name']; ?></span>
                <span class="menu-link"><?php echo $menu['menu_link']; ?></span>

                <!-- Nút Sửa và Xóa -->
                <a href="#" class="btn edit-btn">Lưu</a>
                <a href="?delete_id=<?php echo $menu['id']; ?>" class="btn delete-btn">Xóa</a>
            </div>
        <?php } ?>
    </div>
</body>
</html>
