<?php
// order.php
// File ini menampilkan formulir pemesanan untuk jasa joki atau item top up

// Sertakan file koneksi database dan header/footer
include 'config.php';
include 'includes/header.php';

$item_id = $_GET['id'] ?? null;        // ID dari jasa joki atau item top up
$order_type = $_GET['type'] ?? null;   // Tipe pesanan: 'joki' atau 'topup'

$item_details = null; // Variabel untuk menyimpan detail item yang dipesan
$item_name_ordered = '';
$price_ordered = 0;
$image_url_ordered = '';
$game_name_ordered = ''; // Hanya relevan untuk topup, atau nama game dari jasa joki

$error_message = '';

if ($item_id && ($order_type === 'joki' || $order_type === 'topup')) {
    if ($order_type === 'joki') {
        // Query detail jasa joki
        $stmt = $conn->prepare("SELECT service_name, game_name, description, price, image_url FROM services WHERE id = ? AND is_active = TRUE");
        if ($stmt) {
            $stmt->bind_param("i", $item_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 1) {
                $item_details = $result->fetch_assoc();
                $item_name_ordered = $item_details['service_name'];
                $price_ordered = $item_details['price'];
                $image_url_ordered = $item_details['image_url'];
                $game_name_ordered = $item_details['game_name'];
            } else {
                $error_message = 'Jasa joki tidak ditemukan atau tidak aktif.';
            }
            $stmt->close();
        } else {
            $error_message = 'Gagal menyiapkan query jasa joki.';
        }
    } elseif ($order_type === 'topup') {
        // Query detail item top up
        $stmt = $conn->prepare("SELECT game_name, item_name, amount, price, image_url FROM game_topup WHERE id = ? AND is_active = TRUE");
        if ($stmt) {
            $stmt->bind_param("i", $item_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 1) {
                $item_details = $result->fetch_assoc();
                $item_name_ordered = $item_details['game_name'] . ' - ' . $item_details['item_name'] . ' (' . $item_details['amount'] . ')';
                $price_ordered = $item_details['price'];
                $image_url_ordered = $item_details['image_url'];
                $game_name_ordered = $item_details['game_name'];
            } else {
                $error_message = 'Item top up tidak ditemukan atau tidak aktif.';
            }
            $stmt->close();
        } else {
            $error_message = 'Gagal menyiapkan query item top up.';
        }
    }
} else {
    $error_message = 'Parameter tidak valid. Silakan pilih jasa joki atau item top up dari daftar.';
}
?>

<div class="order-container">
    <?php if ($error_message) : ?>
        <div class="message error">
            <p><?php echo htmlspecialchars($error_message); ?></p>
            <a href="index.php" class="btn">Kembali ke Beranda</a>
        </div>
    <?php elseif ($item_details) : ?>
        <h2>Detail Pemesanan</h2>
        <div class="order-summary">
            <?php if (!empty($image_url_ordered)) : ?>
                <img src="<?php echo htmlspecialchars($image_url_ordered); ?>" alt="<?php echo htmlspecialchars($item_name_ordered); ?>" class="order-img">
            <?php else : ?>
                <img src="https://placehold.co/150x150/e0e0e0/333?text=Item" alt="Placeholder" class="order-img">
            <?php endif; ?>
            <h3><?php echo htmlspecialchars($item_name_ordered); ?></h3>
            <p><strong>Game:</strong> <?php echo htmlspecialchars($game_name_ordered); ?></p>
            <p><strong>Harga:</strong> Rp <?php echo number_format($price_ordered, 0, ',', '.'); ?></p>
        </div>

        <h3>Lengkapi Data Diri</h3>
        <form action="order_process.php" method="POST" class="order-form">
            <!-- Hidden inputs untuk detail item yang akan dikirim ke order_process.php -->
            <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item_id); ?>">
            <input type="hidden" name="order_type" value="<?php echo htmlspecialchars($order_type); ?>">
            <input type="hidden" name="item_name_ordered" value="<?php echo htmlspecialchars($item_name_ordered); ?>">
            <input type="hidden" name="price_ordered" value="<?php echo htmlspecialchars($price_ordered); ?>">

            <div class="form-group">
                <label for="customer_name">Nama Lengkap:</label>
                <input type="text" id="customer_name" name="customer_name" required>
            </div>

            <div class="form-group">
                <label for="customer_email">Email:</label>
                <input type="email" id="customer_email" name="customer_email" required>
            </div>

            <div class="form-group">
                <label for="customer_phone">Nomor Telepon/WhatsApp:</label>
                <input type="tel" id="customer_phone" name="customer_phone" placeholder="Contoh: 081234567890" required>
            </div>

            <?php if ($order_type === 'joki' || $order_type === 'topup') : ?>
                <div class="form-group">
                    <label for="game_id">User ID Game (dan ID Zona jika ada):</label>
                    <input type="text" id="game_id" name="game_id" placeholder="Contoh: 12345678 (1234)" required>
                    <small>Pastikan User ID Game Anda benar.</small>
                </div>

                <div class="form-group">
                    <label for="game_server">Server Game (jika diperlukan):</label>
                    <input type="text" id="game_server" name="game_server" placeholder="Contoh: Asia, Global, S1, dll.">
                    <small>Isi jika game Anda membutuhkan informasi server.</small>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="payment_method">Metode Pembayaran:</label>
                <select id="payment_method" name="payment_method" required>
                    <option value="">-- Pilih Metode Pembayaran --</option>
                    <option value="bank_transfer">Transfer Bank (BCA, Mandiri)</option>
                    <option value="e_wallet">E-Wallet (Dana, OVO, GoPay)</option>
                    <!-- Tambahkan opsi pembayaran lain jika ada integrasi payment gateway -->
                </select>
            </div>

            <button type="submit" class="btn">Lanjutkan Pembayaran</button>
        </form>
    <?php endif; ?>
