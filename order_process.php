<?php
// order_process.php
// File ini memproses data formulir pemesanan dan menyimpannya ke database

// Sertakan file koneksi database
include 'config.php';

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari POST request
    $item_id = $_POST['item_id'] ?? null;
    $order_type = $_POST['order_type'] ?? null;
    $item_name_ordered = $_POST['item_name_ordered'] ?? '';
    $price_ordered = $_POST['price_ordered'] ?? 0;
    $customer_name = $_POST['customer_name'] ?? '';
    $customer_email = $_POST['customer_email'] ?? '';
    $customer_phone = $_POST['customer_phone'] ?? '';
    $game_id = $_POST['game_id'] ?? null;
    $game_server = $_POST['game_server'] ?? null;
    $payment_method = $_POST['payment_method'] ?? '';

    // Validasi dasar input
    if (empty($item_id) || empty($order_type) || empty($customer_name) || empty($customer_email) || empty($customer_phone) || empty($payment_method) || empty($item_name_ordered) || empty($price_ordered)) {
        $message = 'Mohon lengkapi semua data yang diperlukan.';
        $message_type = 'error';
    } else {
        // Asumsi user_id NULLable jika tidak ada sistem login user
        $user_id = null; // Bisa diisi dengan $_SESSION['user_id'] jika ada sistem login user
        $status = 'pending'; // Status awal pesanan

        // Menggunakan prepared statement untuk mencegah SQL Injection
        $stmt = $conn->prepare("INSERT INTO orders (user_id, customer_name, customer_email, customer_phone, order_type, item_id, item_name_ordered, price_ordered, game_id, game_server, payment_method, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if ($stmt) {
            // 'issssssdsss' berarti: integer (user_id), string (nama), string (email), dll.
            // Sesuaikan tipe data 'd' untuk price_ordered jika di database Anda DECIMAL.
            $stmt->bind_param("issssssdssss", $user_id, $customer_name, $customer_email, $customer_phone, $order_type, $item_id, $item_name_ordered, $price_ordered, $game_id, $game_server, $payment_method, $status);

            if ($stmt->execute()) {
                $order_id_new = $conn->insert_id; // Ambil ID pesanan yang baru saja dibuat
                $message = 'Pesanan Anda berhasil dibuat! Nomor Pesanan Anda adalah #' . $order_id_new . '. Kami akan segera memprosesnya.';
                $message_type = 'success';
            } else {
                $message = 'Terjadi kesalahan saat memproses pesanan: ' . $stmt->error;
                $message_type = 'error';
            }
            $stmt->close();
        } else {
            $message = 'Gagal menyiapkan statement untuk insert pesanan: ' . $conn->error;
            $message_type = 'error';
        }
    }
    $conn->close(); // Tutup koneksi database

    // Redirect ke halaman sukses atau error dengan membawa pesan
    // Menggunakan sesi untuk menyimpan pesan agar tidak terlihat di URL
    session_start();
    $_SESSION['order_message'] = $message;
    $_SESSION['order_message_type'] = $message_type;

    header("Location: order_status.php"); // Akan dibuat halaman order_status.php
    exit();

} else {
    // Jika diakses langsung tanpa POST request, redirect ke halaman utama
    header("Location: index.php");
    exit();
}
?>
