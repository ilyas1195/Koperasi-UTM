/* ==================================================
   Koperasi UTM — Main JavaScript
   ================================================== */

document.addEventListener('DOMContentLoaded', function () {

    /* ---- Loading Screen ---- */
    var loader = document.getElementById('loadingScreen');
    var main = document.getElementById('mainContent');

    if (loader && main) {
        setTimeout(function () {
            loader.classList.add('hidden');
            main.style.opacity = '1';
            document.body.style.overflow = '';
        }, 800);
        setTimeout(function () {
            loader.remove();
        }, 1300);
    }

    /* ---- AOS ---- */
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            once: true,
            offset: 80,
            easing: 'ease-out-cubic'
        });
    }

    /* ---- Navbar Scroll ---- */
    var nav = document.getElementById('mainNav');
    var progress = document.getElementById('scrollProgress');

    if (nav) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 40) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
            if (progress) {
                var scroll = window.scrollY;
                var docHeight = document.documentElement.scrollHeight - window.innerHeight;
                if (docHeight > 0) {
                    progress.style.width = (scroll / docHeight) * 100 + '%';
                }
            }
        });
    }

    /* ---- Back to Top ---- */
    var backBtn = document.getElementById('backToTop');
    if (backBtn) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 400) {
                backBtn.classList.add('visible');
            } else {
                backBtn.classList.remove('visible');
            }
        });
        backBtn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    /* ---- Counter Animation ---- */
    var counters = document.querySelectorAll('.stat-num');
    if (counters.length > 0) {
        var obs = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    var target = parseInt(entry.target.getAttribute('data-target'));
                    animateCounter(entry.target, target);
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        counters.forEach(function (c) { obs.observe(c); });
    }

    function animateCounter(el, target) {
        var cur = 0;
        var step = Math.ceil(target / 60);
        var id = setInterval(function () {
            cur += step;
            if (cur >= target) { cur = target; clearInterval(id); }
            el.textContent = cur.toLocaleString('id-ID');
        }, 25);
    }

    /* ---- Vanilla Tilt ---- */
    if (typeof VanillaTilt !== 'undefined') {
        VanillaTilt.init(document.querySelectorAll('.prod-card'), {
            max: 4,
            speed: 400,
            glare: true,
            'max-glare': 0.1,
            scale: 1.01
        });
    }

    /* ---- Quantity Controls ---- */
    document.addEventListener('click', function (e) {
        var plus = e.target.closest('.qty-btn.plus');
        var minus = e.target.closest('.qty-btn.minus');
        if (plus) {
            var inp = plus.parentElement.querySelector('.qty-input');
            if (inp) {
                var v = parseInt(inp.value) || 0;
                var max = parseInt(inp.getAttribute('max')) || 999;
                if (v < max) { inp.value = v + 1; triggerChange(inp); }
            }
        }
        if (minus) {
            var inp2 = minus.parentElement.querySelector('.qty-input');
            if (inp2) {
                var v2 = parseInt(inp2.value) || 0;
                if (v2 > 1) { inp2.value = v2 - 1; triggerChange(inp2); }
            }
        }
    });

    function triggerChange(el) {
        var ev = new Event('change', { bubbles: true });
        el.dispatchEvent(ev);
    }

    /* ---- Cart Functions ---- */
    window.tambahKeranjang = function (id) {
        var qtyInput = document.getElementById('qty_' + id);
        var qty = qtyInput ? parseInt(qtyInput.value) : 1;
        fetch('ajax_cart_add.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + id + '&qty=' + qty
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (d.success) {
                updateCartBadge();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success', title: 'Berhasil!',
                        text: 'Produk ditambahkan ke keranjang',
                        showConfirmButton: false, timer: 1400,
                        toast: true, position: 'top-end'
                    });
                }
            }
        });
    };

    window.tambahKeranjangDetail = function (id) {
        var qtyInput = document.getElementById('detailQty');
        var qty = qtyInput ? parseInt(qtyInput.value) : 1;
        fetch('ajax_cart_add.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + id + '&qty=' + qty
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (d.success) {
                updateCartBadge();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success', title: 'Berhasil!',
                        text: 'Produk ditambahkan ke keranjang',
                        showConfirmButton: false, timer: 1400,
                        toast: true, position: 'top-end'
                    });
                }
            }
        });
    };

    /* ---- Live Search ---- */
    var searchInput = document.getElementById('searchInput');
    var filterKategori = document.getElementById('filterKategori');
    var sortSelect = document.getElementById('sortSelect');
    var produkContainer = document.getElementById('produkContainer');

    if (searchInput) {
        var to;
        searchInput.addEventListener('input', function () {
            clearTimeout(to);
            to = setTimeout(loadProducts, 400);
        });
    }
    if (filterKategori) { filterKategori.addEventListener('change', loadProducts); }
    if (sortSelect) { sortSelect.addEventListener('change', loadProducts); }

    document.querySelectorAll('.filter-chip').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.filter-chip').forEach(function (b) { b.classList.remove('active'); });
            this.classList.add('active');
            if (filterKategori) { filterKategori.value = this.getAttribute('data-filter'); }
            loadProducts();
        });
    });

    function loadProducts() {
        if (!produkContainer) return;
        var kw = searchInput ? searchInput.value : '';
        var kat = filterKategori ? filterKategori.value : 'semua';
        var s = sortSelect ? sortSelect.value : 'terbaru';

        produkContainer.innerHTML = '<div class="col-12"><div class="loading-spinner"><div class="spinner"></div><span>Memuat produk...</span></div></div>';

        fetch('ajax_produk.php?keyword=' + encodeURIComponent(kw) + '&kategori=' + encodeURIComponent(kat) + '&sort=' + encodeURIComponent(s))
        .then(function (r) { return r.text(); })
        .then(function (html) {
            produkContainer.innerHTML = html;
            if (typeof VanillaTilt !== 'undefined') {
                VanillaTilt.init(produkContainer.querySelectorAll('.prod-card'), {
                    max: 4, speed: 400, glare: true, 'max-glare': 0.1, scale: 1.01
                });
            }
            if (window.AOS) { AOS.refresh(); }
        })
        .catch(function () {
            produkContainer.innerHTML = '<div class="col-12 text-center py-5"><p class="text-muted">Gagal memuat produk.</p></div>';
        });
    }

    /* ---- Update Cart Badge ---- */
    window.updateCartBadge = function () {
        fetch('ajax_cart_count.php')
        .then(function (r) { return r.json(); })
        .then(function (d) {
            var badge = document.getElementById('cartBadge');
            if (badge) {
                badge.textContent = d.count;
                badge.style.display = d.count > 0 ? 'flex' : 'none';
            }
        });
    };

    /* ---- Cart Qty ---- */
    document.querySelectorAll('.cart-qty input').forEach(function (inp) {
        inp.addEventListener('change', function () {
            var id = this.getAttribute('data-id');
            var q = parseInt(this.value) || 1;
            updateCart(id, q);
        });
    });

    document.addEventListener('click', function (e) {
        var p = e.target.closest('.cart-qty button.plus');
        var m = e.target.closest('.cart-qty button.minus');
        if (p) {
            var inp = p.parentElement.querySelector('input');
            if (inp) {
                var v = parseInt(inp.value) || 1;
                inp.value = v + 1;
                updateCart(inp.getAttribute('data-id'), v + 1);
            }
        }
        if (m) {
            var inp2 = m.parentElement.querySelector('input');
            if (inp2) {
                var v2 = parseInt(inp2.value) || 1;
                if (v2 > 1) {
                    inp2.value = v2 - 1;
                    updateCart(inp2.getAttribute('data-id'), v2 - 1);
                }
            }
        }
    });

    function updateCart(id, qty) {
        fetch('ajax_cart_update.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + id + '&qty=' + qty
        })
        .then(function (r) { return r.json(); })
        .then(function (d) { if (d.success) { location.reload(); } });
    }

    /* ---- Remove Cart ---- */
    document.querySelectorAll('.cart-del').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = this.getAttribute('data-id');
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Hapus Produk?',
                    text: 'Produk akan dihapus dari keranjang',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then(function (r) {
                    if (r.isConfirmed) {
                        fetch('ajax_cart_remove.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: 'id=' + id
                        })
                        .then(function (r2) { return r2.json(); })
                        .then(function (d2) { if (d2.success) { location.reload(); } });
                    }
                });
            } else {
                if (confirm('Hapus produk dari keranjang?')) {
                    fetch('ajax_cart_remove.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'id=' + id
                    })
                    .then(function (r) { return r.json(); })
                    .then(function (d) { if (d.success) { location.reload(); } });
                }
            }
        });
    });

    /* ---- Checkout ---- */
    var checkoutBtn = document.getElementById('checkoutBtn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function (e) {
            e.preventDefault();
            var nama = document.getElementById('namaPemesan');
            var fak = document.getElementById('fakultasPemesan');
            var prodi = document.getElementById('prodiPemesan');
            var n = nama ? nama.value.trim() : '';
            var f = fak ? fak.value.trim() : '';
            var p = prodi ? prodi.value.trim() : '';
            if (!n) {
                Swal.fire({ icon: 'warning', title: 'Lengkapi Data', text: 'Silakan isi nama pemesan' });
                return;
            }
            window.location.href = 'checkout.php?nama=' + encodeURIComponent(n) + '&fakultas=' + encodeURIComponent(f) + '&prodi=' + encodeURIComponent(p);
        });
    }

    /* ---- Admin Sidebar Toggle ---- */
    var toggle = document.getElementById('sidebarToggle');
    var sidebar = document.getElementById('adminSidebar');
    if (toggle && sidebar) {
        toggle.addEventListener('click', function () { sidebar.classList.toggle('show'); });
        document.addEventListener('click', function (e) {
            if (window.innerWidth <= 991) {
                if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
    }

    /* ---- Admin Preview Image ---- */
    var foto = document.getElementById('foto');
    var preview = document.getElementById('previewImg');
    if (foto && preview) {
        foto.addEventListener('change', function () {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    /* ---- Scroll to ---- */
    document.querySelectorAll('.scroll-to').forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            var target = document.querySelector(this.getAttribute('href'));
            if (target) { target.scrollIntoView({ behavior: 'smooth' }); }
        });
    });
});
