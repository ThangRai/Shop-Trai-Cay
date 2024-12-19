
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<!-- Nút "Lên đầu trang" -->
<div id="back-to-top">
    <button class="scroll-top-btn">
        <i class="fa fa-angle-up"></i>
    </button>
</div>

<style>
    /* Định dạng cho nút "Lên đầu trang" */
#back-to-top {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
    display: none; /* Ẩn nút ban đầu */
}

.scroll-top-btn {
    border-radius: 50%;
    width: 50px;
    height: 50px;
    font-size: 20px;
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s ease, transform 0.3s ease;
}

/* Hiệu ứng hover cho nút */
.scroll-top-btn:hover {
    background-color: #557a29;
    transform: scale(1.1); /* Phóng to nhẹ */
    color: #fff;
    border: none;
}
</style>
<script>
    // JavaScript để xử lý hiển thị nút và cuộn lên đầu trang
document.addEventListener("DOMContentLoaded", function () {
    const backToTop = document.getElementById("back-to-top");

    // Hiển thị nút khi cuộn xuống
    window.addEventListener("scroll", function () {
        if (window.scrollY > 200) { // Nếu cuộn xuống hơn 200px
            backToTop.style.display = "block"; // Hiển thị nút
        } else {
            backToTop.style.display = "none"; // Ẩn nút
        }
    });

    // Xử lý cuộn lên đầu trang khi bấm nút
    backToTop.addEventListener("click", function () {
        window.scrollTo({
            top: 0,
            behavior: "smooth" // Hiệu ứng cuộn mượt
        });
    });
});

</script>