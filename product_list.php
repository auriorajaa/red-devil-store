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

// Delete product logic
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Query untuk menghapus produk
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
}
// Check if the export button is clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['export_csv'])) {
    // Fetch products for the logged-in user
    $query = "SELECT * FROM table_product WHERE user_id = ?";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        // Check if there are products
        if (mysqli_num_rows($result) > 0) {
            $csvFileName = "product_list.csv";
            $csvFile = fopen($csvFileName, 'w');

            // Add CSV header
            fputcsv($csvFile, array('Id', 'Product Name', 'Category', 'Stock', 'Price'));

            while ($row = mysqli_fetch_assoc($result)) {
                // Add product data to CSV
                fputcsv($csvFile, array($row['product_id'], $row['product_name'], $row['product_category'], $row['product_stock'], $row['product_price']));
            }

            fclose($csvFile);

            // Set headers for download
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename="' . $csvFileName . '"');
            readfile($csvFileName);
            exit();
        } else {
            echo "No products found for export.";
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Close the statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UnitedKU | Product List</title>
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
                        <a href="#" class="navbar-link active" style="text-decoration: underline; color:#ff0021">Product List</a>
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
                        <a href="transaction.php" class="navbar-link">Transaction</a>
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
            <h1>Your Product</h1>
            <div class="input-group">

                <div class="input-group-append"></div>
                <input type="search" class="form-control" placeholder="Search Data..." />
                <div class="input-group-append"></div>
            </div>
            <!-- Add export button -->
            <form method="post">
                <button type="submit" name="export_csv" class="btn btn-success">Export to CSV</button>
            </form>
            <a href="form.php">Import Data</a><br><br>
        </section>
        <section class="table__body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Id <span class="icon-arrow">&UpArrow;</span></th>
                        <th>Product Name<span class="icon-arrow">&UpArrow;</span></th>
                        <th>Category <span class="icon-arrow">&UpArrow;</span></th>
                        <th>Stock <span class="icon-arrow">&UpArrow;</span></th>
                        <th>Price <span class="icon-arrow">&UpArrow;</span></th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch products for the logged-in user
                    $query = "SELECT * FROM table_product WHERE user_id = ?";
                    $stmt = mysqli_stmt_init($conn);

                    if (mysqli_stmt_prepare($stmt, $query)) {
                        mysqli_stmt_bind_param($stmt, "i", $user_id);
                        mysqli_stmt_execute($stmt);

                        $result = mysqli_stmt_get_result($stmt);

                        // Check if there are products
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                                    <td>" . $row['product_id'] . "</td>
                                    <td><img class='image-table' src='src/" . $row["product_image"] . ".png' alt='' />" . $row['product_name'] . "</td>
                                    <td>" . $row['product_category'] . "</td>
                                    <td>" . $row['product_stock'] . "</td>
                                    <td><strong>&pound;" . $row['product_price'] . "</strong></td>
                                    <td>
                                        <button class='btn btn-warning btn-sm' onclick='editProduct(" . $row['product_id'] . ")'>Edit</button>
                                        <button class='btn btn-danger btn-sm' onclick='confirmDelete(" . $row['product_id'] . ")'>Delete</button>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No products found for this user.</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>Error: " . mysqli_error($conn) . "</td></tr>";
                    }

                    // Close the statement and connection
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);
                    ?>
                </tbody>
            </table>


        </section>
    </main>


    <!-- 
    - #FOOTER
  -->

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
        function confirmDelete(productId) {
            var result = confirm("Are you sure you want to delete this product?");
            if (result) {
                window.location.href = 'product_list.php?action=delete&product_id=' + productId;
                alert("Deleted successfully!");
            } else {
                alert("Delete Canceled!");
            }
        }

        function editProduct(productId) {
            window.location.href = 'edit_product.php?product_id=' + productId;
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