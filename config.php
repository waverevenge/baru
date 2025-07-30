<?php
$servername = "localhost";
$username = "root"; // Username default XAMPP
$password = "";     // Password default XAMPP (kosong)
$dbname = "db_jokistore"; // Nama database yang Anda buat

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
// echo "Koneksi database berhasil"; // Untuk debugging
?>