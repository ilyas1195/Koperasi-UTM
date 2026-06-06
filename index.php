<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
$title = 'Katalog Produk KOPMA UTM';
$statistik = hitungStatistik($pdo);
$produk_terbaru = getProdukTerbaru($pdo, 4);
$kategori_utama = getKategori($pdo);
include 'includes/header.php';
?>

<!-- Hero -->
<section class="hero" id="beranda">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <div class="hero-badge">
                        <span class="hero-badge-dot"></span>
                        Katalog Digital Resmi
                    </div>
                    <h1 class="hero-title">
                        Katalog Digital <br>
                        <span class="highlight">Koperasi Mahasiswa</span><br>
                        Universitas Trunodjoyo Madura
                    </h1>
                    <p class="hero-text">
                        Menyediakan berbagai kebutuhan mahasiswa dengan pelayanan yang mudah, cepat, dan terpercaya.
                    </p>
                    <div class="hero-actions">
                        <a href="katalog.php" class="btn btn-primary">
                            Jelajahi Produk
                            <i class="fas fa-arrow-right"></i>
                        </a>
                        <a href="kontak.php" class="btn btn-outline">
                            <i class="fas fa-phone-alt"></i>
                            Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-visual">
                    <div class="hero-card-main">
                        <svg viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="100" y="40" width="200" height="140" rx="16" fill="#0F5132" fill-opacity="0.1"/>
                            <rect x="60" y="120" width="80" height="100" rx="12" fill="#D4AF37" fill-opacity="0.15"/>
                            <rect x="260" y="100" width="80" height="120" rx="12" fill="#0F5132" fill-opacity="0.08"/>
                            <circle cx="200" cy="100" r="40" fill="#D4AF37" fill-opacity="0.1"/>
                            <rect x="150" y="160" width="100" height="8" rx="4" fill="#0F5132" fill-opacity="0.15"/>
                            <rect x="170" y="180" width="60" height="6" rx="3" fill="#D4AF37" fill-opacity="0.2"/>
                            <rect x="80" y="200" width="40" height="6" rx="3" fill="#0F5132" fill-opacity="0.1"/>
                            <rect x="280" y="180" width="40" height="6" rx="3" fill="#0F5132" fill-opacity="0.1"/>
                        </svg>
                    </div>
                    <div class="hero-float" style="top:8%;right:-6%;">
                        <div class="hero-float-icon primary"><i class="fas fa-box"></i></div>
                        <div>
                            <span class="hero-float-label">Total Produk</span>
                            <span class="hero-float-value"><?= number_format($statistik['total_produk']) ?>+</span>
                        </div>
                    </div>
                    <div class="hero-float" style="bottom:12%;left:-8%;animation-delay:2.5s;">
                        <div class="hero-float-icon gold"><i class="fas fa-star"></i></div>
                        <div>
                            <span class="hero-float-label">Terpercaya</span>
                            <span class="hero-float-value">Sejak 2010</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-scroll scroll-to" href="#tentang">
        <span>Scroll</span>
        <i class="fas fa-chevron-down"></i>
    </div>
</section>

<!-- About -->
<section class="section section-alt" id="tentang">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 mb-4 mb-lg-0" data-aos="fade-right">
                <div class="about-visual">
                    <div class="about-placeholder">
                        <i class="fas fa-university"></i>
                    </div>
                    <div class="about-badge">
                        <i class="fas fa-calendar-alt me-2"></i> Sejak 2010
                    </div>
                </div>
            </div>
            <div class="col-lg-6 offset-lg-1" data-aos="fade-left">
                <div class="about-text">
                    <div class="section-label">Tentang Kami</div>
                    <h3>Koperasi Mahasiswa <br>Universitas Trunodjoyo Madura</h3>
                    <p>KOPMA UTM adalah koperasi mahasiswa yang berorientasi pada pelayanan mahasiswa, mendukung ekonomi kreatif mahasiswa, dan menyediakan berbagai kebutuhan sehari-hari dengan harga terjangkau dan kualitas terbaik.</p>
                    <div class="about-grid">
                        <div class="about-item"><i class="fas fa-hand-holding-heart"></i><span>Melayani Mahasiswa</span></div>
                        <div class="about-item"><i class="fas fa-lightbulb"></i><span>Ekonomi Kreatif</span></div>
                        <div class="about-item"><i class="fas fa-shopping-bag"></i><span>Kebutuhan Harian</span></div>
                        <div class="about-item"><i class="fas fa-handshake"></i><span>Terpercaya</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats -->
