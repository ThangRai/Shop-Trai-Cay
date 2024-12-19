<?php
// Kết nối cơ sở dữ liệu
include 'db_connection.php';

// Kiểm tra nếu có ID bài viết
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Lấy thông tin bài viết
    $sql = "SELECT * FROM articles WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $article = mysqli_fetch_assoc($result);
    } else {
        echo "Bài viết không tồn tại!";
        exit();
    }
} else {
    echo "Không có bài viết để hiển thị!";
    exit();
}

// Đóng kết nối cơ sở dữ liệu
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $article['title']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1><?php echo $article['title']; ?></h1>
        <p class="text-muted">Tác giả: <?php echo $article['author']; ?> | Ngày đăng: <?php echo date('d/m/Y', strtotime($article['published_date'])); ?></p>
        <img src="uploads/<?php echo $article['image']; ?>" class="img-fluid" alt="<?php echo $article['title']; ?>">
        <p class="mt-4"><?php echo nl2br($article['content']); ?></p>
        <a href="tintuc.php" class="btn btn-secondary">Quay lại danh sách</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