</div>

<style>
    /* CSS tambahan khusus untuk halaman order.php */
    .order-container {
        max-width: 800px;
        margin: 30px auto;
        padding: 30px;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    .order-container h2, .order-container h3 {
        text-align: center;
        color: #2c3e50;
        margin-bottom: 25px;
    }
    .order-summary {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 30px;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 10px;
        border: 1px solid #e0e0e0;
    }
    .order-img {
        max-width: 180px;
        height: 180px;
        object-fit: contain;
        border-radius: 8px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
    }
    .order-summary h3 {
        font-size: 1.8em;
        margin-bottom: 10px;
        color: #34495e;
    }
    .order-summary p {
        margin: 5px 0;
        font-size: 1.1em;
        color: #555;
    }
    .order-summary p strong {
        color: #333;
    }
    .order-summary p:last-of-type {
        font-weight: bold;
        color: #e67e22;
        font-size: 1.3em;
    }

    .order-form .form-group {
        margin-bottom: 20px;
    }
    .order-form label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #444;
    }
    .order-form input[type="text"],
    .order-form input[type="email"],
    .order-form input[type="tel"],
    .order-form select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-sizing: border-box;
        font-size: 1em;
        transition: border-color 0.3s ease;
    }
    .order-form input[type="text"]:focus,
    .order-form input[type="email"]:focus,
    .order-form input[type="tel"]:focus,
    .order-form select:focus {
        border-color: #007bff;
        outline: none;
    }
    .order-form small {
        display: block;
        color: #777;
        margin-top: 5px;
        font-size: 0.85em;
    }
    .order-form .btn {
        width: 100%;
        padding: 15px;
        font-size: 1.2em;
        margin-top: 20px;
    }
    .message {
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        text-align: center;
        font-weight: bold;
    }
    .message.error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    .message.error .btn {
        background-color: #dc3545;
        margin-top: 15px;
    }
    .message.error .btn:hover {
        background-color: #c82333;
    }

    @media (max-width: 600px) {
        .order-container {
            padding: 20px;
            margin: 20px;
        }
        .order-summary {
            padding: 15px;
        }
        .order-img {
            max-width: 120px;
            height: 120px;
        }
        .order-summary h3 {
            font-size: 1.5em;
        }
        .order-summary p {
            font-size: 1em;
        }
        .order-summary p:last-of-type {
            font-size: 1.1em;
        }
        .order-form input, .order-form select, .order-form .btn {
            padding: 10px;
            font-size: 1em;
        }
    }
</style>

<?php
include 'includes/footer.php';
// Tutup koneksi database di akhir halaman
if (isset($conn) && $conn) {
    $conn->close();
}
?>
