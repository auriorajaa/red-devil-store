<?php
if (isset($_POST["submit"])) {
    $user_name = $_POST["user_name"];
    $user_email = $_POST["user_email"];
    $user_password = $_POST["user_password"];

    // Hash the password
    $passwordHash = password_hash($user_password, PASSWORD_DEFAULT);

    require_once "config.php";

    // Use prepared statements to prevent SQL injection
    $sql = "INSERT INTO table_user (user_name, user_email, user_password) VALUES (?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "sss", $user_name, $user_email, $passwordHash);
        mysqli_stmt_execute($stmt);
        $success_message = "You've registered successfully";

        echo "<script>
                window.onload = function() {
                    alert('$success_message');
                    window.location.href = 'index.php';
                };
            </script>";

        // Redirect to index.php
        header("location: index.php");
        exit(); // Ensure that no further code is executed after the redirect

    } else {
        die("Something went wrong. Please try again later.");
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
    <title>Creating Account...</title>
    <style>
        :root {
            filter: blur(35px);
        }
    </style>
    <link rel="icon" href="src/emyu.png" type="image/icon type">
</head>

<body>
    <?php
    if (isset($_POST["submit"])) {
        $user_name = $_POST["user_name"];
        $user_email = $_POST["user_email"];
        $user_password = $_POST["user_password"];

        // Hash the password
        $passwordHash = password_hash($user_password, PASSWORD_DEFAULT);

        require_once "config.php";

        // Use prepared statements to prevent SQL injection
        $sql = "INSERT INTO table_user (user_name, user_email, user_password) VALUES (?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);

        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "sss", $user_name, $user_email, $passwordHash);
            mysqli_stmt_execute($stmt);
            $success_message = "You've registered successfully";
            echo "<script>
                window.onload = function() {
                    alert('$success_message');
                    window.location.href = 'index.php';
                };
            </script>";
        } else {
            die("Something went wrong. Please try again later.");
        }

        // Close the statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
    ?>
</body>

</html>