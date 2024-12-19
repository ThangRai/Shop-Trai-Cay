<?php
include 'db_connection.php';

if (isset($_GET['q'])) {
    $query = trim(mysqli_real_escape_string($conn, $_GET['q']));
    $results = mysqli_query($conn, "SELECT * FROM products WHERE product_name LIKE '%$query%'");

    echo "<h1>Kết quả tìm kiếm cho: $query</h1>";
    while ($row = mysqli_fetch_assoc($results)) {
        echo "<p>" . $row['product_name'] . "</p>";
    }
}
?>
