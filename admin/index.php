<?php
$page_title = 'Dashboard';
require_once 'template/header.php';

$total_produk = $pdo->query("SELECT COUNT(*) FROM produk")->fetchColumn();
$total_kategori = $pdo->query("SELECT COUNT(*) FROM kategori")->fetchColumn();

$stmtRetail = $pdo->prepare("SELECT COUNT(*) FROM produk WHERE kategori_id IN (SELECT id_kategori FROM kategori WHERE parent_id = ? OR id_kategori = ?)");
$stmtRetail->execute([1, 1]);
$produk_retail = $stmtRetail->fetchColumn();

$stmtKonsinyasi = $pdo->prepare("SELECT COUNT(*) FROM produk WHERE kategori_id = ?");
$stmtKonsinyasi->execute([2]);
$produk_konsinyasi = $stmtKonsinyasi->fetchColumn();

$stmtLainnya = $pdo->prepare("SELECT COUNT(*) FROM produk WHERE kategori_id = ?");
$stmtLainnya->execute([3]);
$produk_lainnya = $stmtLainnya->fetchColumn();

$produk_by_kategori = $pdo->query("
    SELECT k.nama_kategori, COUNT(p.id_produk) as jumlah 
    FROM kategori k 
    LEFT JOIN produk p ON k.id_kategori = p.kategori_id 
    WHERE k.parent_id IS NULL 
    GROUP BY k.id_kategori
")->fetchAll();

$recent_products = $pdo->query("SELECT p.*, k.nama_kategori FROM produk p JOIN kategori k ON p.kategori_id = k.id_kategori ORDER BY p.created_at DESC LIMIT 5")->fetchAll();
?>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="admin-stat-card">
            <div class="admin-stat-icon green"><i class="fas fa-box"></i></div>
            <div class="admin-stat-number"><?= $total_produk ?></div>
            <div class="admin-stat-label">Total Produk</div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="admin-stat-card">
            <div class="admin-stat-icon gold"><i class="fas fa-tags"></i></div>
            <div class="admin-stat-number"><?= $total_kategori ?></div>
            <div class="admin-stat-label">Total Kategori</div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="admin-stat-card">
            <div class="admin-stat-icon blue"><i class="fas fa-store"></i></div>
            <div class="admin-stat-number"><?= $produk_retail ?></div>
            <div class="admin-stat-label">Produk Retail</div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="admin-stat-card">
            <div class="admin-stat-icon red"><i class="fas fa-handshake"></i></div>
            <div class="admin-stat-number"><?= $produk_konsinyasi ?></div>
            <div class="admin-stat-label">Produk Konsinyasi</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="chart-wrapper">
            <h5 style="font-weight: 700; color: var(--primary-dark); margin-bottom: 20px;">
                <i class="fas fa-chart-bar me-2" style="color: var(--primary);"></i>Grafik Produk per Kategori
            </h5>
            <canvas id="chartProduk" height="200"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="admin-table-wrapper">
            <div style="padding: 16px 20px; border-bottom: 1px solid rgba(15,81,50,0.06);">
                <h5 style="font-weight: 700; color: var(--primary-dark); margin: 0; font-size: 16px;">
                    <i class="fas fa-clock me-2" style="color: var(--primary);"></i>Produk Terbaru
                </h5>
            </div>
            <?php if (empty($recent_products)): ?>
            <div style="padding: 40px 20px; text-align: center; color: var(--text-light); font-size: 14px;">
                Belum ada produk
            </div>
            <?php else: ?>
            <?php foreach ($recent_products as $rp): ?>
            <div style="padding: 12px 20px; border-bottom: 1px solid rgba(15,81,50,0.04); display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: var(--surface); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fas fa-box" style="color: var(--primary); font-size: 16px; opacity: 0.5;"></i>
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div style="font-weight: 600; font-size: 14px; color: var(--primary-dark); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= htmlspecialchars($rp['nama_produk']) ?></div>
                    <div style="font-size: 12px; color: var(--text-light);"><?= htmlspecialchars($rp['nama_kategori']) ?> • <?= formatRupiah($rp['harga']) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('chartProduk').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($produk_by_kategori, 'nama_kategori')) ?>,
            datasets: [{
                label: 'Jumlah Produk',
                data: <?= json_encode(array_column($produk_by_kategori, 'jumlah')) ?>,
                backgroundColor: ['#0F5132', '#D4AF37', '#198754'],
                borderRadius: 6,
                barThickness: 48
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
});
</script>

<?php require_once 'template/footer.php'; ?>
