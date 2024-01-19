<?php
// Load file autoload.php
require 'vendor/autoload.php';

// Include library PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

// Include your database connection file here if it's not included already
// Example: require 'db_connection.php';
require 'config.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Import Data Excel with PhpSpreadsheet</title>

    <!-- Load File jquery.min.js from the js folder -->
    <script src="js/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            // Hide empty validation alert
            $("#kosong").hide();
        });
    </script>
</head>

<body>
    <h3>Import Data Form</h3>

    <form method="post" action="form.php" enctype="multipart/form-data">
        <a href="product_list.php">Back</a>
        <br><br>

        <input type="file" name="file">
        <button type="submit" name="preview">Preview</button>
    </form>
    <hr>

    <?php
    // If the user clicked the Preview button
    if (isset($_POST['preview'])) {
        $current_date = date('YmdHis');
        $new_file_name = 'data' . $current_date . '.xlsx';

        // Check if the data.xlsx file exists in the tmp folder
        if (is_file('tmp/' . $new_file_name))
            unlink('tmp/' . $new_file_name);

        $file_extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $tmp_file = $_FILES['file']['tmp_name'];

        // Check if the uploaded file is an Excel 2007 file (.xlsx)
        if ($file_extension == "xlsx") {
            move_uploaded_file($tmp_file, 'tmp/' . $new_file_name);

            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load('tmp/' . $new_file_name);
            $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            echo "<form method='post' action='import.php'>";
            echo "<input type='hidden' name='namafile' value='" . $new_file_name . "'>";
            echo "<div id='kosong' style='color: red;margin-bottom: 10px;'>
                    All data is not filled, <span id='jumlah_kosong'></span> data is not filled.
                </div>";

            echo "<table border='1' cellpadding='5'>
                    <tr>
                        <th colspan='6' class='text-center'>Data Preview</th>
                    </tr>
                    <tr>
                        <th>Product ID</th>
                        <th>Customer ID</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Transaction Date</th>
                        <th>User ID</th>
                    </tr>";

            $numrow = 1;
            $kosong = 0;
            foreach ($sheet as $row) {
                $product_id = $row['A'];
                $customer_id = $row['B'];
                $quantity = $row['C'];
                $total_price = $row['D'];
                $transaction_date = $row['E'];
                $user_id = $row['F'];

                if ($product_id == "" && $customer_id == "" && $quantity == "" && $total_price == "" && $transaction_date == "" && $user_id == "")
                    continue;

                if ($numrow > 1) {
                    $product_id_td = (!empty($product_id)) ? "" : " style='background: #E07171;'";
                    $customer_id_td = (!empty($customer_id)) ? "" : " style='background: #E07171;'";
                    $quantity_td = (!empty($quantity)) ? "" : " style='background: #E07171;'";
                    $total_price_td = (!empty($total_price)) ? "" : " style='background: #E07171;'";
                    $transaction_date_td = (!empty($transaction_date)) ? "" : " style='background: #E07171;'";
                    $user_id_td = (!empty($user_id)) ? "" : " style='background: #E07171;'";

                    if ($product_id == "" or $customer_id == "" or $quantity == "" or $total_price == "" or $transaction_date == "" or $user_id == "") {
                        $kosong++;
                    }

                    echo "<tr>";
                    echo "<td" . $product_id_td . ">" . $product_id . "</td>";
                    echo "<td" . $customer_id_td . ">" . $customer_id . "</td>";
                    echo "<td" . $quantity_td . ">" . $quantity . "</td>";
                    echo "<td" . $total_price_td . ">" . $total_price . "</td>";
                    echo "<td" . $transaction_date_td . ">" . $transaction_date . "</td>";
                    echo "<td" . $user_id_td . ">" . $user_id . "</td>";
                    echo "</tr>";
                }

                $numrow++;
            }

            echo "</table>";

            if ($kosong > 0) {
    ?>
                <script>
                    $(document).ready(function() {
                        $("#jumlah_kosong").html('<?php echo $kosong; ?>');
                        $("#kosong").show();
                    });
                </script>
    <?php
            } else {
                echo "<hr>";
                echo "<button type='submit' name='import'>Import</button>";
            }

            echo "</form>";
        } else {
            echo "<div style='color: red;margin-bottom: 10px;'>
                    Only Excel 2007 files (.xlsx) are allowed
                </div>";
        }
    }
    ?>
</body>

</html>