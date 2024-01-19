<?php
// Load file config.php
include "config.php";

// Load file autoload.php
require 'vendor/autoload.php';

// Include librari PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

// Establish a database connection
$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "unitedku_database";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if (isset($_POST['customer_import'])) { // Jika user mengklik tombol Import
    $nama_file_baru = $_POST['namafile'];
    $path = 'tmp/' . $nama_file_baru; // Set tempat menyimpan file tersebut dimana

    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    $spreadsheet = $reader->load($path); // Load file yang tadi diupload ke folder tmp
    $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    $numrow = 1;
    foreach ($sheet as $row) {
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
            // Proses simpan ke Database
            // Buat query Insert
            $sql = $pdo->prepare("INSERT INTO table_customer 
                                  (customer_name, membership_category, customer_gender, customer_phone, user_id) 
                                  VALUES 
                                  (:customer_name, :membership_category, :customer_gender, :customer_phone, :user_id)");

            $sql->execute([
                'customer_name' => $customer_name,
                'membership_category' => $membership_category,
                'customer_gender' => $customer_gender,
                'customer_phone' => $customer_phone,
                'user_id' => $user_id,
            ]);
        }

        $numrow++; // Tambah 1 setiap kali looping
    }

    unlink($path); // Hapus file excel yg telah diupload, ini agar tidak terjadi penumpukan file
}

header('location: customer_list.php'); // Redirect ke halaman awal
