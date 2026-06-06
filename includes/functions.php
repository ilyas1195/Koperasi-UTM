<?php
function getKategori($pdo, $parent_id = null) {
    if ($parent_id === null) {
        $stmt = $pdo->query("SELECT * FROM kategori WHERE parent_id IS NULL ORDER BY id_kategori");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM kategori WHERE parent_id = ? ORDER BY nama_kategori");
        $stmt->execute([$parent_id]);
    }
    return $stmt->fetchAll();
}

function getAllKategori($pdo) {
    $stmt = $pdo->query("SELECT k.*, p.nama_kategori as parent_nama FROM kategori k LEFT JOIN kategori p ON k.parent_id = p.id_kategori ORDER BY k.id_kategori");
    return $stmt->fetchAll();
}

function getProdukTerbaru($pdo, $limit = 8) {
    $stmt = $pdo->prepare("SELECT p.*, k.nama_kategori FROM produk p JOIN kategori k ON p.kategori_id = k.id_kategori ORDER BY p.created_at DESC LIMIT ?");
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getProdukByKategori($pdo, $kategori_id) {
    $stmt = $pdo->prepare("SELECT p.*, k.nama_kategori FROM produk p JOIN kategori k ON p.kategori_id = k.id_kategori WHERE p.kategori_id = ? OR k.parent_id = ? ORDER BY p.created_at DESC");
    $stmt->execute([$kategori_id, $kategori_id]);
    return $stmt->fetchAll();
}

function getProdukDetail($pdo, $id_produk) {
    $stmt = $pdo->prepare("SELECT p.*, k.nama_kategori, k.parent_id as kategori_parent_id, pk.nama_kategori as parent_kategori FROM produk p JOIN kategori k ON p.kategori_id = k.id_kategori LEFT JOIN kategori pk ON k.parent_id = pk.id_kategori WHERE p.id_produk = ?");
    $stmt->execute([$id_produk]);
    return $stmt->fetch();
}

function searchProduk($pdo, $keyword, $kategori = null, $sort = 'terbaru') {
    $sql = "SELECT p.*, k.nama_kategori FROM produk p JOIN kategori k ON p.kategori_id = k.id_kategori WHERE (p.nama_produk LIKE ? OR p.deskripsi LIKE ?)";
    $params = ["%$keyword%", "%$keyword%"];

    if ($kategori && $kategori !== 'semua') {
        if (in_array($kategori, ['makanan', 'minuman'])) {
            $stmtK = $pdo->prepare("SELECT id_kategori FROM kategori WHERE LOWER(nama_kategori) = ?");
            $stmtK->execute([$kategori]);
            $kat = $stmtK->fetch();
            if ($kat) {
                $sql .= " AND p.kategori_id = ?";
                $params[] = $kat['id_kategori'];
            }
        } else {
            $stmtK = $pdo->prepare("SELECT id_kategori FROM kategori WHERE LOWER(nama_kategori) = ? AND parent_id IS NULL");
            $stmtK->execute([$kategori]);
            $kat = $stmtK->fetch();
            if ($kat) {
                $sql .= " AND (p.kategori_id = ? OR k.parent_id = ?)";
                $params[] = $kat['id_kategori'];
                $params[] = $kat['id_kategori'];
            }
        }
    }

    switch ($sort) {
        case 'nama-asc': $sql .= " ORDER BY p.nama_produk ASC"; break;
        case 'nama-desc': $sql .= " ORDER BY p.nama_produk DESC"; break;
        case 'harga-termurah': $sql .= " ORDER BY p.harga ASC"; break;
        case 'harga-tertinggi': $sql .= " ORDER BY p.harga DESC"; break;
        default: $sql .= " ORDER BY p.created_at DESC";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function hitungStatistik($pdo) {
    $data = [];
    $data['total_produk'] = $pdo->query("SELECT COUNT(*) FROM produk")->fetchColumn();
    $data['total_kategori'] = $pdo->query("SELECT COUNT(*) FROM kategori")->fetchColumn();
    $stmtRetail = $pdo->prepare("SELECT COUNT(*) FROM produk p JOIN kategori k ON p.kategori_id = k.id_kategori WHERE (k.parent_id = ? OR k.id_kategori = ?)");
    $stmtRetail->execute([1, 1]);
    $data['produk_retail'] = $stmtRetail->fetchColumn();
    $stmtKonsinyasi = $pdo->prepare("SELECT COUNT(*) FROM produk WHERE kategori_id = ?");
    $stmtKonsinyasi->execute([2]);
    $data['produk_konsinyasi'] = $stmtKonsinyasi->fetchColumn();
    return $data;
}
