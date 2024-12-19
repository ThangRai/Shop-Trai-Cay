<?php
include 'db_connection.php'; // Kết nối cơ sở dữ liệu

// Xử lý yêu cầu xóa bằng Ajax
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $sql = "DELETE FROM contacts WHERE id = $delete_id";
    if (mysqli_query($conn, $sql)) {
        echo "success"; // Xóa thành công
    } else {
        echo "error"; // Lỗi khi xóa
    }
    exit;
}

// Lấy danh sách liên hệ từ bảng contacts
$contacts = mysqli_query($conn, "SELECT * FROM contacts ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Liên Hệ</title>
    <!-- Thêm Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        h2 {
            text-align: center;
            margin: 20px 0;
            color: #343a40;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }

        .popup-confirm {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .popup-box {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .popup-buttons button {
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Quản Lý Liên Hệ</h2>
        
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Điện Thoại</th>
                        <th>Nội Dung</th>
                        <th>Ngày Tạo</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody id="contactsTable">
                    <?php while ($contact = mysqli_fetch_assoc($contacts)) { ?>
                        <tr id="contact-<?php echo $contact['id']; ?>">
                            <td><?php echo $contact['id']; ?></td>
                            <td><?php echo htmlspecialchars($contact['name']); ?></td>
                            <td><?php echo htmlspecialchars($contact['email']); ?></td>
                            <td><?php echo htmlspecialchars($contact['phone']); ?></td>
                            <td><?php echo htmlspecialchars($contact['message']); ?></td>
                            <td><?php echo date('d-m-Y H:i:s', strtotime($contact['created_at'])); ?></td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $contact['id']; ?>)">Xóa</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Popup xác nhận -->
    <div class="popup-confirm" id="popupConfirm">
        <div class="popup-box">
            <p>Bạn có chắc chắn muốn xóa liên hệ này không?</p>
            <div class="popup-buttons">
                <button class="btn btn-secondary" onclick="closePopup()">Hủy</button>
                <button class="btn btn-danger" id="confirmDeleteButton">Đồng ý</button>
            </div>
        </div>
    </div>

    <!-- Thêm Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Thêm JavaScript -->
    <script>
        let deleteId = null; // Lưu ID liên hệ cần xóa

        // Hàm mở popup xác nhận
        function confirmDelete(id) {
            deleteId = id;
            document.getElementById('popupConfirm').style.display = 'flex';
        }

        // Hàm đóng popup
        function closePopup() {
            deleteId = null;
            document.getElementById('popupConfirm').style.display = 'none';
        }

        // Xóa liên hệ khi người dùng nhấn "Đồng ý"
        document.getElementById('confirmDeleteButton').addEventListener('click', function () {
            if (deleteId) {
                // Gửi yêu cầu xóa qua Ajax
                fetch('', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `delete_id=${deleteId}`
                })
                .then(response => response.text())
                .then(result => {
                    if (result === 'success') {
                        // Xóa hàng tương ứng trong bảng
                        const row = document.getElementById(`contact-${deleteId}`);
                        if (row) row.remove();
                        alert('Xóa liên hệ thành công!');
                    } else {
                        alert('Có lỗi xảy ra khi xóa liên hệ.');
                    }
                    closePopup();
                })
                .catch(error => {
                    alert('Có lỗi xảy ra trong quá trình xử lý.');
                    console.error(error);
                    closePopup();
                });
            }
        });
    </script>
</body>
</html>
