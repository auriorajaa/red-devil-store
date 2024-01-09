<!-- save_changes.php -->

<?php
include('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_image = $_POST['product_image'];
    $product_category = $_POST['product_category'];
    $product_stock = $_POST['product_stock'];
    $product_price = $_POST['product_price'];

    // Update the product details in the database
    $query = "UPDATE table_product SET 
                product_name = ?,
                product_image = ?,
                product_category = ?,
                product_stock = ?,
                product_price = ?
                WHERE product_id = ? AND user_id = ?";

    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $query)) {
        // Change the type definition string to "sssiidi"
        mysqli_stmt_bind_param($stmt, "sssiidi", $product_name, $product_image, $product_category, $product_stock, $product_price, $product_id, $user_id);
        mysqli_stmt_execute($stmt);

        // Redirect back to the product list after saving changes
        header("Location: product_list.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
} else {
    // Redirect to product_list.php if accessed directly without a POST request
    header("Location: product_list.php");
    exit();
}
?>