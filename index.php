<?php
// index.php

// Sertakan file koneksi database dari root proyek
include 'config.php';

// Sertakan header halaman. File ini sudah berisi:
// <!DOCTYPE html>, <html lang="id">, <head> (dengan meta, title, CSS), <body> pembuka,
// <header> (dengan navigasi), dan <main> pembuka.
include 'includes/header.php';

// --- BAGIAN INI ADALAH KONTEN UNIK UNTUK HALAMAN INDEX.PHP ---
?>

<!-- Hero Section: Bagian paling atas yang menarik perhatian -->
<section class="hero-section">
    <div class="hero-content">
        <h1>Joki & Top Up Game Terpercaya!</h1>
        <p>Tingkatkan pengalaman bermain game Anda dengan layanan joki profesional dan top up instan!</p>
        <div class="hero-buttons">
            <a href="services.php" class="btn primary-btn">Pesan Jasa Joki Sekarang!</a>
            <a href="topup.php" class="btn secondary-btn">Top Up Game Instan!</a>
            
        </div>
    </div>
</section>

<!-- Section: Keunggulan Kami -->
<section class="features-section">
    <h2>Mengapa Memilih Kami?</h2>
    <div class="features-grid">
        <div class="feature-item">
            <div class="icon-circle" style="background-color: #e0f7fa;"><!-- Placeholder for icon -->🚀</div>
            <h3>Proses Cepat & Instan</h3>
            <p>Layanan joki dan top up diproses dalam hitungan menit, tanpa antrean panjang.</p>
        </div>
        <div class="feature-item">
            <div class="icon-circle" style="background-color: #e8f5e9;"><!-- Placeholder for icon -->🔒</div>
            <h3>Aman & Terpercaya</h3>
            <p>Data akun Anda terjamin keamanannya, didukung oleh reputasi yang solid.</p>
        </div>
        <div class="feature-item">
            <div class="icon-circle" style="background-color: #fce4ec;"><!-- Placeholder for icon -->💰</div>
            <h3>Harga Bersaing</h3>
            <p>Dapatkan harga terbaik untuk setiap jasa joki dan item top up game.</p>
        </div>
        <div class="feature-item">
            <div class="icon-circle" style="background-color: #ede7f6;"><!-- Placeholder for icon -->💬</div>
            <h3>Dukungan Pelanggan</h3>
            <p>Tim support kami siap membantu Anda 24/7 jika ada kendala.</p>
        </div>
    </div>
</section>

<!-- Section: Jasa Joki Populer -->
<section class="services-section">
    <h2>Jasa Joki Populer</h2>
    <div class="service-list">
        <?php
        // Ambil 3 jasa joki terbaru/populer
        $sql_services = "SELECT id, service_name, game_name, description, price, image_url FROM services WHERE is_active = TRUE ORDER BY created_at DESC LIMIT 3";
        $result_services = $conn->query($sql_services);

        if ($result_services && $result_services->num_rows > 0) {
            while ($row = $result_services->fetch_assoc()) {
                echo "<div class='service-item'>";
                // Menggunakan placeholder jika image_url kosong
                echo "<img src='" . htmlspecialchars($row['image_url'] ?? 'https://placehold.co/300x150/e0e0e0/333?text=Jasa+Joki') . "' alt='" . htmlspecialchars($row['service_name']) . "'>";
                echo "<h3>" . htmlspecialchars($row['service_name']) . "</h3>";
                echo "<p>" . htmlspecialchars($row['description']) . "</p>";
                echo "<p>Harga: Rp " . number_format($row['price'], 0, ',', '.') . "</p>";
                echo "<a href='order.php?id=" . htmlspecialchars($row['id']) . "&type=joki' class='btn'>Pesan Sekarang</a>";
                echo "</div>";
            }
        } else {
            echo "<p>Belum ada jasa joki yang tersedia.</p>";
        }
        ?>
    </div>
    <div style="text-align: center; margin-top: 30px;">
        <a href="services.php" class="btn secondary-btn">Lihat Semua Jasa Joki</a>
    </div>
</section>

<hr>

<!-- Section: Top Up Game Populer -->
<section class="topup-section">
    <h2>Top Up Game Populer</h2>
    <div class="topup-list">
        <?php
        // Ambil 3 top up game terbaru/populer
        $sql_topup = "SELECT id, game_name, item_name, amount, price, image_url FROM game_topup WHERE is_active = TRUE ORDER BY created_at DESC LIMIT 3";
        $result_topup = $conn->query($sql_topup);

        if ($result_topup && $result_topup->num_rows > 0) {
            while ($row = $result_topup->fetch_assoc()) {
                echo "<div class='topup-item'>";
                // Menggunakan placeholder jika image_url kosong
                echo "<img src='" . htmlspecialchars($row['image_url'] ?? 'https://placehold.co/300x150/e0e0e0/333?text=Top+Up') . "' alt='" . htmlspecialchars($row['game_name'] . " - " . $row['item_name']) . "'>";
                echo "<h3>" . htmlspecialchars($row['game_name']) . " - " . htmlspecialchars($row['item_name']) . "</h3>";
                echo "<p>Jumlah: " . htmlspecialchars($row['amount']) . "</p>";
                echo "<p>Harga: Rp " . number_format($row['price'], 0, ',', '.') . "</p>";
                echo "<a href='order.php?id=" . htmlspecialchars($row['id']) . "&type=topup' class='btn'>Beli Sekarang</a>";
                echo "</div>";
            }
        } else {
            echo "<p>Belum ada top up game yang tersedia.</p>";
        }
        ?>
    </div>
    <div style="text-align: center; margin-top: 30px;">
        <a href="topup.php" class="btn secondary-btn">Lihat Semua Top Up Game</a>
    </div>
</section>

<?php
// --- AKHIR DARI KONTEN UNIK UNTUK HALAMAN INDEX.PHP ---

// Sertakan footer halaman. File ini sudah berisi:
// </main>, <footer>, <script>, </body> penutup, dan </html> penutup.
include 'includes/footer.php';

// Tutup koneksi database setelah semua script selesai dijalankan
if (isset($conn) && $conn) {
    $conn->close();
}
?>
