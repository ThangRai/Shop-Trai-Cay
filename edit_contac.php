<?php
include 'db_connection.php'; // Kết nối tới cơ sở dữ liệu

// Lấy `id` từ URL nếu có
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$contact = null;

// Nếu có `id`, lấy thông tin liên hệ từ CSDL
if ($id) {
    $query = "SELECT * FROM contact_info WHERE id = $id";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $contact = mysqli_fetch_assoc($result);
    } else {
        echo "Thông tin liên hệ không tồn tại.";
        exit;
    }
}

// Xử lý khi gửi biểu mẫu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contact_name = mysqli_real_escape_string($conn, $_POST['contact_name']);
    $contact_detail = mysqli_real_escape_string($conn, $_POST['contact_detail']);
    
    // Xử lý upload ảnh
    $icon_image = $contact ? $contact['icon_image'] : ''; // Giữ ảnh cũ nếu không upload mới
    if (!empty($_FILES['icon_image']['name'])) {
        $target_dir = "uploads/";
        $icon_image = basename($_FILES['icon_image']['name']);
        $target_file = $target_dir . $icon_image;

        // Kiểm tra loại file và tải lên
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (in_array($file_extension, $allowed_extensions)) {
            move_uploaded_file($_FILES['icon_image']['tmp_name'], $target_file);
        } else {
            echo "Chỉ được phép tải lên file ảnh (JPG, PNG, GIF).";
            exit;
        }
    }

    // Cập nhật hoặc thêm thông tin vào CSDL
    if ($id) {
        // Cập nhật thông tin
        $query = "UPDATE contact_info 
                  SET contact_name = '$contact_name', 
                      contact_detail = '$contact_detail', 
                      icon_image = '$icon_image' 
                  WHERE id = $id";
    } else {
        // Thêm mới thông tin
        $query = "INSERT INTO contact_info (contact_name, contact_detail, icon_image) 
                  VALUES ('$contact_name', '$contact_detail', '$icon_image')";
    }
    if (mysqli_query($conn, $query)) {
        header("Location: thongtinlienhe.php");
        exit;
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id ? 'Sửa Thông Tin Liên Hệ' : 'Thêm Thông Tin Liên Hệ'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center"><?php echo $id ? 'Sửa Thông Tin Liên Hệ' : 'Thêm Thông Tin Liên Hệ'; ?></h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="contact_name" class="form-label">Tên Liên Hệ</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="contact_name" 
                    name="contact_name" 
                    value="<?php echo $contact ? htmlspecialchars($contact['contact_name']) : ''; ?>" 
                    required>
            </div>
            <div class="mb-3">
                <label for="contact_detail" class="form-label">Chi Tiết</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="contact_detail" 
                    name="contact_detail" 
                    value="<?php echo $contact ? htmlspecialchars($contact['contact_detail']) : ''; ?>" 
                    required>
            </div>
            <div class="mb-3">
                <label for="icon_image" class="form-label">Ảnh Icon</label>
                <input type="file" class="form-control" id="icon_image" name="icon_image">
                <?php if ($contact && $contact['icon_image']): ?>
                    <div class="mt-3">
                        <img src="uploads/<?php echo $contact['icon_image']; ?>" alt="Icon" width="100">
                        <p class="text-muted">Ảnh hiện tại</p>
                    </div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-success"><?php echo $id ? 'Cập Nhật' : 'Thêm'; ?></button>
            <a href="thongtinlienhe.php" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
