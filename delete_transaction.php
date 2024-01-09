<?php
include('config.php');

if (isset($_GET['transaction_id'])) {
    $transaction_id = $_GET['transaction_id'];

    $query = "DELETE FROM table_transaction WHERE transaction_id = ?";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $transaction_id);
        mysqli_stmt_execute($stmt);

        // Redirect ke halaman transaksi setelah penghapusan
        header("Location: transaction.php");
        exit();
    } else {
        header("Location: transaction.php"); // Jika ada kesalahan, arahkan kembali
        exit();
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    // Redirect jika parameter tidak tersedia
    header("Location: transaction.php");
    exit();
}
