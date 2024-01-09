<?php
include('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    $query = "SELECT * FROM table_product WHERE product_id = ?";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $product_name = $row['product_name'];
            $product_image = $row['product_image'];
            $product_category = $row['product_category'];
            $product_stock = $row['product_stock'];
            $product_price = $row['product_price'];
            $product_image = $row['product_image'];
        } else {
            header("Location: product_list.php");
            exit();
        }
    }

    mysqli_stmt_close($stmt);
} else {
    header("Location: product_list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UnitedKU | Edit Product</title>
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
        <h1> <a href="product_list.php"> <i class="fa-solid fa-chevron-left" style="color: #000000;"></i></a>
            Edit Product</h1>
        <br>


        <form id="editProductForm" method="POST" action="save_changes.php">
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">

            <div class="form-group">
                <label for="editProductName">Product Name</label>
                <input type="text" class="form-control" id="editProductName" name="product_name" value="<?php echo $product_name; ?>" required>
            </div>

            <div class="form-group">
                <label for="editProductImage">Product Image</label>
                <select class="form-control" id="editProductImage" name="product_image" required>
                    <option value="home_2023" <?php echo ($product_image === 'home_2023') ? 'selected' : ''; ?>>home_2023</option>
                    <option value="away_2023" <?php echo ($product_image === 'away_2023') ? 'selected' : ''; ?>>away_2023</option>
                    <option value="home_2022" <?php echo ($product_image === 'home_2022') ? 'selected' : ''; ?>>home_2022</option>
                    <option value="away_2022" <?php echo ($product_image === 'away_2022') ? 'selected' : ''; ?>>away_2022</option>
                </select>
            </div>

            <div class="form-group">
                <label for="editProductCategory">Category</label>
                <select class="form-control" id="editProductCategory" name="product_category" required>
                    <option value="Shirt" <?php echo ($product_category === 'Shirt') ? 'selected' : ''; ?>>Shirt</option>
                    <!-- Add other options as needed -->
                </select>
            </div>


            <div class="form-group">
                <label for="editProductStock">Stock</label>
                <input type="number" class="form-control" id="editProductStock" name="product_stock" value="<?php echo $product_stock; ?>" required>
            </div>

            <div class="form-group">
                <label for="editProductPrice">Price</label>
                <input type="number" class="form-control" id="editProductPrice" name="product_price" value="<?php echo $product_price; ?>" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-danger btn-block mt-4">Save Changes</button>
            </div>

            <div class="form-group">
                <a href="product_list.php" style="text-decoration: underline; color:#ff0021;">Back to Product List</a>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <!-- Your other scripts -->
</body>

</html>