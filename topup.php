<?php
// topup.php
// File ini menampilkan daftar semua item top up game yang tersedia

// Sertakan file koneksi database dan header/footer
include 'config.php';
include 'includes/header.php';

// Mengambil semua item top up yang aktif dari database
$sql_topup = "SELECT id, game_name, item_name, amount, price, image_url FROM game_topup WHERE is_active = TRUE ORDER BY created_at DESC";
$result_topup = $conn->query($sql_topup);
?>

<h2>Daftar Top Up Game</h2>
<p>Pilih top up game favorit Anda:</p>

<section class="topup-section">
    <div class="topup-list">
        <?php
        // Cek apakah ada item top up yang ditemukan
        if ($result_topup && $result_topup->num_rows > 0) :
            // Loop untuk menampilkan setiap item top up
            while ($row = $result_topup->fetch_assoc()) :
        ?>
                <div class="topup-item">
                    <?php if (!empty($row['image_url'])) : ?>
                        <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['game_name'] . ' - ' . $row['item_name']); ?>">
                    <?php else : ?>
                        <img src="https://placehold.co/300x150/e0e0e0/333?text=Top+Up+Game" alt="Placeholder Top Up Game">
                    <?php endif; ?>
                    <h3><?php echo htmlspecialchars($row['game_name']); ?> - <?php echo htmlspecialchars($row['item_name']); ?></h3>
                    <p>Jumlah: <?php echo htmlspecialchars($row['amount']); ?></p>
                    <p>Harga: Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></p>
                    <!-- Link ke halaman pemesanan (order.php) dengan ID dan tipe layanan -->
                    <a href="order.php?id=<?php echo htmlspecialchars($row['id']); ?>&type=topup" class="btn">Beli Sekarang</a>
                </div>
            <?php
            endwhile;
        else :
            // Jika tidak ada item top up yang aktif
            ?>
            <p>Belum ada item top up yang tersedia saat ini.</p>
        <?php endif; ?>
    </div>
</section>

<?php
include 'includes/footer.php';
// Tutup koneksi database di akhir halaman
if (isset($conn) && $conn) {
    $conn->close();
}
?>
