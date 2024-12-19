<?php
session_start(); // Bắt đầu session

include 'db_connection.php'; // Kết nối cơ sở dữ liệu

// Xử lý thêm bài viết
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_article'])) {
    // Lấy dữ liệu từ form
    $title = $_POST['title'];
    $description = $_POST['description'];
    $content = $_POST['content'];
    $author = $_POST['author'];
    $published_date = date('Y-m-d H:i:s');

    // Xử lý ảnh
    $image = $_FILES['image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

    // Thêm bài viết vào cơ sở dữ liệu
    $sql = "INSERT INTO articles (title, description, content, author, published_date, image) 
            VALUES ('$title', '$description', '$content', '$author', '$published_date', '$image')";
    mysqli_query($conn, $sql);
    header("Location: danhsachbaiviet.php");
}

// Xử lý sửa bài viết
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_article'])) {
    // Lấy dữ liệu từ form
    $article_id = $_POST['article_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $content = $_POST['content'];
    $author = $_POST['author'];

    // Kiểm tra nếu có ảnh mới thì xử lý ảnh
    if ($_FILES['image']['name']) {
        $image = $_FILES['image']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    } else {
        // Nếu không có ảnh mới, giữ nguyên ảnh cũ
        $sql = "SELECT image FROM articles WHERE id = $article_id";
        $result = mysqli_query($conn, $sql);
        $article = mysqli_fetch_assoc($result);
        $image = $article['image'];
    }

    // Cập nhật bài viết trong cơ sở dữ liệu
    $sql = "UPDATE articles SET title = '$title', description = '$description', content = '$content', author = '$author', image = '$image' WHERE id = $article_id";
    mysqli_query($conn, $sql);
    header("Location: danhsachbaiviet.php");
}

// Xử lý xóa bài viết
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM articles WHERE id = $delete_id";
    mysqli_query($conn, $sql);
    header("Location: danhsachbaiviet.php");
}

// Lấy danh sách bài viết từ cơ sở dữ liệu
$sql = "SELECT * FROM articles ORDER BY published_date DESC";
$articles = mysqli_query($conn, $sql);

// Nếu có yêu cầu chỉnh sửa bài viết
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $sql = "SELECT * FROM articles WHERE id = $edit_id";
    $edit_article = mysqli_fetch_assoc(mysqli_query($conn, $sql));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Bài Viết</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Danh Sách Bài Viết</h2>

        <!-- Form Thêm Bài Viết -->
        <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addArticleModal">Thêm Bài Viết</button>

        <!-- Danh Sách Bài Viết -->
        <div class="row">
            <?php while ($article = mysqli_fetch_assoc($articles)) { ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="uploads/<?php echo $article['image']; ?>" class="card-img-top" alt="<?php echo $article['title']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $article['title']; ?></h5>
                            <p class="card-text"><?php echo $article['description']; ?></p>
                            <p class="text-muted">Tác giả: <?php echo $article['author']; ?></p>
                            <p class="text-muted">Ngày đăng: <?php echo date('d/m/Y', strtotime($article['published_date'])); ?></p>
                            <a href="sua_bai_viet.php?edit_id=<?php echo $article['id']; ?>" class="btn btn-warning">Sửa</a>
                            <a href="danhsachbaiviet.php?delete_id=<?php echo $article['id']; ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa bài viết này?');">Xóa</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Modal Thêm Bài Viết -->
    <div class="modal fade" id="addArticleModal" tabindex="-1" aria-labelledby="addArticleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addArticleModalLabel">Thêm Bài Viết</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="danhsachbaiviet.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="title" class="form-label">Tiêu đề</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Nội dung</label>
                            <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="author" class="form-label">Tác giả</label>
                            <input type="text" class="form-control" id="author" name="author" required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Ảnh đại diện</label>
                            <input type="file" class="form-control" id="image" name="image" required>
                        </div>
                        <button type="submit" name="add_article" class="btn btn-primary">Thêm Bài Viết</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Sửa Bài Viết -->
    <?php if (isset($edit_article)) { ?>
    <div class="modal fade" id="editArticleModal" tabindex="-1" aria-labelledby="editArticleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editArticleModalLabel">Sửa Bài Viết</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="danhsachbaiviet.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="article_id" value="<?php echo $edit_article['id']; ?>">
                        <div class="mb-3">
                            <label for="title" class="form-label">Tiêu đề</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo $edit_article['title']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required><?php echo $edit_article['description']; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Nội dung</label>
                            <textarea class="form-control" id="content" name="content" rows="5" required><?php echo $edit_article['content']; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="author" class="form-label">Tác giả</label>
                            <input type="text" class="form-control" id="author" name="author" value="<?php echo $edit_article['author']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Ảnh đại diện</label>
                            <input type="file" class="form-control" id="image" name="image">
                            <img src="uploads/<?php echo $edit_article['image']; ?>" class="img-fluid mt-3" alt="Image">
                        </div>
                        <button type="submit" name="edit_article" class="btn btn-primary">Cập Nhật Bài Viết</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

    <!-- Thêm Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
