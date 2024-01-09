<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Access user session data
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Include the database configuration
include('config.php');

// Delete transaction logic
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['transaction_id'])) {
    $transaction_id = $_GET['transaction_id'];

    // Query untuk menghapus transaksi
    $query = "DELETE FROM table_transaction WHERE transaction_id = ?";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $transaction_id);
        mysqli_stmt_execute($stmt);

        // Redirect ke halaman riwayat transaksi setelah penghapusan
        header("Location: transaction_history.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UnitedKU | Transaction History</title>
    <link rel="icon" href="src/emyu.png" type="image/icon type">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="style/product_list.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet" />
</head>

<body>
    <!-- 
    - #HEADER
  -->

    <header class="header" data-header>
        <div class="container">
            <div class="overlay" data-overlay></div>

            <button class="nav-open-btn" data-nav-open-btn aria-label="Open Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <nav class="navbar" data-navbar>
                <div class="navbar-top">
                    <a href="#" class="logo">
                        <img src="src/emyu.png" alt="UnitedKU logo" width="45" height="31" />
                    </a>

                    <button class="nav-close-btn" data-nav-close-btn aria-label="Close Menu">
                        <ion-icon name="close-outline"></ion-icon>
                    </button>
                </div>

                <ul class="navbar-list">
                    <li>
                        <a href="dashboard.php" class="navbar-link">Home</a>
                    </li>

                    <li>
                        <a href="product_list.php" class="navbar-link active">Product List</a>
                    </li>

                    <li>
                        <a href="add_product.php" class="navbar-link">Add Product</a>
                    </li>

                    <li>
                        <a href="customer_list.php" class="navbar-link">Customer List</a>
                    </li>

                    <li>
                        <a href="add_customer.php" class="navbar-link">Add Customer</a>
                    </li>

                    <li>
                        <a href="checkout.php" class="navbar-link">Checkout</a>
                    </li>
                    <li>
                        <a href="transaction.php" class="navbar-link" style="text-decoration: underline; color:#ff0021">Transaction</a>
                    </li>
                    <li>
                        <a href="profile.php" class="navbar-link">Edit Profile</a>
                    </li>
                    <li>
                        <a href="#" class="navbar-link-logout" style="color: #ff0021;">Logout</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    <br>
    <br>
    <br>

    <main class="table">
        <section class="table__header">
            <h1>Transaction History</h1>
            <div class="input-group">
                <input type="search" class="form-control" placeholder="Search Data..." />
                <div class="input-group-append"></div>
            </div>
        </section>
        <section class="table__body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Transaction ID <span class="icon-arrow">&UpArrow;</span></th>
                        <th>Product Name<span class="icon-arrow">&UpArrow;</span></th>
                        <th>Customer Name <span class="icon-arrow">&UpArrow;</span></th>
                        <th>Quantity <span class="icon-arrow">&UpArrow;</span></th>
                        <th>Total Price <span class="icon-arrow">&UpArrow;</span></th>
                        <th>Transaction Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch transactions for the logged-in user
                    $query = "SELECT t.transaction_id, p.product_name, c.customer_name, t.quantity, t.total_price, t.transaction_date 
                              FROM table_transaction t
                              JOIN table_product p ON t.product_id = p.product_id
                              JOIN table_customer c ON t.customer_id = c.customer_id
                              WHERE t.user_id = ?
                              ORDER BY t.transaction_id DESC";  // Menambahkan ORDER BY untuk mengurutkan berdasarkan transaction_id secara descending
                    $stmt = mysqli_stmt_init($conn);

                    if (mysqli_stmt_prepare($stmt, $query)) {
                        mysqli_stmt_bind_param($stmt, "i", $user_id);
                        mysqli_stmt_execute($stmt);

                        $result = mysqli_stmt_get_result($stmt);

                        // Check if there are transactions
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                                    <td>" . $row['transaction_id'] . "</td>
                                    <td>" . $row['product_name'] . "</td>
                                    <td>" . $row['customer_name'] . "</td>
                                    <td>" . $row['quantity'] . "</td>
                                    <td><strong>&pound;" . $row['total_price'] . "</strong></td>
                                    <td>" . $row['transaction_date'] . "</td>
                                    <td>
                                        <button class='btn btn-danger btn-sm' onclick='confirmDelete(" . $row['transaction_id'] . ")'>Delete</button>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No transactions found for this user.</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>Error: " . mysqli_error($conn) . "</td></tr>";
                    }

                    // Close the statement and connection
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);
                    ?>
                </tbody>
            </table>
        </section>
    </main>


    <!-- - #FOOTER -->

    <footer class="footer">

        <div class="footer-bottom">
            <div class="container">
                <p class="copyright">
                    &copy; 2023
                    <a href="https://www.linkedin.com/in/auriorajaa">UnitedKU</a>. All
                    Rights Reserved
                </p>
            </div>
        </div>
    </footer>

    <script>
        function confirmDelete(transactionId) {
            var result = confirm("Are you sure you want to delete this transaction?");
            if (result) {
                window.location.href = 'delete_transaction.php?action=delete&transaction_id=' + transactionId;
                alert("Deleted successfully!");
            } else {
                alert("Delete Canceled!");
            }
        }

        // Disable browser back arrow
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            history.go(1);
        };

        // Logout function
        function logout() {
            // You can add any additional cleanup or server-side logout logic here
            window.location.href = 'logout.php'; // Redirect to logout script
        }

        // Attach the logout function to the "Logout" button
        document.querySelector('.navbar-link-logout').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default link behavior
            logout(); // Call the logout function
        });
    </script>

    <script src="javascript/script.js"></script>
    <script src="javascript/product_list.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <!-- 
    - ionicon link
  -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>