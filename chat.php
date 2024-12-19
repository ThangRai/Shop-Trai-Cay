<?php
session_start();
include 'db_connection.php'; // Kết nối cơ sở dữ liệu

// Lấy tất cả tin nhắn từ cơ sở dữ liệu
$sql = "SELECT * FROM messages ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Tin Nhắn Hỗ Trợ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #4CAF50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .message {
            font-size: 14px;
            color: #555;
        }

        .message-time {
            font-size: 0.8em;
            color: #aaa;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Danh Sách Tin Nhắn Hỗ Trợ</h1>

        <!-- Bảng hiển thị danh sách tin nhắn -->
        <table>
            <thead>
                <tr>
                    <th>Tên Người Gửi</th>
                    <th>Số Điện Thoại</th>
                    <th>Ghi Chú</th>
                    <th>Thời Gian</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Kiểm tra xem có tin nhắn nào không
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $user_name = htmlspecialchars($row['user_name']);
                        $user_phone = htmlspecialchars($row['user_phone']);
                        $note = htmlspecialchars($row['note']);
                        $created_at = date("d/m/Y H:i:s", strtotime($row['created_at']));
                ?>
                    <tr>
                        <td><?php echo $user_name; ?></td>
                        <td><?php echo $user_phone; ?></td>
                        <td class="message"><?php echo $note; ?></td>
                        <td class="message-time"><?php echo $created_at; ?></td>
                    </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align:center;'>Không có tin nhắn nào.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
