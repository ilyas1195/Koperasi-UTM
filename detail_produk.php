<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$produk = getProdukDetail($pdo, $id);

if (!$produk) {
    header('Location: katalog.php');
    exit;
}

$title = htmlspecialchars($produk['nama_produk']) . ' - KOPMA UTM';
include 'includes/header.php';
?>

<section class="page-head" style="padding-bottom:0;">
    <div class="container">
        <div class="bread">
            <a href="index.php">Beranda</a>
            <span class="sep"><i class="fas fa-chevron-right"></i></span>
            <a href="katalog.php">Katalog</a>
            <span class="sep"><i class="fas fa-chevron-right"></i></span>
            <span><?= htmlspecialchars($produk['parent_kategori'] ?? $produk['nama_kategori']) ?></span>
        </div>
    </div>
</section>

<section class="pd-wrap">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="pd-image">
                    <?php if ($produk['gambar'] && file_exists('assets/uploads/' . $produk['gambar'])): ?>
                    <img src="assets/uploads/<?= htmlspecialchars($produk['gambar']) ?>" alt="<?= htmlspecialchars($produk['nama_produk']) ?>">
                    <?php else: ?>
                    <i class="fas fa-box placeholder-icon"></i>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="pd-info">
                    <div class="pd-cat">
                        <i class="fas fa-tag me-1"></i>
                        <?= htmlspecialchars($produk['nama_kategori']) ?>
                        <?php if ($produk['parent_kategori']): ?>
                        / <?= htmlspecialchars($produk['parent_kategori']) ?>
                        <?php endif; ?>
                    </div>
                    <h1><?= htmlspecialchars($produk['nama_produk']) ?></h1>
                    <div class="pd-price"><?= formatRupiah($produk['harga']) ?></div>
                    <div class="pd-stock">
                        <span class="pd-dot <?= $produk['stok'] > 0 ? ($produk['stok'] <= 5 ? 'low' : 'ok') : 'out' ?>"></span>
                        <span>
                            <?php if ($produk['stok'] > 0): ?>
                            Stok: <strong><?= $produk['stok'] ?></strong>
                            <?php if ($produk['stok'] <= 5): ?>
                            <span class="text-warning">(Hampir Habis)</span>
                            <?php endif; ?>
                            <?php else: ?>
                            <strong class="text-danger">Stok Habis</strong>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="pd-desc"><?= nl2br(htmlspecialchars($produk['deskripsi'])) ?></div>

                    <?php if ($produk['stok'] > 0): ?>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span style="font-weight:600;font-size:14px;">Jumlah:</span>
                        <div class="qty-group">
                            <button class="qty-btn minus"><i class="fas fa-minus"></i></button>
                            <input type="number" class="qty-input" id="detailQty" value="1" min="1" max="<?= $produk['stok'] ?>">
                            <button class="qty-btn plus"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    <button class="btn btn-primary" onclick="tambahKeranjangDetail(<?= $produk['id_produk'] ?>)">
                        <i class="fas fa-shopping-bag"></i>
                        Tambah ke Keranjang
                    </button>
                    <?php else: ?>
                    <button class="btn btn-secondary" disabled style="opacity:0.5;cursor:not-allowed;padding:14px 32px;border-radius:var(--radius-sm);font-weight:600;border:none;">
                        <i class="fas fa-times-circle"></i> Stok Habis
                    </button>
                    <?php endif; ?>

                    <div style="margin-top:28px;padding-top:20px;border-top:1px solid var(--border);">
                        <div class="d-flex align-items-center gap-2 mb-2" style="font-size:13px;color:var(--text-muted);">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Ditambahkan: <?= date('d M Y', strtotime($produk['created_at'])) ?></span>
                        </div>
                        <div class="d-flex align-items-center gap-2" style="font-size:13px;color:var(--text-muted);">
                            <i class="fas fa-store"></i>
                            <span>Koperasi Mahasiswa Universitas Trunodjoyo Madura</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
