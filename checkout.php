<?php
ob_start();
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Access user session data
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Include configuration file
include('config.php');

// Tambahkan logika checkout di sini, misalnya:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data formulir checkout
    $customer_name = $_POST["customer_id"];
    $products = $_POST["products"];

    // Mulai transaksi database
    mysqli_begin_transaction($conn);

    // Get customer_id based on customer_name
    $customer_query = mysqli_query($conn, "SELECT customer_id, membership_category FROM table_customer WHERE customer_name = '$customer_name'");
    $customer_row = mysqli_fetch_assoc($customer_query);

    if (!$customer_row) {
        echo "Error: Invalid customer.";
        mysqli_rollback($conn);
        exit();
    }

    $customer_id = $customer_row['customer_id'];
    $membership_category = $customer_row['membership_category'];

    // Initialize total price variables
    $totalPrice = 0;
    $totalPrice_after_discount = 0;

    // Loop melalui setiap produk yang di-checkout
    foreach ($products as $product) {
        $product_id = $product['product_id'];
        $quantity = $product['quantity'];

        // Ambil informasi produk dari database
        $product_query = mysqli_query($conn, "SELECT * FROM table_product WHERE product_id = '$product_id'");
        $product_row = mysqli_fetch_assoc($product_query);

        // Periksa apakah quantity melebihi stok
        if ($quantity > $product_row['product_stock']) {
            echo "Error: Quantity melebihi stok produk.";
            mysqli_rollback($conn);
            exit();
        }

        // Hitung total harga sebelum diskon
        $total_price = $quantity * $product_row['product_price'];
        $totalPrice += $total_price;

        // Terapkan diskon berdasarkan membership category
        $discount_percentage = 0;
        switch ($customer_row['membership_category']) {
            case 'Premium':
                $discount_percentage = 5;
                break;
            case 'Silver':
                $discount_percentage = 3;
                break;
            case 'Standard':
                $discount_percentage = 0;
                break;
            default:
                $discount_percentage = 0;
                break;
        }

        // Hitung total harga setelah diskon
        $discount_amount = ($discount_percentage / 100) * $total_price;
        $total_price_after_discount = $total_price - $discount_amount;

        // Accumulate the discounted total for each product
        $totalPrice_after_discount += $total_price_after_discount;

        // Update stok produk setelah pembelian
        $updated_stock = $product_row['product_stock'] - $quantity;
        $update_stock_query = mysqli_query($conn, "UPDATE table_product SET product_stock = '$updated_stock' WHERE product_id = '$product_id'");

        // Periksa apakah update stok berhasil
        if (!$update_stock_query) {
            // Rollback transaksi jika gagal mengupdate stok produk
            mysqli_rollback($conn);
            echo "Error updating product stock: " . mysqli_error($conn);
            exit();
        }

        // Simpan informasi transaksi ke dalam tabel_transaction
        $sql = "INSERT INTO table_transaction (product_id, customer_id, quantity, total_price, user_id) 
                VALUES ('$product_id', '$customer_id', '$quantity', '$total_price_after_discount', '$user_id')";

        if (!mysqli_query($conn, $sql)) {
            // Rollback transaksi jika gagal menyimpan informasi transaksi
            mysqli_rollback($conn);
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            exit();
        }
    }

    // Commit transaksi database jika semua produk berhasil dicheckout
    mysqli_commit($conn);

    // Konversi total harga ke dalam Pound Sterling (£) berdasarkan nilai tukar
    $exchangeRate = 0.75; // Gantilah dengan nilai tukar yang sesuai
    $totalPriceGBP = $totalPrice * $exchangeRate;

    echo "<div id='summary'>
    <h2 style='text-align: center;'>Details</h2>";

    $totalQuantity = 0;

    // Tampilkan rincian pembelian
    if (!empty($products)) {
        echo "<div id='purchase-details'>";
        foreach ($products as $product) {
            $product_id = $product['product_id'];
            $quantity = $product['quantity'];

            $product_query = mysqli_query($conn, "SELECT * FROM table_product WHERE product_id = '$product_id'");
            $product_row = mysqli_fetch_assoc($product_query);

            echo "<div class='purchase-item'>
                <p style='text-align: center;'>{$product_row['product_name']} | £{$product_row['product_price']} X {$quantity} pcs</p>
              </div>";

            $totalQuantity += $quantity;
        }

        // Tampilkan total dan diskon dalam Pound Sterling (£)
        echo "<div class='purchase-total'>
        <br><br>
            <p>Total Quantity: {$totalQuantity} pcs</p>
            <p>Discount: {$discount_percentage}%</p>
            <p>Total Price after discount: £{$totalPrice_after_discount}</p>
          </div>";

        echo "</div>"; // Tutup div #purchase-details
    } else {
        echo "<p>No products in the order.</p>";
    }

    echo "</div>"; // Tutup div #summary

    // Add buttons
    echo "<div class='btn-section'>
    <br><br>
    <a href='checkout.php' class='backtocheckout'><p>Back to Checkout</p></a>
    <br>
    <button class='btn btn-danger btn-print' onclick='window.print()'>Print Receipt</button>
  </div>";
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UnitedKU | Checkout</title>
    <link rel="icon" href="src/emyu.png" type="image/icon type">

    <!-- Tambahkan link Bootstrap -->

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="style/product_list.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <!-- Tambahkan link CSS kustom jika diperlukan -->
    <style>
        /* Tambahkan CSS kustom di sini jika diperlukan */

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

        .backtocheckout {
            text-decoration: none;
        }

        p {
            color: black;
        }

        body {
            font-family: "Jost", sans-serif;
        }

        .product-container {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .product-container select {
            flex: 1;
            margin-right: 10px;
        }

        .product-container input {
            width: 120px;
            margin-right: 10px;
        }

        .product-container button {
            width: 80px;
        }

        #summary {
            margin-top: 20px;
        }
    </style>
