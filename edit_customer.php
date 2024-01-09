<?php
include('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

if (isset($_GET['customer_id'])) {
    $customer_id = $_GET['customer_id'];

    $query = "SELECT * FROM table_customer WHERE customer_id = ?";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $customer_id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $customer_name = $row['customer_name'];
            $membership_category = $row['membership_category'];
            $customer_gender = $row['customer_gender'];
            $customer_phone = $row['customer_phone'];
        } else {
            header("Location: customer_list.php");
            exit();
        }
    }

    mysqli_stmt_close($stmt);
} else {
    header("Location: customer_list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UnitedKU | Edit Customer</title>
    <link rel="icon" href="src/emyu.png" type="image/icon type">
    <script src="https://kit.fontawesome.com/7c7f68c3e5.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <style>
        body {
            font-family: "Jost", sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 480px;
            background-color: #ffffff;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            display: inline-block;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #007bff;
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        button:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            margin-left: 10px;
        }

        .btn-secondary:hover {
            background-color: #545b62;
        }

        select {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            display: inline-block;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group a:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1> <a href="customer_list.php"> <i class="fa-solid fa-chevron-left" style="color: #000000;"></i></a>
            Edit Customer</h1>
        <br>

        <form id="editProductForm" method="POST" action="save_customer.php">
            <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">

            <div class="form-group">
                <label for="editProductName">Customer Name</label>
                <input type="text" class="form-control" id="editProductName" name="customer_name" value="<?php echo $customer_name; ?>" required>
            </div>

            <div class="form-group">
                <label for="editMembershipCategory">Membership Category</label>
                <select class="form-control" id="editMembershipCategory" name="membership_category" required>
                    <!-- Add options based on your membership categories -->
                    <option value="Premium" <?php echo ($membership_category === 'Premium') ? 'selected' : ''; ?>>Premium</option>
                    <option value="Silver" <?php echo ($membership_category === 'Silver') ? 'selected' : ''; ?>>Silver</option>
                    <option value="Standard" <?php echo ($membership_category === 'Standard') ? 'selected' : ''; ?>>Standard</option>
                </select>
            </div>

            <div class="form-group">
                <label for="editCustomerGender">Customer Gender</label>
                <select class="form-control" id="editCustomerGender" name="customer_gender" required>
                    <!-- Add options based on your gender categories -->
                    <option value="Male" <?php echo ($customer_gender === 'Male') ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo ($customer_gender === 'Female') ? 'selected' : ''; ?>>Female</option>
                </select>
            </div>

            <div class="form-group">
                <label for="editCustomerPhone">Customer Phone</label>
                <input type="text" class="form-control" id="editCustomerPhone" name="customer_phone" value="<?php echo $customer_phone; ?>" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-danger btn-block mt-4">Save Changes</button>
            </div>

            <div class="form-group">
                <a href="customer_list.php" style="text-decoration: underline; color:#ff0021;">Back to Customer List</a>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <!-- Your other scripts -->
</body>

</html>