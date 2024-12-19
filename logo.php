<?php
include 'db_connection.php';

// Xử lý thêm logo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_logo'])) {
    $logo_name = $_POST['logo_name'];
    $logo_image = $_FILES['logo_image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($logo_image);
    move_uploaded_file($_FILES['logo_image']['tmp_name'], $target_file);

    $sql = "INSERT INTO logos (logo_name, logo_image) VALUES ('$logo_name', '$logo_image')";
    mysqli_query($conn, $sql);
}

// Xử lý xóa logo
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM logos WHERE id = $id";
    mysqli_query($conn, $sql);
}

// Lấy danh sách logo
$logos = mysqli_query($conn, "SELECT * FROM logos");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Logo</title>
    <style>
        /* Reset some default styles */
        body, h1, table, th, td {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body style */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        /* Form style */
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        input[type="text"], input[type="file"] {
            padding: 10px;
            margin: 10px 0;
            width: 80%;
            max-width: 400px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Table style */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        td img {
            width: 50px;
            height: auto;
        }

        /* Link style */
        a {
            color: #f44336;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            form {
                width: 100%;
            }

            input[type="text"], input[type="file"] {
                width: 90%;
            }

            table {
                font-size: 14px;
            }

            td img {
                width: 40px;
            }
        }
    </style>
</head>
<body>
    <h1>Quản lý Logo</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="text" name="logo_name" placeholder="Tên logo" required>
        <input type="file" name="logo_image" required>
        <button type="submit" name="add_logo">Thêm logo</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên Logo</th>
                <th>Hình ảnh</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($logos)) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['logo_name']; ?></td>
                    <td><img src="uploads/<?php echo $row['logo_image']; ?>" alt="Logo"></td>
                    <td>
                        <a href="?delete_id=<?php echo $row['id']; ?>">Xóa</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
