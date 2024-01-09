<?php
include('config.php');

if (isset($_GET['customer_id'])) {
    $customer_id = $_GET['customer_id'];

    $query = "DELETE FROM table_customer WHERE customer_id = ?";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $customer_id);
        mysqli_stmt_execute($stmt);

        // Redirect ke halaman product list setelah penghapusan
        header("Location: customer_list.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    // Redirect jika parameter tidak tersedia
    header("Location: customer_list.php");
    exit();
}
