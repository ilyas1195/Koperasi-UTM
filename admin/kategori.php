<?php
$page_title = 'Kelola Kategori';
require_once 'template/header.php';

// Handle Delete
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM produk WHERE kategori_id = ?");
    $stmt->execute([$id]);
    if ($stmt->fetchColumn() > 0) {
        echo "<script>Swal.fire({icon:'error',title:'Gagal',text:'Kategori masih memiliki produk!',showConfirmButton:false,timer:2000}).then(()=>{window.location='kategori.php'})</script>";
        exit;
    }
    $pdo->prepare("DELETE FROM kategori WHERE id_kategori = ?")->execute([$id]);
    echo "<script>Swal.fire({icon:'success',title:'Berhasil',text:'Kategori berhasil dihapus',showConfirmButton:false,timer:1500}).then(()=>{window.location='kategori.php'})</script>";
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_kategori']);
    $parent_id = !empty($_POST['parent_id']) ? intval($_POST['parent_id']) : null;
    $edit_id = intval($_POST['edit_id'] ?? 0);

    if ($edit_id > 0) {
        $stmt = $pdo->prepare("UPDATE kategori SET nama_kategori=?, parent_id=? WHERE id_kategori=?");
        $stmt->execute([$nama, $parent_id, $edit_id]);
        echo "<script>Swal.fire({icon:'success',title:'Berhasil',text:'Kategori berhasil diperbarui',showConfirmButton:false,timer:1500}).then(()=>{window.location='kategori.php'})</script>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO kategori (nama_kategori, parent_id) VALUES (?,?)");
        $stmt->execute([$nama, $parent_id]);
        echo "<script>Swal.fire({icon:'success',title:'Berhasil',text:'Kategori berhasil ditambahkan',showConfirmButton:false,timer:1500}).then(()=>{window.location='kategori.php'})</script>";
    }
    exit;
}

$kategori_list = $pdo->query("SELECT k.*, p.nama_kategori as parent_nama FROM kategori k LEFT JOIN kategori p ON k.parent_id = p.id_kategori ORDER BY k.parent_id IS NULL DESC, k.id_kategori")->fetchAll();
$parent_kategori = $pdo->query("SELECT * FROM kategori WHERE parent_id IS NULL")->fetchAll();
?>

<div class="row mb-4">
    <div class="col-md-6">
        <h5 style="font-weight: 700; color: var(--primary-dark); margin: 0;">Daftar Kategori</h5>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <button class="btn btn-primary-custom" onclick="showModal(null)" style="padding: 10px 24px; font-size: 14px;">
            <i class="fas fa-plus"></i> Tambah Kategori
        </button>
    </div>
</div>

<div class="admin-table-wrapper">
    <div class="table-responsive">
        <table class="table admin-table">
            <thead>
                <tr>
                    <th>Nama Kategori</th>
                    <th>Kategori Induk</th>
                    <th>Tipe</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($kategori_list)): ?>
                <tr>
                    <td colspan="4" class="text-center py-5" style="color: var(--text-light);">
                        <i class="fas fa-tags" style="font-size: 32px; display: block; margin-bottom: 8px; opacity: 0.3;"></i>
                        Belum ada kategori
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($kategori_list as $k): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($k['nama_kategori']) ?></strong></td>
                    <td><?= $k['parent_nama'] ? htmlspecialchars($k['parent_nama']) : '<span style="color: var(--text-light);">-</span>' ?></td>
                    <td>
                        <?php if ($k['parent_id'] === null): ?>
                        <span style="background: rgba(15,81,50,0.06); padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; color: var(--primary);">Utama</span>
                        <?php else: ?>
                        <span style="background: rgba(212,175,55,0.1); padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; color: var(--premium);">Subkategori</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <button class="btn-admin-edit" onclick="showModal(<?= $k['id_kategori'] ?>)">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn-admin-delete" onclick="hapusKategori(<?= $k['id_kategori'] ?>)">
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
<div class="modal fade" id="kategoriModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content modal-content-custom">
            <div class="modal-header-custom">
                <h5 class="modal-title" id="modalTitle">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="kategoriForm">
                <input type="hidden" name="edit_id" id="editId" value="0">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 14px;">Nama Kategori</label>
                        <input type="text" name="nama_kategori" id="inputNama" class="form-control" required style="border-radius: 10px; border: 1px solid rgba(15,81,50,0.1); padding: 10px 14px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 14px;">Kategori Induk (opsional)</label>
                        <select name="parent_id" id="inputParent" class="form-control" style="border-radius: 10px; border: 1px solid rgba(15,81,50,0.1); padding: 10px 14px;">
                            <option value="">Tidak ada (Kategori Utama)</option>
                            <?php foreach ($parent_kategori as $pk): ?>
                            <option value="<?= $pk['id_kategori'] ?>"><?= htmlspecialchars($pk['nama_kategori']) ?></option>
                            <?php endforeach; ?>
                        </select>
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
    const modal = new bootstrap.Modal(document.getElementById('kategoriModal'));
    document.getElementById('editId').value = 0;
    document.getElementById('modalTitle').textContent = 'Tambah Kategori';
    document.getElementById('inputNama').value = '';
    document.getElementById('inputParent').value = '';

    if (id) {
        fetch('../ajax_kategori_detail.php?id=' + id)
        .then(res => res.json())
        .then(data => {
            document.getElementById('editId').value = data.id_kategori;
            document.getElementById('modalTitle').textContent = 'Edit Kategori';
            document.getElementById('inputNama').value = data.nama_kategori;
            document.getElementById('inputParent').value = data.parent_id || '';
        });
    }

    modal.show();
}

function hapusKategori(id) {
    Swal.fire({
        title: 'Hapus Kategori?',
        text: 'Kategori akan dihapus permanen',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = 'kategori.php?hapus=' + id;
        }
    });
}
</script>

<?php require_once 'template/footer.php'; ?>
