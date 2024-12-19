<?php
// Kết nối cơ sở dữ liệu
include 'db_connection.php'; 

// Kiểm tra xem có ID bài viết không
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    
    // Lấy thông tin bài viết cần sửa
    $sql = "SELECT * FROM articles WHERE id = $edit_id";
    $result = mysqli_query($conn, $sql);
    
    // Nếu bài viết tồn tại, lấy dữ liệu bài viết
    if (mysqli_num_rows($result) > 0) {
        $article = mysqli_fetch_assoc($result);
    } else {
        // Nếu không tìm thấy bài viết
        echo "Bài viết không tồn tại!";
        exit();
    }
} else {
    // Nếu không có edit_id trong URL
    echo "Không có bài viết để chỉnh sửa!";
    exit();
}

// Kiểm tra xem người dùng có gửi form sửa không
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $content = $_POST['content'];
    $author = $_POST['author'];
    $image = $_FILES['image']['name'];

    // Cập nhật ảnh nếu có
    if ($image) {
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_folder = 'uploads/' . $image;
        move_uploaded_file($image_tmp, $image_folder);

        // Xóa ảnh cũ nếu có ảnh mới
        if (file_exists('uploads/' . $article['image'])) {
            unlink('uploads/' . $article['image']);
        }
    } else {
        // Nếu không có ảnh mới, giữ nguyên ảnh cũ
        $image = $article['image'];
    }

    // Cập nhật thông tin bài viết
    $update_sql = "UPDATE articles SET 
                    title = '$title', 
                    description = '$description', 
                    content = '$content', 
                    author = '$author', 
                    image = '$image'
                    WHERE id = $edit_id";

    if (mysqli_query($conn, $update_sql)) {
        echo "<script>alert('Cập nhật bài viết thành công!'); window.location.href='danhsachbaiviet.php';</script>";
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
}

// Đóng kết nối cơ sở dữ liệu
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Bài Viết</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Sửa Bài Viết</h2>

        <form action="sua_bai_viet.php?edit_id=<?php echo $edit_id; ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">

            <!-- Tiêu đề -->
            <div class="mb-3">
                <label for="title" class="form-label">Tiêu đề</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo $article['title']; ?>" required>
            </div>

            <!-- Mô tả -->
            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo $article['description']; ?></textarea>
            </div>

            <!-- Nội dung -->
            <div class="mb-3">
                <label for="content" class="form-label">Nội dung</label>
                <textarea class="form-control" id="content" name="content" rows="5" required><?php echo $article['content']; ?></textarea>
            </div>

            <!-- Tác giả -->
            <div class="mb-3">
                <label for="author" class="form-label">Tác giả</label>
                <input type="text" class="form-control" id="author" name="author" value="<?php echo $article['author']; ?>" required>
            </div>

            <!-- Ảnh đại diện -->
            <div class="mb-3">
                <label for="image" class="form-label">Ảnh đại diện</label>
                <input type="file" class="form-control" id="image" name="image">
                <img src="uploads/<?php echo $article['image']; ?>" class="img-fluid mt-3" alt="Image">
            </div>

            <!-- Nút cập nhật -->
            <button type="submit" class="btn btn-primary">Cập nhật bài viết</button>
            <a href="danhsachbaiviet.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
