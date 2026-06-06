<?php
$page_title = 'Kelola Produk';
require_once 'template/header.php';

// Handle Delete
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $stmt = $pdo->prepare("SELECT gambar FROM produk WHERE id_produk = ?");
    $stmt->execute([$id]);
    $produk = $stmt->fetch();
    if ($produk && $produk['gambar'] && file_exists('../assets/uploads/' . $produk['gambar'])) {
        unlink('../assets/uploads/' . $produk['gambar']);
    }
    $pdo->prepare("DELETE FROM produk WHERE id_produk = ?")->execute([$id]);
    echo "<script>Swal.fire({icon:'success',title:'Berhasil',text:'Produk berhasil dihapus',showConfirmButton:false,timer:1500}).then(()=>{window.location='produk.php'})</script>";
    exit;
}

// Handle Add/Edit
$edit_mode = false;
$edit_data = null;

if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $pdo->prepare("SELECT * FROM produk WHERE id_produk = ?");
    $stmt->execute([$id]);
    $edit_data = $stmt->fetch();
    if ($edit_data) $edit_mode = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_produk']);
    $harga = intval($_POST['harga']);
    $stok = intval($_POST['stok']);
    $kategori_id = intval($_POST['kategori_id']);
    $deskripsi = trim($_POST['deskripsi']);
    $edit_id = intval($_POST['edit_id'] ?? 0);

    $gambar = '';

    // Handle image upload
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $gambar = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], '../assets/uploads/' . $gambar);
    }

    if ($edit_id > 0) {
        // Update
        if ($gambar) {
            // Delete old image
            $stmt = $pdo->prepare("SELECT gambar FROM produk WHERE id_produk = ?");
            $stmt->execute([$edit_id]);
            $old = $stmt->fetch();
            if ($old['gambar'] && file_exists('../assets/uploads/' . $old['gambar'])) {
                unlink('../assets/uploads/' . $old['gambar']);
            }
            $stmt = $pdo->prepare("UPDATE produk SET nama_produk=?, harga=?, stok=?, deskripsi=?, gambar=?, kategori_id=? WHERE id_produk=?");
            $stmt->execute([$nama, $harga, $stok, $deskripsi, $gambar, $kategori_id, $edit_id]);
        } else {
            $stmt = $pdo->prepare("UPDATE produk SET nama_produk=?, harga=?, stok=?, deskripsi=?, kategori_id=? WHERE id_produk=?");
            $stmt->execute([$nama, $harga, $stok, $deskripsi, $kategori_id, $edit_id]);
        }
        echo "<script>Swal.fire({icon:'success',title:'Berhasil',text:'Produk berhasil diperbarui',showConfirmButton:false,timer:1500}).then(()=>{window.location='produk.php'})</script>";
    } else {
        // Insert
        $stmt = $pdo->prepare("INSERT INTO produk (nama_produk, harga, stok, deskripsi, gambar, kategori_id) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$nama, $harga, $stok, $deskripsi, $gambar, $kategori_id]);
        echo "<script>Swal.fire({icon:'success',title:'Berhasil',text:'Produk berhasil ditambahkan',showConfirmButton:false,timer:1500}).then(()=>{window.location='produk.php'})</script>";
    }
    exit;
}

// Get all products
$keyword = $_GET['cari'] ?? '';
if ($keyword) {
    $stmt = $pdo->prepare("SELECT p.*, k.nama_kategori FROM produk p JOIN kategori k ON p.kategori_id = k.id_kategori WHERE p.nama_produk LIKE ? ORDER BY p.created_at DESC");
    $stmt->execute(["%$keyword%"]);
} else {
    $stmt = $pdo->query("SELECT p.*, k.nama_kategori FROM produk p JOIN kategori k ON p.kategori_id = k.id_kategori ORDER BY p.created_at DESC");
}
$produk_list = $stmt->fetchAll();

// Get categories for select
$kategori_list = $pdo->query("SELECT * FROM kategori ORDER BY parent_id IS NULL DESC, parent_id, id_kategori")->fetchAll();
?>

<div class="row mb-4">
    <div class="col-md-6">
        <form method="GET" class="d-flex gap-2">
            <div class="position-relative" style="flex: 1;">
                <i class="fas fa-search" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-light); font-size: 14px;"></i>
                <input type="text" name="cari" class="form-control" placeholder="Cari produk..." value="<?= htmlspecialchars($keyword) ?>" style="padding-left: 38px; border-radius: 10px; border: 1px solid rgba(15,81,50,0.1); font-size: 14px;">
            </div>
            <button type="submit" class="btn btn-primary-custom" style="padding: 8px 20px; font-size: 13px;">Cari</button>
            <?php if ($keyword): ?>
            <a href="produk.php" class="btn btn-outline-custom" style="padding: 8px 20px; font-size: 13px;">Reset</a>
            <?php endif; ?>
        </form>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <button class="btn btn-primary-custom" onclick="showModal(null)" style="padding: 10px 24px; font-size: 14px;">
            <i class="fas fa-plus"></i> Tambah Produk
        </button>
    </div>
</div>

