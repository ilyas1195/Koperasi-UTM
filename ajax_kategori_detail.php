<?php
require_once 'config/database.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM kategori WHERE id_kategori = ?");
    $stmt->execute([$id]);
    $kategori = $stmt->fetch();
    if ($kategori) {
        echo json_encode($kategori);
        exit;
    }
}
echo json_encode(null);
