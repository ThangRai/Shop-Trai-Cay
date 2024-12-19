<?php
include 'db_connection.php'; // Kết nối cơ sở dữ liệu

// Hàm gửi tin nhắn đến Telegram
function sendTelegramMessage($message) {
    $botToken = '6608663537:AAExeC77L9XmTSK3lpW0Q3zt_kGfC1qKZfA';  // Thay thế bằng Token của bạn
    $chatId = '5901907211';      // Thay thế bằng Chat ID của bạn
    $url = "https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chatId&text=" . urlencode($message);

    // Sử dụng cURL để gửi yêu cầu HTTP đến Telegram API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

// Xử lý gửi tin nhắn
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    // Kiểm tra nếu các giá trị này tồn tại trong mảng $_POST
    $user_name = isset($_POST['user_name']) ? $_POST['user_name'] : '';
    $user_phone = isset($_POST['user_phone']) ? $_POST['user_phone'] : '';
    $note = isset($_POST['note']) ? $_POST['note'] : '';

    // Nếu tất cả các trường đều có giá trị
    if (!empty($user_name) && !empty($user_phone) && !empty($note)) {
        // Insert tin nhắn vào cơ sở dữ liệu
        $sql = "INSERT INTO messages (user_name, user_phone, note) VALUES ('$user_name', '$user_phone', '$note')";
        if (mysqli_query($conn, $sql)) {
            $success_message = "Tin nhắn đã được gửi thành công!";
            
            // Gửi thông báo tới Telegram
            $telegram_message = "Bạn có tin nhắn hỗ trợ từ khách hàng:\nTên: $user_name\nSố điện thoại: $user_phone\nGhi chú: $note";
            sendTelegramMessage($telegram_message); // Gửi tin nhắn tới Telegram

            // Redirect lại trang để tránh gửi lại form khi tải lại trang
            header("Location: " . $_SERVER['PHP_SELF']);
            exit(); // Dừng script lại sau khi redirect
        } else {
            $error_message = "Đã có lỗi xảy ra, vui lòng thử lại!";
        }
    } else {
        $error_message = "Vui lòng điền đầy đủ các trường.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Hỗ Trợ Khách Hàng</title>
    <style>
        

        /* Nút hỗ trợ nằm ở góc dưới bên phải */
        .chat-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.3s ease;
        }

        .chat-button:hover {
            background-color: #45a049;
        }

        .chat-button span {
            margin-right: 10px;
            font-size: 24px;
            font-weight: bold;
        }

        /* Hộp chat (ẩn ban đầu) */
        .chat-container {
            position: fixed;
            bottom: 0px;
            right: 20px;
            width: 300px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: none;
            flex-direction: column;
            overflow: hidden;
            height: auto;
            transition: all 0.3s ease;
        }

        .chat-header {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            font-size: 18px;
            text-align: center;
            position: relative;
        }

        .close-chat {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #fff;
            cursor: pointer;
            font-size: 20px;
        }

        /* Form gửi tin nhắn */
        .send-message-form {
            display: flex;
            flex-direction: column;
            padding: 10px;
        }

        .send-message-form input,
        .send-message-form textarea,
        .send-message-form button {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border-radius: 4px;
            border: 1px solid #ddd;
            box-sizing: border-box; /* Bao gồm padding và border trong width */
        }

        .send-message-form button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        .send-message-form button:hover {
            background-color: #45a049;
        }

        .send-message-form input,
        .send-message-form textarea {
            font-size: 14px;
            padding-left: 30px; /* Cung cấp không gian cho icon bên trái */
        }

        /* Icon trong input */
        .input-wrapper {
            position: relative;
        }

        .input-wrapper input, .input-wrapper textarea {
            padding-left: 30px; /* Giới hạn không gian cho icon */
        }

        .input-wrapper i {
            position: absolute;
            left: 10px;
            top: 25px;
            transform: translateY(-50%);
            color: #888;
            z-index: 1000;
        }
    </style>
</head>
<body>

    <!-- Nút "Hỗ Trợ" với icon ba gạch ngang -->
    <button class="chat-button" id="chatButton">
        <span>&#9776;</span> Hỗ Trợ
    </button>

    <!-- Hộp chat -->
    <div class="chat-container" id="chatContainer">
        <div class="chat-header">
            Hỗ Trợ Khách Hàng
            <span class="close-chat" id="closeChat">×</span>
        </div>

        <!-- Form gửi tin nhắn -->
        <form action="profile.php" method="POST" class="send-message-form">
            <div class="input-wrapper">
                <i class="fa fa-user"></i> <!-- Biểu tượng cho tên người dùng -->
                <input type="text" name="user_name" placeholder="Tên của bạn" required>
            </div>

            <div class="input-wrapper">
                <i class="fa fa-phone"></i> <!-- Biểu tượng cho số điện thoại -->
                <input type="text" name="user_phone" placeholder="Số điện thoại" required>
            </div>

            <div class="input-wrapper">
                <i class="fa fa-comment"></i> <!-- Biểu tượng cho ghi chú -->
                <textarea name="note" rows="4" placeholder="Nhập ghi chú..." required></textarea>
            </div>

            <button type="submit" name="send_message">Gửi Tin Nhắn</button>
        </form>
    </div>


    <script>
        // Hiển thị hoặc ẩn hộp chat khi bấm vào nút "Hỗ Trợ"
        var chatButton = document.getElementById('chatButton');
        var chatContainer = document.getElementById('chatContainer');
        var closeChat = document.getElementById('closeChat');

        chatButton.onclick = function() {
            // Toggle hiển thị/ẩn hộp chat
            if (chatContainer.style.display === 'none' || chatContainer.style.display === '') {
                chatContainer.style.display = 'flex';
                // Ẩn nút "Hỗ Trợ" khi hộp chat hiện
                chatButton.classList.add('hide');
            } else {
                chatContainer.style.display = 'none';
                // Hiển thị lại nút "Hỗ Trợ" khi hộp chat ẩn
                chatButton.classList.remove('hide');
            }
        }

        closeChat.onclick = function() {
            // Ẩn hộp chat khi bấm vào "×"
            chatContainer.style.display = 'none';
            // Hiển thị lại nút "Hỗ Trợ" khi hộp chat ẩn
            chatButton.classList.remove('hide');
        }

        // Hiển thị popup thông báo thành công hoặc thất bại
        <?php if (isset($success_message) || isset($error_message)) { ?>
            var popup = document.getElementById("popupMessage");
            popup.style.display = "block";
            setTimeout(function() {
                popup.style.display = "none";
            }, 3000); // Ẩn popup sau 3 giây
        <?php } ?>

    </script>

</body>
</html>
