<?php
require_once 'config/database.php';
$title = 'Kontak KOPMA UTM';
include 'includes/header.php';
?>

<section class="page-head">
    <div class="container">
        <div class="bread">
            <a href="index.php">Beranda</a>
            <span class="sep"><i class="fas fa-chevron-right"></i></span>
            <span>Kontak</span>
        </div>
        <h1 data-aos="fade-up">Hubungi Kami</h1>
        <p data-aos="fade-up" data-aos-delay="40">Jangan ragu untuk menghubungi KOPMA UTM</p>
    </div>
</section>

<section class="contact-section">
    <div class="container">
        <div class="row g-4 mb-5">
            <div class="col-md-6 col-lg-3" data-aos="fade-up">
                <div class="contact-card">
                    <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <h5>Alamat</h5>
                    <p>Gedung Student Center<br>Universitas Trunodjoyo Madura<br>Jl. Raya Telang, Bangkalan</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="80">
                <div class="contact-card">
                    <div class="contact-icon"><i class="fab fa-whatsapp"></i></div>
                    <h5>WhatsApp</h5>
                    <p><a href="https://wa.me/6285727877235">+62 812-3456-7890</a></p>
                    <p style="font-size:12px;">Senin - Jumat, 08.00 - 16.00</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="160">
                <div class="contact-card">
                    <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                    <h5>Email</h5>
                    <p><a href="mailto:kopma@trunojoyo.ac.id">kopma@trunojoyo.ac.id</a></p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="240">
                <div class="contact-card">
                    <div class="contact-icon"><i class="fab fa-instagram"></i></div>
                    <h5>Instagram</h5>
                    <p><a href="https://instagram.com/kopmautm" target="_blank">@kopmautm</a></p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12" data-aos="fade-up">
                <div class="contact-map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3958.1288897424656!2d112.788308!3d-7.129483!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd803a5b8a8b8b9%3A0x8a8b8b9a8b8b8b!2sUniversitas%20Trunodjoyo%20Madura!5e0!3m2!1sid!2sid!4v1" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
