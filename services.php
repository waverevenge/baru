<?php
// services.php
// File ini menampilkan daftar semua jasa joki yang tersedia

// Sertakan file koneksi database dan header/footer
include 'config.php';
include 'includes/header.php';

// Mengambil semua jasa joki yang aktif dari database
$sql_services = "SELECT id, service_name, game_name, description, price, image_url FROM services WHERE is_active = TRUE ORDER BY created_at DESC";
$result_services = $conn->query($sql_services);
?>

<h2>Daftar Jasa Joki</h2>
<p>Pilih jasa joki sesuai kebutuhan game Anda:</p>

<section class="services-section">
    <div class="service-list">
        <?php
        // Cek apakah ada jasa joki yang ditemukan
        if ($result_services && $result_services->num_rows > 0) :
            // Loop untuk menampilkan setiap jasa joki
            while ($row = $result_services->fetch_assoc()) :
        ?>
                <div class="service-item">
                    <?php if (!empty($row['image_url'])) : ?>
                        <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['service_name']); ?>">
                    <?php else : ?>
                        <img src="https://placehold.co/300x150/e0e0e0/333?text=Jasa+Joki" alt="Placeholder Jasa Joki">
                    <?php endif; ?>
                    <h3><?php echo htmlspecialchars($row['service_name']); ?></h3>
                    <p>Game: <?php echo htmlspecialchars($row['game_name']); ?></p>
                    <p><?php echo htmlspecialchars($row['description']); ?></p>
                    <p>Harga: Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></p>
                    <!-- Link ke halaman pemesanan (order.php) dengan ID dan tipe layanan -->
                    <a href="order.php?id=<?php echo htmlspecialchars($row['id']); ?>&type=joki" class="btn">Pesan Sekarang</a>
                </div>
            <?php
            endwhile;
        else :
            // Jika tidak ada jasa joki yang aktif
            ?>
            <p>Belum ada jasa joki yang tersedia saat ini.</p>
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
