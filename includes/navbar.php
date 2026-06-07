<nav class="navbar" id="mainNav">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="assets/img/logo-koperasi.png" alt="Koperasi UTM" width="34" height="34" style="border-radius: 8px;">
            <span class="brand-name">Koperasi UTM</span>
            <span class="brand-dot">.</span>
        </a>

        <div class="nav-menu" id="navMenu">
            <ul class="nav-list">
                <li><a href="index.php" class="nav-link-custom <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">Beranda</a></li>
                <li><a href="profil.php" class="nav-link-custom <?= basename($_SERVER['PHP_SELF']) == 'profil.php' ? 'active' : '' ?>">Profil</a></li>
                <li><a href="katalog.php" class="nav-link-custom <?= basename($_SERVER['PHP_SELF']) == 'katalog.php' ? 'active' : '' ?>">Katalog</a></li>
                <li><a href="kontak.php" class="nav-link-custom <?= basename($_SERVER['PHP_SELF']) == 'kontak.php' ? 'active' : '' ?>">Kontak</a></li>
            </ul>
        </div>

        <div class="nav-right">
            <a href="keranjang.php" class="cart-btn" id="cartBtn">
                <i class="fas fa-shopping-bag"></i>
                <span class="cart-count" id="cartBadge">0</span>
            </a>
            <button class="hamburger" id="hamburger" aria-label="Toggle navigation">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('navMenu');
    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function() {
            this.classList.toggle('active');
            navMenu.classList.toggle('show');
            document.body.classList.toggle('nav-open');
        });
        document.querySelectorAll('.nav-link-custom').forEach(function(link) {
            link.addEventListener('click', function() {
                hamburger.classList.remove('active');
                navMenu.classList.remove('show');
                document.body.classList.remove('nav-open');
            });
        });
    }
    updateCartBadge();
});

function updateCartBadge() {
    fetch('ajax_cart_count.php')
        .then(function(res) { return res.json(); })
        .then(function(data) {
            var badge = document.getElementById('cartBadge');
            if (badge) {
                badge.textContent = data.count;
                badge.style.display = data.count > 0 ? 'flex' : 'none';
            }
        });
}
</script>
