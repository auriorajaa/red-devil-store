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
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>UnitedKU | Home</title>
    <link rel="icon" href="src/emyu.png" type="image/icon type">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />

    <link rel="stylesheet" href="style/dashboard_style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
                        <a href="#home" class="navbar-link" style="text-decoration: underline; color:#ff0021">Home</a>
                    </li>

                    <li>
                        <a href="product_list.php" class="navbar-link">Product List</a>
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

    <main>
        <article>
            <!-- 
        - #HERO
      -->

            <section class="hero" id="home" style="background-image: url('src/hero.png');">
                <div class="container">
                    <div class="hero-content">
                        <p class="hero-subtitle">Track Your Selling with UnitedKU</p>
                        <h2 class="h1 hero-title">
                            Managing Stock and Product Checkout!
                        </h2>
                        <br />
                        <br />
                        <br />
                        <p style="opacity: 80%; font-size: 17px;" class="hero-subtitle">In Partnership with Manchester United</p>
                    </div>

                    <!-- Cards for Information -->
                    <div class="row mt-5">
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Products</h5>
                                    <?php
                                    // Include the database configuration
                                    include('config.php');

                                    // Query to get the total number of products
                                    $queryProducts = "SELECT COUNT(*) AS total_products FROM table_product WHERE user_id = ?";
                                    $stmtProducts = mysqli_stmt_init($conn);

                                    if (mysqli_stmt_prepare($stmtProducts, $queryProducts)) {
                                        mysqli_stmt_bind_param($stmtProducts, "i", $user_id);
                                        mysqli_stmt_execute($stmtProducts);
                                        $resultProducts = mysqli_stmt_get_result($stmtProducts);
                                        $rowProducts = mysqli_fetch_assoc($resultProducts);
                                        echo "<p class='card-text'>" . $rowProducts['total_products'] . "</p>";
                                    } else {
                                        echo "Error: " . mysqli_error($conn);
                                    }

                                    mysqli_stmt_close($stmtProducts);
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Customers</h5>
                                    <?php
                                    // Query to get the total number of customers
                                    $queryCustomers = "SELECT COUNT(*) AS total_customers FROM table_customer WHERE user_id = ?";
                                    $stmtCustomers = mysqli_stmt_init($conn);

                                    if (mysqli_stmt_prepare($stmtCustomers, $queryCustomers)) {
                                        mysqli_stmt_bind_param($stmtCustomers, "i", $user_id);
                                        mysqli_stmt_execute($stmtCustomers);
                                        $resultCustomers = mysqli_stmt_get_result($stmtCustomers);
                                        $rowCustomers = mysqli_fetch_assoc($resultCustomers);
                                        echo "<p class='card-text'>" . $rowCustomers['total_customers'] . "</p>";
                                    } else {
                                        echo "Error: " . mysqli_error($conn);
                                    }

                                    mysqli_stmt_close($stmtCustomers);
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Transactions</h5>
                                    <?php
                                    // Query to get the total number of transactions
                                    $queryTransactions = "SELECT COUNT(*) AS total_transactions FROM table_transaction WHERE user_id = ?";
                                    $stmtTransactions = mysqli_stmt_init($conn);

                                    if (mysqli_stmt_prepare($stmtTransactions, $queryTransactions)) {
                                        mysqli_stmt_bind_param($stmtTransactions, "i", $user_id);
                                        mysqli_stmt_execute($stmtTransactions);
                                        $resultTransactions = mysqli_stmt_get_result($stmtTransactions);
                                        $rowTransactions = mysqli_fetch_assoc($resultTransactions);
                                        echo "<p class='card-text'>" . $rowTransactions['total_transactions'] . "</p>";
                                    } else {
                                        echo "Error: " . mysqli_error($conn);
                                    }

                                    mysqli_stmt_close($stmtTransactions);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


            <!-- 
            - #PRODUCT
            -->

            <section class="section product">
                <div class="container">
                    <h2 class="h2 section-title">Your Product</h2>

                    <ul class="product-list">
                        <?php

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
                                    echo "<li>
                                            <div class='product-card'>
                                                <figure class='card-banner'>
                                                    <a href='#'>
                                                        <img src='src/" . $row["product_image"] . ".png' alt='' loading='lazy' width='800' height='1034' class='w-100' />
                                                    </a>
                                                    <div class='card-actions'>
                                                        <button class='card-action-btn cart-btn'>
                                                            <ion-icon name='create-outline' aria-hidden='true'></ion-icon>
                                                            <a style='text-decoration: none; color: #fff;' href='product_list.php'>
                                                                <p>Edit Product</p>
                                                            </a>
                                                        </button>
                                                    </div>
                                                </figure>
                                                <div class='card-content'>
                                                    <h3 class='h4 card-title'>
                                                        <a href='#'>" . $row["product_name"] . "</a>
                                                    </h3>
                                                    <div class='card-price'>
                                                        <data value='" . $row["product_price"] . "'>&pound;" . $row["product_price"] . "</data>
                                                        <p>Stock: " . $row["product_stock"] . "</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>";
                                }
                            } else {
                                echo "No products found for this user.";
                            }
                        } else {
                            echo "Error: " . mysqli_error($conn);
                        }

                        // Close the statement and connection
                        mysqli_stmt_close($stmt);
                        mysqli_close($conn);
                        ob_end_flush();
                        ?>
                    </ul>
                </div>
            </section>
        </article>
        <!-- Chart -->
        <div class="row mt-5 justify-content-center">
            <div class="col-md-9">
                <canvas id="chart"></canvas>
            </div>
        </div>
        <div class="row mt-5 justify-content-center">
            <div class="col-md-9">
                <canvas id="chartDailySales"></canvas>
            </div>
        </div>

        <!-- Add this where you want to display monthly customers chart -->
        <div class="row mt-5 justify-content-center">
            <div class="col-md-9">
                <canvas id="chartMonthlyCustomers"></canvas>
            </div>
        </div>

    </main>

    <!-- 
    - #FOOTER
  -->

    <footer class="footer">
        <div class="footer-top">
            <div class="container">
                <div class="footer-brand">
                    <a href="#" class="logo">
                        <img src="src/emyu.png" alt="UnitedKU logo" width="45" height="31" />
                    </a>

                    <p class="footer-text">
                        UnitedKU is a web-services to store and track your product for selling.
                        It is also a web-services to manage your stock and checkout.
                    </p>

                    <br>

                    <ul class="social-list">
                        <li>
                            <a href="https://twitter.com/hendrianoko" class="social-link">
                                <ion-icon name="logo-twitter"></ion-icon>
                            </a>
                        </li>


                    </ul>
                </div>

                <ul class="footer-list">
                    <li>
                        <p class="footer-list-title">Information</p>
                    </li>

                    <li>
                        <a href="https://twitter.com/hendrianoko" class="footer-link">About Creator</a>
                    </li>
                </ul>

                <ul class="footer-list">
                    <li>
                        <p class="footer-list-title">Help & Support</p>
                    </li>

                    <li>
                        <a href="mailto:riorajaa2018@gmail.com" class="footer-link">Personal Contact</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <p class="copyright">
                    &copy; 2023
                    <a href="#">UnitedKU</a>. All
                    Rights Reserved
                </p>
            </div>
        </div>
    </footer>

    <script>
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

    <!-- 
    - custom js link
  -->
    <script src="javascript/script.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Create a bar chart for total products, customers, and transactions -->
    <script>
        // Get data from PHP variables
        var totalProducts = <?php echo $rowProducts['total_products']; ?>;
        var totalCustomers = <?php echo $rowCustomers['total_customers']; ?>;
        var totalTransactions = <?php echo $rowTransactions['total_transactions']; ?>;

        // Create a bar chart for total products, customers, and transactions
        var ctx = document.getElementById('chart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total Products', 'Total Customers', 'Total Transactions'],
                datasets: [{
                    label: 'Count',
                    data: [totalProducts, totalCustomers, totalTransactions],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 206, 86, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>


    <script>
        // Get data from PHP variables for daily sales
        var dailySalesData = <?php
                                $queryDailySales = "SELECT DATE(transaction_date) AS date, SUM(quantity) AS total_quantity
                         FROM table_transaction
                         WHERE user_id = ?
                         GROUP BY DATE(transaction_date)";
                                $stmtDailySales = mysqli_stmt_init($conn);

                                if (mysqli_stmt_prepare($stmtDailySales, $queryDailySales)) {
                                    mysqli_stmt_bind_param($stmtDailySales, "i", $user_id);
                                    mysqli_stmt_execute($stmtDailySales);
                                    $resultDailySales = mysqli_stmt_get_result($stmtDailySales);
                                    $dataDailySales = array();

                                    while ($rowDailySales = mysqli_fetch_assoc($resultDailySales)) {
                                        $dataDailySales[$rowDailySales['date']] = $rowDailySales['total_quantity'];
                                    }

                                    echo json_encode($dataDailySales);
                                } else {
                                    echo "[]";
                                }

                                mysqli_stmt_close($stmtDailySales);
                                ?>;

        // Convert date strings to a more readable format
        var formattedDailySalesData = Object.keys(dailySalesData).map(function(date) {
            return {
                date: new Date(date).toLocaleDateString(),
                total_quantity: dailySalesData[date]
            };
        });

        // Create a bar chart for daily sales
        var ctxDailySales = document.getElementById('chartDailySales').getContext('2d');
        var myChartDailySales = new Chart(ctxDailySales, {
            type: 'bar',
            data: {
                labels: formattedDailySalesData.map(data => data.date),
                datasets: [{
                    label: 'Total Quantity',
                    data: formattedDailySalesData.map(data => data.total_quantity),
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Total Quantity',
                            font: {
                                size: 14
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date',
                            font: {
                                size: 14
                            }
                        }
                    }
                }
            }
        });
    </script>

    <!-- Add this script for monthly customers chart -->
    <script>
        // Get data from PHP variables for monthly customers
        var monthlyCustomersData = <?php
                                    $queryMonthlyCustomers = "SELECT DATE_FORMAT(transaction_date, '%Y-%m') AS month, COUNT(DISTINCT customer_id) AS total_customers
                             FROM table_transaction
                             WHERE user_id = ?
                             GROUP BY month";
                                    $stmtMonthlyCustomers = mysqli_stmt_init($conn);

                                    if (mysqli_stmt_prepare($stmtMonthlyCustomers, $queryMonthlyCustomers)) {
                                        mysqli_stmt_bind_param($stmtMonthlyCustomers, "i", $user_id);
                                        mysqli_stmt_execute($stmtMonthlyCustomers);
                                        $resultMonthlyCustomers = mysqli_stmt_get_result($stmtMonthlyCustomers);
                                        $dataMonthlyCustomers = array();

                                        while ($rowMonthlyCustomers = mysqli_fetch_assoc($resultMonthlyCustomers)) {
                                            $dataMonthlyCustomers[$rowMonthlyCustomers['month']] = $rowMonthlyCustomers['total_customers'];
                                        }

                                        echo json_encode($dataMonthlyCustomers);
                                    } else {
                                        echo "[]";
                                    }

                                    mysqli_stmt_close($stmtMonthlyCustomers);
                                    ?>;

        // Convert date strings to a more readable format
        var formattedMonthlyCustomersData = Object.keys(monthlyCustomersData).map(function(month) {
            return {
                month: new Date(month + '-01').toLocaleDateString('en-US', {
                    month: 'long',
                    year: 'numeric'
                }),
                total_customers: monthlyCustomersData[month]
            };
        });

        // Create a bar chart for monthly customers
        var ctxMonthlyCustomers = document.getElementById('chartMonthlyCustomers').getContext('2d');
        var myChartMonthlyCustomers = new Chart(ctxMonthlyCustomers, {
            type: 'bar',
            data: {
                labels: formattedMonthlyCustomersData.map(data => data.month),
                datasets: [{
                    label: 'Total Customers',
                    data: formattedMonthlyCustomersData.map(data => data.total_customers),
                    backgroundColor: 'rgba(255, 206, 86, 0.5)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Total Customers',
                            font: {
                                size: 14
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Month',
                            font: {
                                size: 14
                            }
                        }
                    }
                }
            }
        });
    </script>


    <!-- 
    - ionicon link
  -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>