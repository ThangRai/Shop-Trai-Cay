<?php
include 'db_connection.php'; // Kết nối CSDL

// Lấy dữ liệu từ bảng contact_info
$query = "SELECT * FROM contact_info";
$result = mysqli_query($conn, $query);
?>
    <style>
        /* Định dạng sidebar */
        .contact-sidebar {
            position: fixed;
            bottom: 10%;
            left: 10px;
            z-index: 1000;
        }

        .contact-item {
            position: relative;
            margin-bottom: 10px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        /* Thêm hiệu ứng rung lắc */
        @keyframes shake {
            0% {
                transform: translateX(0);
            }
            25% {
                transform: translateX(-5px);
            }
            50% {
                transform: translateX(5px);
            }
            75% {
                transform: translateX(-5px);
            }
            100% {
                transform: translateX(0);
            }
        }

        /* Khi hover vào mục, hình ảnh sẽ phóng to và rung lắc */
        .contact-item:hover {
            animation: shake 0.3s ease-in-out;
        }

        .contact-item a {
            display: block;
            width: 100%;
            height: 100%;
            position: relative;
            text-decoration: none;
            color: inherit;
        }

        .contact-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        /* Phóng to hình ảnh khi hover */
        .contact-item:hover img {
            transform: scale(1.1);
        }

        .contact-item span {
            position: absolute;
            top: 50%;
            left: 110%;
            transform: translateY(-50%);
            white-space: nowrap;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease, left 0.3s ease;
        }

        /* Hiện chữ khi hover */
        .contact-item:hover span {
            opacity: 1;
            left: 100%;
        }
    </style>
    <div class="contact-sidebar">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="contact-item">
                <a href="<?php echo $row['contact_detail']; ?>" target="_blank">
                    <img src="uploads/<?php echo $row['icon_image']; ?>" alt="<?php echo $row['contact_name']; ?>">
                    <span><?php echo $row['contact_name']; ?></span>
                </a>
            </div>
        <?php } ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
