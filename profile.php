<!-- profile.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UnitedKU | Profile</title>
    <link rel="icon" href="src/emyu.png" type="image/icon type">
    <link rel="stylesheet" href="style/profile.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="style/product_list.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet" />

</head>

<body>

    <?php
    include('config.php');
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];

    // Fetch user data
    $query = "SELECT * FROM table_user WHERE user_id = ?";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $user_name = $row['user_name'];
            $user_email = $row['user_email'];
        } else {
            header("Location: login.php");
            exit();
        }
    }

    mysqli_stmt_close($stmt);
    ?>

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
                        <a href="transaction.php" class="navbar-link">Transaction</a>
                    </li>
                    <li>
                        <a href="profile.php" class="navbar-link" style="text-decoration: underline; color:#ff0021">Edit Profile</a>
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

    <div class="container-strap mx-auto">


        <h1>Hi, <?php echo $user_name; ?>!</h1>
        <p>We would like to extend our sincere appreciation for choosing UnitedKU for your selling needs. It has been our pleasure to serve you, and we are grateful for the trust you have placed in us.

        </p>
        <p>Your satisfaction is our top priority, and we hope that our UnitedKU has met and exceeded your expectations. We are committed to delivering high-quality solutions, and your feedback is invaluable to us. Thank you <?php echo $user_name; ?>!

        </p>
        <div class="profile-info">
            <p><strong>Email:</strong> <?php echo $user_email; ?></p>
        </div>

        <div class="profile-edit">
            <h2>Change Password</h2>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["updateProfile"])) {
                $oldPassword = $_POST["oldPassword"];
                $newPassword = $_POST["newPassword"];
                $confirmNewPassword = $_POST["confirmNewPassword"];

                // Verify old password
                $query = "SELECT user_password FROM table_user WHERE user_id = ?";
                $stmt = mysqli_stmt_init($conn);

                if (mysqli_stmt_prepare($stmt, $query)) {
                    mysqli_stmt_bind_param($stmt, "i", $user_id);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if ($row = mysqli_fetch_assoc($result)) {
                        $storedPassword = $row['user_password'];

                        if (password_verify($oldPassword, $storedPassword)) {
                            // Old password is correct, proceed with the update
                            if ($newPassword === $confirmNewPassword) {
                                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                                // Update user password
                                $updateQuery = "UPDATE table_user SET user_password = ? WHERE user_id = ?";
                                $updateStmt = mysqli_stmt_init($conn);

                                if (mysqli_stmt_prepare($updateStmt, $updateQuery)) {
                                    mysqli_stmt_bind_param($updateStmt, "si", $hashedPassword, $user_id);
                                    mysqli_stmt_execute($updateStmt);
                                    echo "<p class='success-message'>Password updated successfully!</p>";
                                } else {
                                    echo "<p class='error-message'>Something went wrong. Please try again later.</p>";
                                }

                                mysqli_stmt_close($updateStmt);
                            } else {
                                echo "<p class='error-message'>New passwords do not match.</p>";
                            }
                        } else {
                            echo "<p class='error-message'>Incorrect old password.</p>";
                        }
                    }
                }

                mysqli_stmt_close($stmt);
            }
            ?>

            <form action="profile.php" method="post">
                <label for="oldPassword">Old Password</label>
                <input type="password" id="oldPassword" name="oldPassword" required>

                <label for="newPassword">New Password</label>
                <input type="password" id="newPassword" name="newPassword" required>

                <label for="confirmNewPassword">Confirm New Password</label>
                <input type="password" id="confirmNewPassword" name="confirmNewPassword" required>

                <br>
                <br>

                <button type="submit" class="btn btn-danger" name="updateProfile">Update Password</button>
            </form>
        </div>
    </div>

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