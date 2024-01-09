<?php
// Load file autoload.php
require 'vendor/autoload.php';

// Include librari PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Import Data Excel dengan PhpSpreadsheet</title>

    <!-- Load File jquery.min.js yang ada difolder js -->
    <script src="js/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            // Sembunyikan alert validasi kosong
            $("#kosong").hide();
        });
    </script>
</head>

<body>
    <h3>Form Import Data</h3>

    <form method="post" action="form.php" enctype="multipart/form-data">
        <!-- <a href="Format.xlsx">Download Format</a> &nbsp;|&nbsp; -->
        <a href="product_list.php">Kembali</a>
        <br><br>

        <input type="file" name="file">
        <button type="submit" name="preview">Preview</button>
    </form>
    <hr>

    <?php
    // Jika user telah mengklik tombol Preview
    if (isset($_POST['preview'])) {
        $tgl_sekarang = date('YmdHis'); // Ini akan mengambil waktu sekarang dengan format yyyymmddHHiiss
        $nama_file_baru = 'data' . $tgl_sekarang . '.xlsx';

        // Cek apakah terdapat file data.xlsx pada folder tmp
        if (is_file('tmp/' . $nama_file_baru)) // Jika file tersebut ada
            unlink('tmp/' . $nama_file_baru); // Hapus file tersebut

        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION); // Ambil ekstensi filenya apa
        $tmp_file = $_FILES['file']['tmp_name'];

        // Cek apakah file yang diupload adalah file Excel 2007 (.xlsx)
        if ($ext == "xlsx") {
            // Upload file yang dipilih ke folder tmp
            // dan rename file tersebut menjadi data{tglsekarang}.xlsx
            // {tglsekarang} diganti jadi tanggal sekarang dengan format yyyymmddHHiiss
            // Contoh nama file setelah di rename : data20210814192500.xlsx
            move_uploaded_file($tmp_file, 'tmp/' . $nama_file_baru);

            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load('tmp/' . $nama_file_baru); // Load file yang tadi diupload ke folder tmp
            $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            // Buat sebuah tag form untuk proses import data ke database
            echo "<form method='post' action='import.php'>";

            // Disini kita buat input type hidden yg isinya adalah nama file excel yg diupload
            // ini tujuannya agar ketika import, kita memilih file yang tepat (sesuai yg diupload)
            echo "<input type='hidden' name='namafile' value='" . $nama_file_baru . "'>";

            // Buat sebuah div untuk alert validasi kosong
            echo "<div id='kosong' style='color: red;margin-bottom: 10px;'>
					Semua data belum diisi, Ada <span id='jumlah_kosong'></span> data yang belum diisi.
                </div>";

            echo "<table border='1' cellpadding='5'>
					<tr>
						<th colspan='6' class='text-center'>Preview Data</th>
					</tr>
					<tr>
                        <th>Product Name</th>
                        <th>Product Image</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>User ID</th>
                    </tr>";

            $numrow = 1;
            $kosong = 0;
            foreach ($sheet as $row) { // Lakukan perulangan dari data yang ada di excel
                // Ambil data pada excel sesuai Kolom
                $product_name = $row['A']; // Ambil data nama
                $product_image = $row['B']; // Ambil data jenis kelamin
                $product_category = $row['C']; // Ambil data telepon
                $product_price = $row['D']; // Ambil data alamat
                $product_stock = $row['E']; // Ambil data alamat
                $user_id = $row['F']; // Ambil data alamat

                // Cek jika semua data tidak diisi
                if ($product_name == "" && $product_image == "" && $product_category == "" && $product_price == "" && $product_stock == "" && $user_id == "")
                    continue; // Lewat data pada baris ini (masuk ke looping selanjutnya / baris selanjutnya)

                // Cek $numrow apakah lebih dari 1
                // Artinya karena baris pertama adalah nama-nama kolom
                // Jadi dilewat saja, tidak usah diimport
                if ($numrow > 1) {
                    // Validasi apakah semua data telah diisi
                    $product_name_td = (!empty($product_name)) ? "" : " style='background: #E07171;'"; // Jika NIS kosong, beri warna merah
                    $product_image_td = (!empty($product_image)) ? "" : " style='background: #E07171;'"; // Jika Nama kosong, beri warna merah
                    $product_category_td = (!empty($product_category)) ? "" : " style='background: #E07171;'"; // Jika Jenis Kelamin kosong, beri warna merah
                    $product_price_td = (!empty($product_price)) ? "" : " style='background: #E07171;'"; // Jika Telepon kosong, beri warna merah
                    $product_stock_td = (!empty($product_stock)) ? "" : " style='background: #E07171;'"; // Jika Alamat kosong, beri warna merah
                    $user_id_td = (!empty($user_id)) ? "" : " style='background: #E07171;'"; // Jika Alamat kosong, beri warna merah

                    // Jika salah satu data ada yang kosong
                    if ($product_name == "" or $product_image == "" or $product_category == "" or $product_price == "" or $product_stock == "" or $user_id == "") {
                        $kosong++; // Tambah 1 variabel $kosong
                    }

                    echo "<tr>";
                    echo "<td" . $product_name_td . ">" . $product_name . "</td>";
                    echo "<td" . $product_image_td . ">" . $product_image . "</td>";
                    echo "<td" . $product_category_td . ">" . $product_category . "</td>";
                    echo "<td" . $product_price_td . ">" . $product_price . "</td>";
                    echo "<td" . $product_stock_td . ">" . $product_stock . "</td>";
                    echo "<td" . $user_id_td . ">" . $user_id . "</td>";
                    echo "</tr>";
                }

                $numrow++; // Tambah 1 setiap kali looping
            }

            echo "</table>";

            // Cek apakah variabel kosong lebih dari 0
            // Jika lebih dari 0, berarti ada data yang masih kosong
            if ($kosong > 0) {
    ?>
                <script>
                    $(document).ready(function() {
                        // Ubah isi dari tag span dengan id jumlah_kosong dengan isi dari variabel kosong
                        $("#jumlah_kosong").html('<?php echo $kosong; ?>');

                        $("#kosong").show(); // Munculkan alert validasi kosong
                    });
                </script>
    <?php
            } else { // Jika semua data sudah diisi
                echo "<hr>";

                // Buat sebuah tombol untuk mengimport data ke database
                echo "<button type='submit' name='import'>Import</button>";
            }

            echo "</form>";
        } else { // Jika file yang diupload bukan File Excel 2007 (.xlsx)
            // Munculkan pesan validasi
            echo "<div style='color: red;margin-bottom: 10px;'>
					Hanya File Excel 2007 (.xlsx) yang diperbolehkan
                </div>";
        }
    }
    ?>
</body>

</html>