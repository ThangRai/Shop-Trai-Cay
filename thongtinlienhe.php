<?php
include 'db_connection.php'; // Kết nối CSDL

// Lấy danh sách các thông tin liên hệ
$query = "SELECT * FROM contact_info";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Thông Tin Liên Hệ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Danh Sách Thông Tin Liên Hệ</h2>
        <a href="edit_contac.php" class="btn btn-primary mb-3">Thêm Thông Tin Liên Hệ</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Icon</th>
                    <th>Tên Liên Hệ</th>
                    <th>Chi Tiết</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Hiển thị các thông tin liên hệ
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>{$row['id']}</td>";
                        echo "<td><img src='uploads/{$row['icon_image']}' alt='Icon' width='50'></td>";
                        echo "<td>{$row['contact_name']}</td>";
                        echo "<td>{$row['contact_detail']}</td>";
                        echo "<td>";
                        echo "<a href='edit_contac.php?id={$row['id']}' class='btn btn-warning btn-sm'>Chỉnh sửa</a> ";
                        echo "<a href='delete_contact.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Bạn có chắc chắn muốn xóa?\")'>Xóa</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>Không có thông tin liên hệ nào.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
