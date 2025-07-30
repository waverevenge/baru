<form action="order_process.php" method="POST">
    <input type="hidden" name="item_id" value="[ID Joki/Top Up]">
    <input type="hidden" name="order_type" value="joki/topup">
    <input type="hidden" name="item_name_ordered" value="[Nama Item]">
    <input type="hidden" name="price_ordered" value="[Harga Item]">

    <label for="customer_name">Nama Lengkap:</label>
    <input type="text" id="customer_name" name="customer_name" required>

    <label for="customer_email">Email:</label>
    <input type="email" id="customer_email" name="customer_email" required>

    <label for="customer_phone">Nomor Telepon/WhatsApp:</label>
    <input type="tel" id="customer_phone" name="customer_phone" required>

    <label for="game_id">User ID Game:</label>
    <input type="text" id="game_id" name="game_id" required>

    <label for="game_server">Server Game (jika ada):</label>
    <input type="text" id="game_server" name="game_server">

    <label for="payment_method">Metode Pembayaran:</label>
    <select id="payment_method" name="payment_method" required>
        <option value="bank_transfer">Transfer Bank</option>
        <option value="e_wallet">E-Wallet</option>
        </select>

    <button type="submit">Pesan Sekarang</button>
</form>