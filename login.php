<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checking Login Data...</title>
    <style>
        :root {
            filter: blur(35px);
        }
    </style>
    <link rel="icon" href="src/emyu.png" type="image/icon type">
</head>

<body>
    <?php
    include('config.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $user_name = $_POST['user_name'];
        $user_password = $_POST['user_password'];

        // Use prepared statement to prevent SQL injection
        $query = "SELECT * FROM table_user WHERE user_name = ?";
        $stmt = mysqli_stmt_init($conn);

        if (mysqli_stmt_prepare($stmt, $query)) {
            mysqli_stmt_bind_param($stmt, "s", $user_name);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            if ($result) {
                if (mysqli_num_rows($result) == 1) {
                    $user_data = mysqli_fetch_assoc($result);

                    // Verify the password
                    if (password_verify($user_password, $user_data['user_password'])) {
                        // Authentication successful
                        session_start();
                        $_SESSION['user_id'] = $user_data['user_id'];
                        $_SESSION['user_name'] = $user_data['user_name'];

                        // Redirect the user to the dashboard
                        header("Location: dashboard.php");
                        exit();
                    } else {
                        // Authentication failed
                        $error_message = "Invalid username or password";
                        echo "<script>
                            alert('$error_message');
                            window.location.href = 'index.php';
                          </script>";
                    }
                } else {
                    // User not found
                    $error_message = "Invalid username or password";
                    echo "<script>
                        alert('$error_message');
                        window.location.href = 'index.php';
                      </script>";
                }
            } else {
                // Error in the SQL query
                $error_message = "Error: " . mysqli_error($conn);
                echo "<script>
                    alert('$error_message');
                    window.location.href = 'index.php';
                  </script>";
            }

            // Close the statement and connection
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        }
    }
    ?>
</body>

</html>