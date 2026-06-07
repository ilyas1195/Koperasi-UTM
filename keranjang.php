<?php
require_once 'config/database.php';
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$title = 'Keranjang Belanja - Koperasi UTM';
include 'includes/header.php';
?>

<section class="page-head" style="padding-bottom:0;">
    <div class="container">
        <div class="bread">
            <a href="index.php">Beranda</a>
            <span class="sep"><i class="fas fa-chevron-right"></i></span>
            <span>Keranjang</span>
        </div>
        <h1 data-aos="fade-up">Keranjang Belanja</h1>
        <p data-aos="fade-up" data-aos-delay="40">Review pesanan Anda sebelum checkout</p>
    </div>
</section>

<section class="cart-wrap">
    <div class="container">
        <?php if (empty($_SESSION['cart'])): ?>
        <div class="cart-empty" data-aos="fade-up">
            <div class="cart-empty-icon"><i class="fas fa-shopping-bag"></i></div>
            <h3>Keranjang Belanja Kosong</h3>
            <p>Belum ada produk yang ditambahkan ke keranjang.</p>
            <a href="katalog.php" class="btn btn-primary"><i class="fas fa-shopping-bag"></i> Mulai Belanja</a>
        </div>
        <?php else:
        $total_keseluruhan = 0;
        $cart_items = [];
        $ids = array_keys($_SESSION['cart']);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("SELECT * FROM produk WHERE id_produk IN ($placeholders)");
        $stmt->execute($ids);
        $products = $stmt->fetchAll();
        $product_map = [];
        foreach ($products as $p) { $product_map[$p['id_produk']] = $p; }
        ?>
        <div class="row g-4">
            <div class="col-lg-8">
                <?php foreach ($_SESSION['cart'] as $id_produk => $qty):
                if (!isset($product_map[$id_produk])) continue;
                $p = $product_map[$id_produk];
                $subtotal = $p['harga'] * $qty;
                $total_keseluruhan += $subtotal;
                ?>
                <div class="cart-item" data-aos="fade-up">
                    <div class="row align-items-center">
                        <div class="col-md-2 col-4">
                            <div class="cart-item-img">
                                <?php if ($p['gambar'] && file_exists('assets/uploads/' . $p['gambar'])): ?>
                                <img src="assets/uploads/<?= htmlspecialchars($p['gambar']) ?>" alt="<?= htmlspecialchars($p['nama_produk']) ?>">
                                <?php else: ?>
                                <i class="fas fa-box placeholder-icon"></i>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-4 col-8">
                            <div class="cart-item-name"><?= htmlspecialchars($p['nama_produk']) ?></div>
                            <div class="cart-item-price"><?= formatRupiah($p['harga']) ?></div>
                        </div>
                        <div class="col-md-3 col-6 mt-3 mt-md-0">
                            <div class="cart-qty">
                                <button class="minus">-</button>
                                <input type="number" data-id="<?= $p['id_produk'] ?>" value="<?= $qty ?>" min="1" max="<?= $p['stok'] ?>">
                                <button class="plus">+</button>
                            </div>
                        </div>
                        <div class="col-md-2 col-4 mt-3 mt-md-0 text-end">
                            <div class="cart-item-sub"><?= formatRupiah($subtotal) ?></div>
                        </div>
                        <div class="col-md-1 col-2 mt-3 mt-md-0 text-end">
                            <button class="cart-del" data-id="<?= $p['id_produk'] ?>"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="col-lg-4">
                <div class="cart-summary" data-aos="fade-left">
                    <h5>Ringkasan Pesanan</h5>
                    <div class="summ-row">
                        <span>Total Item</span>
                        <span><?= array_sum($_SESSION['cart']) ?> produk</span>
                    </div>
                    <div class="summ-row total">
                        <span>Total Pembayaran</span>
                        <span><?= formatRupiah($total_keseluruhan) ?></span>
                    </div>
                    <hr style="border-color:var(--border);">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Nama Pemesan</label>
                        <input type="text" class="form-control" id="namaPemesan" placeholder="Masukkan nama Anda" style="border-radius:8px;border:1px solid var(--border);padding:10px 14px;font-size:13px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Fakultas</label>
                        <input type="text" class="form-control" id="fakultasPemesan" placeholder="Fakultas (opsional)" style="border-radius:8px;border:1px solid var(--border);padding:10px 14px;font-size:13px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Program Studi</label>
                        <input type="text" class="form-control" id="prodiPemesan" placeholder="Program Studi (opsional)" style="border-radius:8px;border:1px solid var(--border);padding:10px 14px;font-size:13px;">
                    </div>
                    <button class="btn-checkout" id="checkoutBtn">
                        <i class="fab fa-whatsapp"></i>
                        Checkout via WhatsApp
                    </button>
                    <p class="text-center mt-3" style="font-size:11px;color:var(--text-muted);">
                        <i class="fas fa-info-circle"></i> Anda akan diarahkan ke WhatsApp untuk konfirmasi pesanan
                    </p>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
