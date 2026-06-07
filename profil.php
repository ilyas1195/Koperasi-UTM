<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
$title = 'Profil Koperasi UTM';
include 'includes/header.php';
?>

<section class="page-head">
    <div class="container">
        <div class="bread">
            <a href="index.php">Beranda</a>
            <span class="sep"><i class="fas fa-chevron-right"></i></span>
            <span>Profil</span>
        </div>
        <h1 data-aos="fade-up">Profil Koperasi UTM</h1>
        <p data-aos="fade-up" data-aos-delay="40">Mengenal lebih dekat Koperasi Mahasiswa Universitas Trunodjoyo Madura</p>
    </div>
</section>

<section class="profile-section">
    <div class="container">
        <!-- Tentang -->
        <div class="row align-items-center mb-5" data-aos="fade-up">
            <div class="col-lg-5 mb-4 mb-lg-0">
                <div class="about-visual">
                    <div class="about-placeholder"><i class="fas fa-handshake"></i></div>
                </div>
            </div>
            <div class="col-lg-6 offset-lg-1">
                <div class="section-label">Tentang</div>
                <h3 style="font-family:var(--font-heading);font-weight:700;color:var(--primary-dark);margin-bottom:12px;">Koperasi UTM</h3>
                <p style="color:var(--text-light);line-height:1.8;">Koperasi Mahasiswa Universitas Trunodjoyo Madura (Koperasi UTM) adalah organisasi koperasi yang didirikan oleh mahasiswa Universitas Trunodjoyo Madura. Berdiri sejak tahun 2010, Koperasi UTM berkomitmen untuk melayani kebutuhan mahasiswa, dosen, tenaga kependidikan, dan masyarakat sekitar kampus.</p>
                <p style="color:var(--text-light);line-height:1.8;">Dengan semangat kebersamaan dan kekeluargaan, Koperasi UTM terus berkembang menjadi koperasi mahasiswa yang profesional, terpercaya, dan menjadi kebanggaan almamater.</p>
            </div>
        </div>

        <!-- Sejarah -->
        <div class="row mb-5" data-aos="fade-up">
            <div class="col-12">
                <div class="section-head">
                    <div class="section-label">Sejarah</div>
                    <h2 class="section-title">Sejarah Singkat</h2>
                </div>
            </div>
            <div class="col-lg-8 mx-auto">
                <div style="background:var(--surface);border-radius:var(--radius-lg);padding:36px;box-shadow:var(--shadow-xs);border:1px solid var(--border);border-left:4px solid var(--premium);">
                    <p style="color:var(--text-light);line-height:1.9;margin-bottom:14px;">Koperasi UTM didirikan pada tahun 2010 oleh sekelompok mahasiswa Universitas Trunodjoyo Madura yang memiliki kepedulian terhadap kebutuhan ekonomi mahasiswa. Berawal dari sebuah inisiatif sederhana untuk menyediakan alat tulis dan kebutuhan pokok sehari-hari, Koperasi UTM kini telah berkembang menjadi koperasi mahasiswa yang menyediakan berbagai produk retail, konsinyasi, dan kebutuhan lainnya.</p>
                    <p style="color:var(--text-light);line-height:1.9;margin-bottom:0;">Seiring berjalannya waktu, Koperasi UTM terus berinovasi dan beradaptasi dengan kebutuhan mahasiswa. Dengan dukungan penuh dari universitas dan seluruh anggota, Koperasi UTM berkomitmen untuk menjadi koperasi mahasiswa terdepan yang melayani dengan sepenuh hati.</p>
                </div>
            </div>
        </div>

        <!-- Visi Misi -->
        <div class="row mb-5" data-aos="fade-up">
            <div class="col-12">
                <div class="section-head">
                    <div class="section-label">Visi & Misi</div>
                    <h2 class="section-title">Visi & Misi</h2>
                </div>
            </div>
            <div class="col-12">
                <div class="vm-grid">
                    <div class="vis-box">
                        <h4><i class="fas fa-eye text-premium"></i> Visi</h4>
                        <p>Menjadi koperasi mahasiswa yang profesional, mandiri, dan berdaya saing tinggi serta menjadi kebanggaan Universitas Trunodjoyo Madura.</p>
                    </div>
                    <div class="mis-box">
                        <h4><i class="fas fa-bullseye" style="color:var(--primary);"></i> Misi</h4>
                        <ul>
                            <li>Menyediakan kebutuhan mahasiswa dengan harga terjangkau dan kualitas terbaik</li>
                            <li>Mengembangkan jiwa kewirausahaan dan ekonomi kreatif mahasiswa</li>
                            <li>Membangun kemitraan dengan UMKM dan berbagai pihak terkait</li>
                            <li>Meningkatkan kesejahteraan anggota melalui program-program koperasi</li>
                            <li>Menerapkan prinsip-prinsip koperasi secara konsisten dan profesional</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gallery -->
        <div class="row" data-aos="fade-up">
            <div class="col-12">
                <div class="section-head">
                    <div class="section-label">Galeri</div>
                    <h2 class="section-title">Galeri Kegiatan</h2>
                    <p class="section-sub">Dokumentasi kegiatan dan aktivitas Koperasi UTM</p>
                </div>
            </div>
            <div class="col-12">
                <div class="gallery-grid">
                    <div class="gallery-item"><div class="gallery-placeholder"><i class="fas fa-image"></i><span>Kegiatan Koperasi</span></div></div>
                    <div class="gallery-item"><div class="gallery-placeholder"><i class="fas fa-image"></i><span>Kegiatan Koperasi</span></div></div>
                    <div class="gallery-item"><div class="gallery-placeholder"><i class="fas fa-image"></i><span>Kegiatan Koperasi</span></div></div>
                    <div class="gallery-item"><div class="gallery-placeholder"><i class="fas fa-image"></i><span>Kegiatan Koperasi</span></div></div>
                    <div class="gallery-item"><div class="gallery-placeholder"><i class="fas fa-image"></i><span>Kegiatan Koperasi</span></div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
