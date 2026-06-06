<?php
try {
    require_once 'config/database.php';
    require_once 'includes/functions.php';

    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
    $kategori = isset($_GET['kategori']) ? $_GET['kategori'] : 'semua';
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'terbaru';

    $produk = searchProduk($pdo, $keyword, $kategori, $sort);

    if (empty($produk)): ?>
        <div class="col-12 text-center py-5">
            <div style="font-size:44px;color:rgba(15,81,50,0.1);margin-bottom:14px;">
                <i class="fas fa-box-open"></i>
            </div>
            <h5 style="font-weight:600;color:var(--primary-dark);">Produk Tidak Ditemukan</h5>
            <p style="color:var(--text-muted);">Coba kata kunci atau filter lain</p>
        </div>
    <?php else: ?>
        <?php foreach ($produk as $index => $p): ?>
        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?= ($index % 4) * 75 ?>">
            <div class="prod-card">
                <div class="prod-img-wrap">
                    <?php if ($p['gambar'] && file_exists('assets/uploads/' . $p['gambar'])): ?>
                    <img src="assets/uploads/<?= htmlspecialchars($p['gambar']) ?>" alt="<?= htmlspecialchars($p['nama_produk']) ?>" class="prod-img" loading="lazy">
                    <?php else: ?>
                    <div class="prod-img-placeholder"><i class="fas fa-box"></i></div>
                    <?php endif; ?>
                    <?php $bc = strtolower($p['nama_kategori'] ?? 'lainnya'); ?>
                    <span class="prod-badge <?= htmlspecialchars($bc) ?>"><?= htmlspecialchars($p['nama_kategori'] ?? '') ?></span>
                    <?php if (($p['stok'] ?? 0) > 0): ?>
                    <span class="prod-stock <?= ($p['stok'] ?? 0) <= 5 ? 'low' : 'ok' ?>">
                        <?= ($p['stok'] ?? 0) <= 5 ? 'Stok Terbatas' : 'Tersedia' ?>
                    </span>
                    <?php else: ?>
                    <span class="prod-stock out">Habis</span>
                    <?php endif; ?>
                </div>
                <div class="prod-body">
                    <div class="prod-cat"><?= htmlspecialchars($p['nama_kategori'] ?? '') ?></div>
                    <h5 class="prod-name"><?= htmlspecialchars($p['nama_produk'] ?? '') ?></h5>
                    <p class="prod-desc"><?= htmlspecialchars(substr($p['deskripsi'] ?? '', 0, 80)) ?></p>
                    <div class="prod-price"><?= formatRupiah((int)($p['harga'] ?? 0)) ?></div>
                    <div class="prod-foot">
                        <a href="detail_produk.php?id=<?= $p['id_produk'] ?>" class="btn-prod btn-prod-detail">Detail</a>
                        <?php if (($p['stok'] ?? 0) > 0): ?>
                        <button class="btn-prod btn-prod-cart" onclick="tambahKeranjang(<?= $p['id_produk'] ?>)">
                            <i class="fas fa-shopping-bag"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif;
} catch (Exception $e) {
    echo '<div class="col-12 text-center py-5"><p style="color:#dc3545;">Error: ' . htmlspecialchars($e->getMessage()) . '</p></div>';
}
