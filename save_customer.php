<?php
include('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['customer_id'])) {
    $customer_id = $_POST['customer_id'];
    $customer_name = $_POST['customer_name'];
    $membership_category = $_POST['membership_category'];
    $customer_gender = $_POST['customer_gender'];
    $customer_phone = $_POST['customer_phone'];

    // Update the customer details in the database
    $query = "UPDATE table_customer SET 
                customer_name = ?,
                membership_category = ?,
                customer_gender = ?,
                customer_phone = ?
                WHERE customer_id = ? AND user_id = ?";

    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $query)) {
        // Change the type definition string to "ssssi"
        mysqli_stmt_bind_param($stmt, "ssssii", $customer_name, $membership_category, $customer_gender, $customer_phone, $customer_id, $user_id);
        mysqli_stmt_execute($stmt);

        // Redirect back to the customer list after saving changes
        header("Location: customer_list.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
} else {
    // Redirect to customer_list.php if accessed directly without a POST request
    header("Location: customer_list.php");
    exit();
}
