<?php
include 'db_connection.php';

// Xử lý thêm slide
if (isset($_POST['add_slide'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Upload ảnh
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["slide_image"]["name"]);
    move_uploaded_file($_FILES["slide_image"]["tmp_name"], $target_file);

    // Lưu slide vào cơ sở dữ liệu
    $query = "INSERT INTO slides (slide_title, slide_image, slide_description) VALUES ('$title', '$target_file', '$description')";
    if (mysqli_query($conn, $query)) {
        echo "Slide added successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Xử lý sửa slide
if (isset($_POST['edit_slide'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Nếu có file ảnh mới
    if ($_FILES["slide_image"]["name"]) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["slide_image"]["name"]);
        move_uploaded_file($_FILES["slide_image"]["tmp_name"], $target_file);
        $query = "UPDATE slides SET slide_title='$title', slide_image='$target_file', slide_description='$description' WHERE id=$id";
    } else {
        // Nếu không thay đổi ảnh
        $query = "UPDATE slides SET slide_title='$title', slide_description='$description' WHERE id=$id";
    }

    if (mysqli_query($conn, $query)) {
        echo "Slide updated successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Xử lý xoá slide
if (isset($_GET['delete_slide'])) {
    $id = $_GET['delete_slide'];
    $query = "DELETE FROM slides WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        echo "Slide deleted successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Lấy danh sách slides
$slides = mysqli_query($conn, "SELECT * FROM slides");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slide Show</title>
    <!-- Thêm Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Slide Show</h2>

        <!-- Form thêm slide -->
        <form action="slideshow.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Slide Title</label>
                <input type="text" class="form-control" name="title" id="title" required>
            </div>
            <div class="mb-3">
                <label for="slide_image" class="form-label">Slide Image</label>
                <input type="file" class="form-control" name="slide_image" id="slide_image" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" name="description" id="description" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="add_slide">Add Slide</button>
        </form>

        <!-- Danh sách slide -->
        <h3 class="mt-5">All Slides</h3>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($slide = mysqli_fetch_assoc($slides)) { ?>
                    <tr>
                        <td><?php echo $slide['id']; ?></td>
                        <td><?php echo $slide['slide_title']; ?></td>
                        <td><img src="<?php echo $slide['slide_image']; ?>" alt="Slide Image" width="100"></td>
                        <td><?php echo $slide['slide_description']; ?></td>
                        <td>
                            <!-- Sửa slide -->
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $slide['id']; ?>">Edit</button>

                            <!-- Xoá slide -->
                            <a href="slideshow.php?delete_slide=<?php echo $slide['id']; ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>

                    <!-- Modal sửa slide -->
                    <div class="modal fade" id="editModal<?php echo $slide['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Slide</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="slideshow.php" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="id" value="<?php echo $slide['id']; ?>">
                                        <div class="mb-3">
                                            <label for="title" class="form-label">Slide Title</label>
                                            <input type="text" class="form-control" name="title" id="title" value="<?php echo $slide['slide_title']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="slide_image" class="form-label">Slide Image</label>
                                            <input type="file" class="form-control" name="slide_image" id="slide_image">
                                            <img src="<?php echo $slide['slide_image']; ?>" alt="Slide Image" width="100" class="mt-2">
                                        </div>
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" name="description" id="description" rows="3" required><?php echo $slide['slide_description']; ?></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary" name="edit_slide">Update Slide</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Thêm Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
