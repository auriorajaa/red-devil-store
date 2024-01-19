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

    <!-- Load Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Load File jquery.min.js yang ada di folder js -->
    <script src="js/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            // Sembunyikan alert validasi kosong
            $("#kosong").hide();
        });
    </script>
</head>

<body>
    <div class="container mt-3">
        <h3 class="mt-3">Form Import Data</h3>

        <form method="post" action="customer_form.php" enctype="multipart/form-data" class="mt-3">
            <a href="customer_list.php" class="btn btn-secondary">Kembali</a>
            <br><br>

            <div class="custom-file">
                <input type="file" name="file" class="custom-file-input" id="customFile">
                <label class="custom-file-label" for="customFile">Choose file</label>
            </div>

            <button type="submit" name="preview" class="btn btn-primary mt-3">Preview</button>
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
                echo "<form method='post' action='customer_import.php'>";

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
                    <th>Customer Name</th>
                    <th>Membership Category</th>
                    <th>Customer Gender</th>
                    <th>Customer Phone</th>
                    <th>User ID</th>
                </tr>";

                $numrow = 1;
                $kosong = 0;
                foreach ($sheet as $row) { // Lakukan perulangan dari data yang ada di excel
                    // Ambil data pada excel sesuai Kolom
                    $customer_name = $row['A']; // Ambil data nama
                    $membership_category = $row['B']; // Ambil data jenis kelamin
                    $customer_gender = $row['C']; // Ambil data telepon
                    $customer_phone = $row['D']; // Ambil data alamat
                    $user_id = $row['E']; // Ambil data alamat

                    // Cek jika semua data tidak diisi
                    if ($customer_name == "" && $membership_category == "" && $customer_gender == "" && $customer_phone == "" && $user_id == "")
                        continue; // Lewat data pada baris ini (masuk ke looping selanjutnya / baris selanjutnya)

                    // Cek $numrow apakah lebih dari 1
                    // Artinya karena baris pertama adalah nama-nama kolom
                    // Jadi dilewat saja, tidak usah diimport
                    if ($numrow > 1) {
                        // Validasi apakah semua data telah diisi
                        $customer_name_td = (!empty($customer_name)) ? "" : " style='background: #E07171;'"; // Jika NIS kosong, beri warna merah
                        $membership_category_td = (!empty($membership_category)) ? "" : " style='background: #E07171;'"; // Jika Nama kosong, beri warna merah
                        $customer_gender_td = (!empty($customer_gender)) ? "" : " style='background: #E07171;'"; // Jika Jenis Kelamin kosong, beri warna merah
                        $customer_phone_td = (!empty($customer_phone)) ? "" : " style='background: #E07171;'"; // Jika Telepon kosong, beri warna merah
                        $user_id_td = (!empty($user_id)) ? "" : " style='background: #E07171;'"; // Jika Alamat kosong, beri warna merah

                        // Jika salah satu data ada yang kosong
                        if ($customer_gender == "" or $membership_category == "" or $customer_gender == "" or $customer_phone == "" or $user_id == "") {
                            $kosong++; // Tambah 1 variabel $kosong
                        }

                        echo "<tr>";
                        echo "<td" . $customer_name_td . ">" . $customer_name . "</td>";
                        echo "<td" . $membership_category_td . ">" . $membership_category . "</td>";
                        echo "<td" . $customer_gender_td . ">" . $customer_gender . "</td>";
                        echo "<td" . $customer_phone_td . ">" . $customer_phone . "</td>";
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
                    echo "<button type='submit' name='customer_import'>Import</button>";
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
    </div>
</body>

</html>