<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$title = 'Katalog Produk KOPMA UTM';
include 'includes/header.php';

$semua_produk = searchProduk($pdo, '', 'semua', 'terbaru');
?>

<section class="page-head">
    <div class="container">
        <div class="bread">
            <a href="index.php">Beranda</a>
            <span class="sep"><i class="fas fa-chevron-right"></i></span>
            <span>Katalog</span>
        </div>
        <h1 data-aos="fade-up">Katalog Produk</h1>
        <p data-aos="fade-up" data-aos-delay="40">Temukan kebutuhan Anda di KOPMA UTM</p>
    </div>
</section>

<section class="section pt-4">
    <div class="container">
        <div class="filter-bar" data-aos="fade-up">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="filter-search">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" placeholder="Cari produk..." autocomplete="off">
                    </div>
                </div>
                <div class="col-md-3">
                    <select id="filterKategori" class="filter-sel">
                        <option value="semua">Semua Produk</option>
                        <option value="retail">Retail</option>
                        <option value="makanan">Makanan</option>
                        <option value="minuman">Minuman</option>
                        <option value="konsinyasi">Konsinyasi</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="sortSelect" class="filter-sel">
                        <option value="terbaru">Terbaru</option>
                        <option value="nama-asc">Nama A-Z</option>
                        <option value="nama-desc">Nama Z-A</option>
                        <option value="harga-termurah">Harga Termurah</option>
                        <option value="harga-tertinggi">Harga Tertinggi</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-center">
                    <button class="btn btn-primary w-100" onclick="cariProduk()" style="padding:10px 20px;font-size:13px;">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <div class="filter-chips">
                        <button class="filter-chip active" data-filter="semua">Semua</button>
                        <button class="filter-chip" data-filter="retail">Retail</button>
                        <button class="filter-chip" data-filter="makanan">Makanan</button>
                        <button class="filter-chip" data-filter="minuman">Minuman</button>
                        <button class="filter-chip" data-filter="konsinyasi">Konsinyasi</button>
                        <button class="filter-chip" data-filter="lainnya">Lainnya</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4" id="produkContainer">
            <?php if (empty($semua_produk)): ?>
            <div class="col-12 text-center py-5">
                <div style="font-size:44px;color:rgba(15,81,50,0.1);margin-bottom:14px;">
                    <i class="fas fa-box-open"></i>
                </div>
                <h5 style="font-weight:600;color:var(--primary-dark);">Belum Ada Produk</h5>
                <p style="color:var(--text-muted);">Belum ada produk yang tersedia saat ini.</p>
            </div>
            <?php else: ?>
            <?php foreach ($semua_produk as $index => $p): ?>
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
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
function cariProduk() {
    var kw = document.getElementById('searchInput').value;
    var kat = document.getElementById('filterKategori').value;
    var s = document.getElementById('sortSelect').value;
    var container = document.getElementById('produkContainer');
    container.innerHTML = '<div class="col-12 text-center py-5" style="color:var(--text-muted);"><i class="fas fa-spinner fa-spin me-2"></i>Mencari...</div>';
    fetch('ajax_produk.php?keyword=' + encodeURIComponent(kw) + '&kategori=' + encodeURIComponent(kat) + '&sort=' + encodeURIComponent(s))
        .then(function(r) { return r.text(); })
        .then(function(html) {
            container.innerHTML = html;
            if (typeof VanillaTilt !== 'undefined') {
                VanillaTilt.init(container.querySelectorAll('.prod-card'), { max:4, speed:400, glare:true, 'max-glare':0.1, scale:1.01 });
            }
            if (window.AOS) { AOS.refresh(); }
        })
        .catch(function() {
            container.innerHTML = '<div class="col-12 text-center py-5"><p style="color:var(--text-muted);">Gagal memuat produk.</p></div>';
        });
}

document.addEventListener('DOMContentLoaded', function() {
    var si = document.getElementById('searchInput');
    var fk = document.getElementById('filterKategori');
    var ss = document.getElementById('sortSelect');
    if (si) { var to; si.addEventListener('input', function() { clearTimeout(to); to = setTimeout(cariProduk, 500); }); }
    if (fk) { fk.addEventListener('change', cariProduk); }
    if (ss) { ss.addEventListener('change', cariProduk); }

    document.querySelectorAll('.filter-chip').forEach(function(b) {
        b.addEventListener('click', function() {
            document.querySelectorAll('.filter-chip').forEach(function(x) { x.classList.remove('active'); });
            this.classList.add('active');
            if (fk) { fk.value = this.getAttribute('data-filter'); }
            cariProduk();
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