</head>

<body class="container">

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
                        <a href="#" class="navbar-link" style="text-decoration: underline; color:#ff0021">Checkout</a>
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

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1 class="mb-4">Checkout</h1>
                <p>Greetings, welcome to the UnitedKU Checkout experience! Your satisfaction is our priority, and we want to ensure a smooth process for you. As you embark on this checkout journey, we kindly advise you to proceed with caution and meticulously review your selections. A double-check goes a long way in preventing any inadvertent errors during the checkout process. We appreciate your attention to detail and wish you a seamless and enjoyable checkout experience with UnitedKU.</p>

                <!-- Formulir Checkout -->
                <form method="post" action="print_receipt.php" class="mt-5" id="checkoutForm">
                    <!-- Ambil data pelanggan dari database sesuai kebutuhan -->
                    <div class="form-group">
                        <label for="customer_id">Membership:</label>
                        <select name="customer_id" id="customer_id" class="form-control" required>
                            <!-- Tampilkan opsi pelanggan dari database -->
                            <?php
                            $result = mysqli_query($conn, "SELECT * FROM table_customer WHERE user_id = '$user_id'");
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['customer_name'] . "'>" . $row['customer_name'] . " - " . $row['membership_category'] . "</option>";
                            }
                            ?>
                        </select>

                    </div>

                    <div id="products-container">
                        <div class="product-container">
                            <select name="products[0][product_id]" class="form-control" onchange="updateStockInfo(this)" required>
                                <?php
                                $result = mysqli_query($conn, "SELECT * FROM table_product WHERE user_id = '$user_id'");
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='" . $row['product_id'] . "' data-stock='" . $row['product_stock'] . "'>" . $row['product_name'] . " (Stok: " . $row['product_stock'] . ")</option>";
                                }
                                ?>
                            </select>
                            <span id="stockInfo_0"></span>
                            <input type="number" name="products[0][quantity]" class="form-control" min="1" placeholder="Quantity">
                            <button type="button" class="btn btn-danger" onclick="removeProduct(this.parentElement)">Delete</button>
                        </div>
                    </div>

                    <button type="button" id="addProduct" class="btn btn-secondary" onclick="addProduct()">Add Another</button>

                    <div class="form-group mt-3">
                        <button type="button" class="btn btn-primary" onclick="confirmCheckout()">Checkout</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br>
    <br>
    <br>
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



    <!-- Tambahkan script JavaScript untuk menambahkan/menghapus produk -->
    <script>
        var productIdCounter = 1;

        var productOptions = <?php
                                $result = mysqli_query($conn, "SELECT * FROM table_product WHERE user_id = '$user_id'");
                                $options = array();
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $options[] = array('value' => $row['product_id'], 'text' => $row['product_name'], 'stock' => $row['product_stock']);
                                }
                                echo json_encode($options);
                                ?>;

        function addProduct() {
            var container = document.getElementById('products-container');
            var productContainer = document.createElement('div');
            productContainer.className = 'product-container';

            var productSelect = document.createElement('select');
            productSelect.name = 'products[' + productIdCounter + '][product_id]';
            productSelect.className = 'form-control';

            for (var i = 0; i < productOptions.length; i++) {
                var option = document.createElement('option');
                option.value = productOptions[i].value;
                option.text = productOptions[i].text + " (Stok: " + productOptions[i].stock + ")";
                productSelect.add(option);
            }

            var stockInfo = document.createElement('span');
            stockInfo.id = 'stockInfo_' + productIdCounter;

            var quantityInput = document.createElement('input');
            quantityInput.type = 'number';
            quantityInput.name = 'products[' + productIdCounter + '][quantity]';
            quantityInput.className = 'form-control';
            quantityInput.min = '1';
            quantityInput.placeholder = 'Quantity';

            var deleteButton = document.createElement('button');
            deleteButton.type = 'button';
            deleteButton.className = 'btn btn-danger';
            deleteButton.textContent = 'Delete';
            deleteButton.onclick = function() {
                removeProduct(productContainer);
            };

            productContainer.appendChild(productSelect);
            productContainer.appendChild(stockInfo);
            productContainer.appendChild(quantityInput);
            productContainer.appendChild(deleteButton);

            container.appendChild(productContainer);
            productIdCounter++;
        }

        function removeProduct(element) {
            var container = document.getElementById('products-container');
            container.removeChild(element);
        }

        document.getElementById('addProduct').addEventListener('click', addProduct);

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

        function confirmCheckout() {
            var confirmation = confirm("Are you sure you want to checkout this item?");
            if (confirmation) {
                // Jika pengguna menekan OK pada dialog konfirmasi
                document.getElementById('checkoutForm').submit();
            } else {
                // Jika pengguna menekan Cancel pada dialog konfirmasi
                // Tidak melakukan apa-apa
            }
        }
    </script>

    <!-- Tambahkan script Bootstrap dan lainnya jika diperlukan -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2kKI4zcBXH+VdU+ZkE+yQ0Z1EGwCyf3oIdjOZy4ZE2J/sYp" crossorigin="anonymous"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="javascript/script.js"></script>
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