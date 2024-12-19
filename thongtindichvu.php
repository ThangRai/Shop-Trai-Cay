<?php
session_start();
include 'db_connection.php'; // Kết nối cơ sở dữ liệu

// Xử lý thêm dịch vụ
if (isset($_POST['add_service'])) {
    $title = $_POST['service_title'];
    $description = $_POST['service_description'];
    
    // Xử lý upload ảnh
    if (isset($_FILES['service_image']) && $_FILES['service_image']['error'] == 0) {
        $image_name = $_FILES['service_image']['name'];
        $image_tmp = $_FILES['service_image']['tmp_name'];
        $image_type = pathinfo($image_name, PATHINFO_EXTENSION);
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($image_type, $allowed_types)) {
            $image_path = 'uploads/' . uniqid() . '.' . $image_type;
            move_uploaded_file($image_tmp, $image_path);
        } else {
            echo "Chỉ hỗ trợ các định dạng ảnh JPG, JPEG, PNG, GIF.";
        }
    }

    // Lưu thông tin vào cơ sở dữ liệu
    $sql = "INSERT INTO services (service_image, service_title, service_description) 
            VALUES ('$image_path', '$title', '$description')";
    mysqli_query($conn, $sql);
}

// Xử lý sửa dịch vụ
if (isset($_POST['edit_service'])) {
    $id = $_POST['service_id'];
    $title = $_POST['service_title'];
    $description = $_POST['service_description'];
    $image_path = $_POST['existing_image']; // Lấy đường dẫn ảnh cũ

    // Nếu có ảnh mới được tải lên
    if (isset($_FILES['service_image']) && $_FILES['service_image']['error'] == 0) {
        $image_name = $_FILES['service_image']['name'];
        $image_tmp = $_FILES['service_image']['tmp_name'];
        $image_type = pathinfo($image_name, PATHINFO_EXTENSION);
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($image_type, $allowed_types)) {
            $image_path = 'uploads/' . uniqid() . '.' . $image_type;
            move_uploaded_file($image_tmp, $image_path);
        } else {
            echo "Chỉ hỗ trợ các định dạng ảnh JPG, JPEG, PNG, GIF.";
        }
    }

    // Cập nhật dịch vụ
    $sql = "UPDATE services SET service_image = '$image_path', service_title = '$title', service_description = '$description' WHERE id = $id";
    mysqli_query($conn, $sql);
}

// Xử lý xóa dịch vụ
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM services WHERE id = $id";
    mysqli_query($conn, $sql);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Dịch Vụ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <h2>Quản Lý Dịch Vụ</h2>

        <!-- Form thêm mới dịch vụ -->
        <form action="thongtindichvu.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="service_title" class="form-label">Tiêu Đề Dịch Vụ</label>
                <input type="text" class="form-control" id="service_title" name="service_title" required>
            </div>
            <div class="mb-3">
                <label for="service_description" class="form-label">Mô Tả Dịch Vụ</label>
                <textarea class="form-control" id="service_description" name="service_description" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="service_image" class="form-label">Ảnh Đại Diện</label>
                <input type="file" class="form-control" id="service_image" name="service_image" accept="image/*" required>
            </div>
            <button type="submit" name="add_service" class="btn btn-primary">Thêm Dịch Vụ</button>
        </form>

        <hr>

        <!-- Danh sách dịch vụ -->
        <h3>Danh Sách Dịch Vụ</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Ảnh</th>
                    <th>Tiêu Đề</th>
                    <th>Mô Tả</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Lấy danh sách dịch vụ từ cơ sở dữ liệu
                $result = mysqli_query($conn, "SELECT * FROM services");
                while ($service = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td><img src='" . $service['service_image'] . "' alt='" . $service['service_title'] . "' width='100'></td>";
                    echo "<td>" . $service['service_title'] . "</td>";
                    echo "<td>" . $service['service_description'] . "</td>";
                    echo "<td>
                            <a href='thongtindichvu.php?edit=" . $service['id'] . "' class='btn btn-warning btn-sm'>Sửa</a>
                            <a href='thongtindichvu.php?delete=" . $service['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Bạn có chắc muốn xóa?\")'>Xóa</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <?php
        // Xử lý sửa dịch vụ
        if (isset($_GET['edit'])) {
            $id = $_GET['edit'];
            $result = mysqli_query($conn, "SELECT * FROM services WHERE id = $id");
            $service = mysqli_fetch_assoc($result);
            ?>

            <!-- Form sửa dịch vụ -->
            <h3>Sửa Dịch Vụ</h3>
            <form action="thongtindichvu.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                <input type="hidden" name="existing_image" value="<?php echo $service['service_image']; ?>">
                <div class="mb-3">
                    <label for="service_title" class="form-label">Tiêu Đề Dịch Vụ</label>
                    <input type="text" class="form-control" id="service_title" name="service_title" value="<?php echo $service['service_title']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="service_description" class="form-label">Mô Tả Dịch Vụ</label>
                    <textarea class="form-control" id="service_description" name="service_description" rows="4" required><?php echo $service['service_description']; ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="service_image" class="form-label">Ảnh Đại Diện</label>
                    <input type="file" class="form-control" id="service_image" name="service_image" accept="image/*">
                    <img src="<?php echo $service['service_image']; ?>" alt="Service Image" width="100" class="mt-3">
                </div>
                <button type="submit" name="edit_service" class="btn btn-primary">Cập Nhật Dịch Vụ</button>
            </form>

            <?php
        }
        ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
