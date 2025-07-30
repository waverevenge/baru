<?php
// order_status.php
// File ini menampilkan status pesanan setelah formulir diproses

session_start(); // Mulai sesi untuk mengambil pesan

// Sertakan header/footer. Tidak perlu config.php karena tidak ada interaksi DB langsung di sini.
include 'includes/header.php';

$message = $_SESSION['order_message'] ?? 'Terjadi kesalahan tidak dikenal saat memproses pesanan Anda.';
$message_type = $_SESSION['order_message_type'] ?? 'error';

// Hapus pesan dari sesi setelah ditampilkan
unset($_SESSION['order_message']);
unset($_SESSION['order_message_type']);
?>

<div class="order-status-container">
    <div class="message <?php echo htmlspecialchars($message_type); ?>">
        <p><?php echo htmlspecialchars($message); ?></p>
    </div>

    <?php if ($message_type === 'success'): ?>
        <h3>Langkah Selanjutnya:</h3>
        <p>Mohon tunggu konfirmasi dari admin kami. Anda akan dihubungi melalui email atau WhatsApp yang telah Anda berikan.</p>
        <p>Untuk metode pembayaran transfer bank/e-wallet, Anda akan menerima instruksi pembayaran.</p>
        <div class="action-buttons">
            <a href="index.php" class="btn">Kembali ke Beranda</a>
            <!-- Opsional: Tambahkan link untuk melihat riwayat pesanan jika ada fitur login user -->
            <!-- <a href="my_orders.php" class="btn secondary-btn">Lihat Riwayat Pesanan</a> -->
        </div>
    <?php else: ?>
        <p>Pesanan Anda gagal diproses. Silakan coba lagi atau hubungi dukungan pelanggan kami.</p>
        <div class="action-buttons">
            <a href="index.php" class="btn">Kembali ke Beranda</a>
        </div>
    <?php endif; ?>
</div>

<style>
    /* CSS tambahan khusus untuk halaman order_status.php */
    .order-status-container {
        max-width: 600px;
        margin: 50px auto;
        padding: 40px;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        text-align: center;
    }
    .order-status-container h3 {
        color: #2c3e50;
        margin-top: 30px;
        margin-bottom: 20px;
    }
    .order-status-container p {
        font-size: 1.1em;
        color: #555;
        margin-bottom: 15px;
    }
    .action-buttons {
        margin-top: 30px;
    }
    .action-buttons .btn {
        margin: 10px;
        padding: 12px 25px;
        font-size: 1.1em;
    }
    .action-buttons .secondary-btn {
        background-color: #6c757d;
    }
    .action-buttons .secondary-btn:hover {
        background-color: #5a6268;
    }
    .message {
        padding: 20px;
        border-radius: 10px;
        font-size: 1.2em;
        font-weight: bold;
    }
    .message.success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .message.error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    @media (max-width: 600px) {
        .order-status-container {
            padding: 20px;
            margin: 20px auto;
        }
        .message {
            font-size: 1em;
            padding: 15px;
        }
        .order-status-container p {
            font-size: 1em;
        }
        .action-buttons .btn {
            display: block;
            width: calc(100% - 20px);
            margin: 10px auto;
        }
    }
</style>

<?php
include 'includes/footer.php';
?>
