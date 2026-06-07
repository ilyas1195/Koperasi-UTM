<?php
require_once 'config/database.php';
session_start();

$nama = isset($_GET['nama']) ? trim($_GET['nama']) : '';
$fakultas = isset($_GET['fakultas']) ? trim($_GET['fakultas']) : '';
$prodi = isset($_GET['prodi']) ? trim($_GET['prodi']) : '';

if (empty($_SESSION['cart']) || empty($nama)) {
    header('Location: keranjang.php');
    exit;
}

$ids = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT * FROM produk WHERE id_produk IN ($placeholders)");
$stmt->execute($ids);
$products = $stmt->fetchAll();
$product_map = [];
foreach ($products as $p) { $product_map[$p['id_produk']] = $p; }

$total = 0;
$pesan = "Halo Admin Koperasi UTM,\n\n";
$pesan .= "Saya ingin memesan produk berikut:\n\n";

$no = 1;
foreach ($_SESSION['cart'] as $id_produk => $qty) {
    if (!isset($product_map[$id_produk])) continue;
    $p = $product_map[$id_produk];
    $subtotal = $p['harga'] * $qty;
    $total += $subtotal;
    $pesan .= "$no. " . $p['nama_produk'] . "\n";
    $pesan .= "   Jumlah: $qty\n";
    $pesan .= "   Harga: Rp " . number_format($p['harga'], 0, ',', '.') . "\n";
    $pesan .= "   Subtotal: Rp " . number_format($subtotal, 0, ',', '.') . "\n\n";
    $no++;
}

$pesan .= "Total Pembayaran: Rp " . number_format($total, 0, ',', '.') . "\n\n";
$pesan .= "Nama Pemesan: $nama\n";
if ($fakultas) $pesan .= "Fakultas: $fakultas\n";
if ($prodi) $pesan .= "Program Studi: $prodi\n\n";
$pesan .= "Terima kasih.";

$wa_number = '6285727877235';
$wa_url = 'https://wa.me/' . $wa_number . '?text=' . urlencode($pesan);

$_SESSION['cart'] = [];

$title = 'Checkout - Koperasi UTM';
include 'includes/header.php';
?>

<section class="cart-wrap">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center" data-aos="fade-up">
                <div style="font-size:72px;color:var(--secondary);margin-bottom:20px;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2 style="font-family:var(--font-heading);font-weight:700;color:var(--primary-dark);margin-bottom:10px;">Pesanan Berhasil Dibuat!</h2>
                <p style="color:var(--text-light);margin-bottom:28px;">Pesanan Anda telah dibuat. Silakan lanjutkan ke WhatsApp untuk konfirmasi pemesanan.</p>
                <div style="background:var(--surface-alt);border-radius:var(--radius-lg);padding:24px;text-align:left;margin-bottom:28px;border:1px solid var(--border);">
                    <h6 style="font-weight:700;color:var(--primary-dark);margin-bottom:14px;">Detail Pesanan:</h6>
                    <p style="font-size:13px;color:var(--text-light);white-space:pre-line;line-height:1.7;margin-bottom:0;"><?= htmlspecialchars($pesan) ?></p>
                </div>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="<?= $wa_url ?>" target="_blank" class="btn btn-primary"><i class="fab fa-whatsapp"></i> Lanjutkan ke WhatsApp</a>
                    <a href="katalog.php" class="btn btn-outline"><i class="fas fa-shopping-bag"></i> Belanja Lagi</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