<section class="stats-wrap">
    <div class="container">
        <div class="row">
            <div class="col-6 col-lg-3" data-aos="fade-up">
                <div class="stat-block">
                    <div class="stat-icon"><i class="fas fa-box"></i></div>
                    <div class="stat-num" data-target="<?= $statistik['total_produk'] ?>">0</div>
                    <div class="stat-label">Total Produk</div>
                </div>
            </div>
            <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="80">
                <div class="stat-block">
                    <div class="stat-icon"><i class="fas fa-tags"></i></div>
                    <div class="stat-num" data-target="<?= $statistik['total_kategori'] ?>">0</div>
                    <div class="stat-label">Total Kategori</div>
                </div>
            </div>
            <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="160">
                <div class="stat-block">
                    <div class="stat-icon"><i class="fas fa-store"></i></div>
                    <div class="stat-num" data-target="<?= $statistik['produk_retail'] ?>">0</div>
                    <div class="stat-label">Produk Retail</div>
                </div>
            </div>
            <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="240">
                <div class="stat-block">
                    <div class="stat-icon"><i class="fas fa-handshake"></i></div>
                    <div class="stat-num" data-target="<?= $statistik['produk_konsinyasi'] ?>">0</div>
                    <div class="stat-label">Produk Konsinyasi</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Kategori -->
<section class="section" id="kategori">
    <div class="container">
        <div class="section-head" data-aos="fade-up">
            <div class="section-label">Kategori</div>
            <h2 class="section-title">Kategori Produk</h2>
            <p class="section-sub">Temukan berbagai kebutuhan Anda dalam kategori produk yang tersedia</p>
        </div>
        <div class="row g-4">
            <?php
            $kategori_data = [
                ['nama' => 'Retail', 'ikon' => 'fas fa-store', 'desc' => 'Produk makanan dan minuman untuk kebutuhan sehari-hari mahasiswa.'],
                ['nama' => 'Konsinyasi', 'ikon' => 'fas fa-handshake', 'desc' => 'Produk titipan UMKM dan mitra kerja sama KOPMA UTM.'],
                ['nama' => 'Lainnya', 'ikon' => 'fas fa-box-open', 'desc' => 'Alat tulis, perlengkapan kuliah, aksesoris, dan peralatan pendukung.']
            ];
            foreach ($kategori_data as $index => $kat):
            ?>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="<?= $index * 120 ?>">
                <div class="cat-card">
                    <div class="cat-icon"><i class="<?= $kat['ikon'] ?>"></i></div>
                    <h4><?= $kat['nama'] ?></h4>
                    <p><?= $kat['desc'] ?></p>
                    <a href="katalog.php?kategori=<?= strtolower($kat['nama']) ?>" class="cat-link">
                        Lihat Produk <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Produk Terbaru -->
<section class="section section-alt" id="produk">
    <div class="container">
        <div class="section-head" data-aos="fade-up">
            <div class="section-label">Produk</div>
            <h2 class="section-title">Produk Terbaru</h2>
            <p class="section-sub">Lihat produk-produk terbaru yang tersedia di KOPMA UTM</p>
        </div>
        <div class="row g-4">
            <?php if (empty($produk_terbaru)): ?>
            <div class="col-12 text-center py-5" data-aos="fade-up">
                <p class="text-muted">Belum ada produk tersedia.</p>
            </div>
            <?php else: ?>
            <?php foreach ($produk_terbaru as $index => $p): ?>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
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

<!-- CTA -->
<section class="cta-wrap">
    <div class="container">
        <div class="cta-content text-center" data-aos="fade-up">
            <h2 class="cta-title">Lihat Seluruh Katalog Produk</h2>
            <p class="cta-text">Jelajahi semua produk yang tersedia dan temukan kebutuhan Anda bersama KOPMA UTM</p>
            <a href="katalog.php" class="btn-cta">
                <i class="fas fa-shopping-bag"></i>
                Lihat Katalog Lengkap
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
