<?php

$hostName = "localhost:3307";
$dbUser = "root";
$dbPassword = "";
$dbName = "unitedku_database";

$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);

if (!$conn) {
    die("Gagal terhubung dengan database: " . mysqli_connect_error());
}
