<?php
include('config.php');

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    $query = "DELETE FROM table_product WHERE product_id = ?";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        mysqli_stmt_execute($stmt);

        // Redirect ke halaman product list setelah penghapusan
        header("Location: product_list.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    // Redirect jika parameter tidak tersedia
    header("Location: product_list.php");
    exit();
}
