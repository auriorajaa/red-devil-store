<?php
ob_start();
session_start();

// Access user session data
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Receipt</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        /* Add custom styles for a receipt look */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
        }

        .receipt-container {
            max-width: 400px;
            margin: auto;
            padding: 10px;
            border-radius: 10px;
            margin-top: 50px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Tambahkan shadow di sini */
        }


        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .receipt-header h1 {
            color: #ff0021;
            font-size: 24px;
        }

        .receipt-header p {
            font-size: 14px;
            color: #6c757d;
        }

        .receipt-details {
            margin-top: 20px;
        }

        .table th,
        .table td {
            border: none;
            padding: 8px;
            text-align: left;
        }

        .total-section {
            margin-top: 20px;
            font-weight: bold;
            color: #ff0021;
        }

        .footer-text {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #6c757d;
        }

        .btn-section {
            margin-top: 20px;
            text-align: center;
        }

        .btn-back,
        .btn-print {
            background-color: #ff0021;
            border: none;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-back {
            margin-right: 10px;
        }

        .btn-back:hover,
        .btn-print:hover {
            background-color: #d90017;
        }

        .btn-print {
            margin-top: 20px;
        }

        /* Tambahkan CSS kustom di sini jika diperlukan */

        #purchase-details {
            margin-top: 20px;
        }

        .purchase-item {
            margin-bottom: 10px;
        }

        .purchase-total {
            margin-top: 20px;
            font-weight: bold;
            color: #ff0021;
        }
    </style>
</head>

<body class="container mt-5">
    <div class="receipt-container">
        <div class="receipt-header">
            <img src="src/emyu.png" alt="" width="48" height="48">
            <h1><strong>UnitedKU</strong></h1>
            <p>Your Fashion Partner</p>
        </div>

        <div class="receipt-details">
            <!-- Display the receipt details here -->
            <?php
            // Include configuration file and start session
            include('config.php');
            include('checkout.php');

            // Check if the user is logged in
            if (!isset($_SESSION['user_id'])) {
                header("Location: login.php");
                exit();
            }

            // Get user_id from the session
            $user_id = $_SESSION['user_id'];

            // Check if there is data sent through the checkout form
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Get checkout form data
                $customer_id = $_POST["customer_id"];
                $products = $_POST["products"];

                // Get transaction date from the database
                $transaction_query = mysqli_query($conn, "SELECT transaction_date FROM table_transaction WHERE user_id = '$user_id' ORDER BY transaction_id DESC LIMIT 1");
                $transaction_row = mysqli_fetch_assoc($transaction_query);
                $transaction_date = date('F j, Y, g:i a', strtotime($transaction_row['transaction_date']));

                // Display purchase details
                echo "<div id='summary'>
                        <h3>Purchase Details</h3>
                        <p>Date: $transaction_date</p>";

                // Display product details in a table
                echo "<table class='table'>
                        <tbody>";

                foreach ($products as $product) {
                    $product_id = $product['product_id'];
                    $quantity = $product['quantity'];

                    $product_query = mysqli_query($conn, "SELECT * FROM table_product WHERE product_id = '$product_id'");
                    $product_row = mysqli_fetch_assoc($product_query);

                    echo "<tr>
                            <td>{$product_row['product_name']}</td>
                            <td>{$quantity} pcs</td>
                            <td>£{$product_row['product_price']}</td>
                        </tr>";
                }

                // Apply discount based on membership category
                $discount_percentage = 0;
                switch ($membership_category) {
                    case 'Premium':
                        $discount_percentage = 15;
                        break;
                    case 'Silver':
                        $discount_percentage = 10;
                        break;
                    case 'Standard':
                        $discount_percentage = 5;
                        break;
                    default:
                        $discount_percentage = 0;
                        break;
                }

                // Calculate total price after discount
                $totalPriceAfterDiscount = $totalPrice - ($discount_percentage / 100 * $totalPrice);

                // Display total and discount in Pound Sterling (£)
                echo "</tbody>
                      </table>
                      <div class='total-section'>
                        <p>Total Quantity: {$totalQuantity} pcs</p>
                        <p>Discount: {$discount_percentage}%</p>
                        <p>Total Price after discount: £{$totalPriceAfterDiscount}</p>
                      </div>";

                echo "</div>"; // End of summary div

                // Add buttons
                echo "<div class='btn-section'>
                        <a href='checkout.php' class='btn btn-secondary btn-back'>Back to Checkout</a>
                        <button class='btn btn-primary btn-print' onclick='window.print()'>Print Receipt</button>
                      </div>";

                exit(); // Stop execution after displaying purchase details
            }
            ?>
        </div>

        <div class="footer-text">
            <p>Thank you for shopping with us!</p>
        </div>
    </div>

    <!-- Add Bootstrap and other scripts if needed -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2kKI4zcBXH+VdU+ZkE+yQ0Z1EGwCyf3oIdjOZy4ZE2J/sYp" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>