<div class="admin-table-wrapper">
    <div class="table-responsive">
        <table class="table admin-table">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($produk_list)): ?>
                <tr>
                    <td colspan="6" class="text-center py-5" style="color: var(--text-light);">
                        <i class="fas fa-box-open" style="font-size: 32px; display: block; margin-bottom: 8px; opacity: 0.3;"></i>
                        Belum ada produk
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($produk_list as $p): ?>
                <tr>
                    <td>
                        <?php if ($p['gambar'] && file_exists('../assets/uploads/' . $p['gambar'])): ?>
                        <img src="../assets/uploads/<?= htmlspecialchars($p['gambar']) ?>" class="product-thumb" alt="">
                        <?php else: ?>
                        <div class="product-thumb" style="background: var(--surface); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-box" style="color: var(--primary); opacity: 0.3; font-size: 18px;"></i>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= htmlspecialchars($p['nama_produk']) ?></strong></td>
                    <td><span style="background: rgba(15,81,50,0.06); padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; color: var(--primary);"><?= htmlspecialchars($p['nama_kategori']) ?></span></td>
                    <td style="font-weight: 700; color: var(--primary);"><?= formatRupiah($p['harga']) ?></td>
                    <td>
                        <span style="font-weight: 600; color: <?= $p['stok'] > 0 ? 'var(--secondary)' : '#dc3545' ?>;">
                            <?= $p['stok'] ?>
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <button class="btn-admin-edit" onclick="showModal(<?= $p['id_produk'] ?>)">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn-admin-delete" onclick="hapusProduk(<?= $p['id_produk'] ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="produkModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-content-custom">
            <div class="modal-header-custom">
                <h5 class="modal-title" id="modalTitle">Tambah Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data" id="produkForm">
                <input type="hidden" name="edit_id" id="editId" value="0">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size: 14px;">Nama Produk</label>
                            <input type="text" name="nama_produk" id="inputNama" class="form-control" required style="border-radius: 10px; border: 1px solid rgba(15,81,50,0.1); padding: 10px 14px;">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold" style="font-size: 14px;">Harga (Rp)</label>
                            <input type="number" name="harga" id="inputHarga" class="form-control" required min="0" style="border-radius: 10px; border: 1px solid rgba(15,81,50,0.1); padding: 10px 14px;">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold" style="font-size: 14px;">Stok</label>
                            <input type="number" name="stok" id="inputStok" class="form-control" required min="0" style="border-radius: 10px; border: 1px solid rgba(15,81,50,0.1); padding: 10px 14px;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size: 14px;">Kategori</label>
                            <select name="kategori_id" id="inputKategori" class="form-control" required style="border-radius: 10px; border: 1px solid rgba(15,81,50,0.1); padding: 10px 14px;">
                                <option value="">Pilih Kategori</option>
                                <?php foreach ($kategori_list as $k): ?>
                                <option value="<?= $k['id_kategori'] ?>"><?= htmlspecialchars($k['nama_kategori']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size: 14px;">Foto Produk</label>
                            <input type="file" name="foto" id="foto" class="form-control" accept="image/*" style="border-radius: 10px; border: 1px solid rgba(15,81,50,0.1); padding: 8px 12px;">
                            <img id="previewImg" src="#" alt="Preview" style="display: none; width: 80px; height: 80px; object-fit: cover; border-radius: 8px; margin-top: 8px;">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size: 14px;">Deskripsi</label>
                            <textarea name="deskripsi" id="inputDeskripsi" class="form-control" rows="4" style="border-radius: 10px; border: 1px solid rgba(15,81,50,0.1); padding: 10px 14px;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid rgba(15,81,50,0.06); padding: 16px 24px;">
                    <button type="button" class="btn btn-outline-custom" data-bs-dismiss="modal" style="padding: 10px 24px; font-size: 14px;">Batal</button>
                    <button type="submit" class="btn btn-primary-custom" style="padding: 10px 24px; font-size: 14px;">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showModal(id) {
    const modal = new bootstrap.Modal(document.getElementById('produkModal'));
    document.getElementById('editId').value = 0;
    document.getElementById('modalTitle').textContent = 'Tambah Produk';
    document.getElementById('inputNama').value = '';
    document.getElementById('inputHarga').value = '';
    document.getElementById('inputStok').value = '';
    document.getElementById('inputKategori').value = '';
    document.getElementById('inputDeskripsi').value = '';
    document.getElementById('previewImg').style.display = 'none';

    if (id) {
        fetch('../ajax_produk_detail.php?id=' + id)
        .then(res => res.json())
        .then(data => {
            document.getElementById('editId').value = data.id_produk;
            document.getElementById('modalTitle').textContent = 'Edit Produk';
            document.getElementById('inputNama').value = data.nama_produk;
            document.getElementById('inputHarga').value = data.harga;
            document.getElementById('inputStok').value = data.stok;
            document.getElementById('inputKategori').value = data.kategori_id;
            document.getElementById('inputDeskripsi').value = data.deskripsi;
            if (data.gambar) {
                document.getElementById('previewImg').src = '../assets/uploads/' + data.gambar;
                document.getElementById('previewImg').style.display = 'block';
            }
        });
    }

    modal.show();
}

function hapusProduk(id) {
    Swal.fire({
        title: 'Hapus Produk?',
        text: 'Data produk akan dihapus permanen',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = 'produk.php?hapus=' + id;
        }
    });
}
</script>

<?php require_once 'template/footer.php'; ?>
