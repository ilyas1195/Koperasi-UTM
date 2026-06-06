<?php
require_once 'config/database.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM produk WHERE id_produk = ?");
    $stmt->execute([$id]);
    $produk = $stmt->fetch();
    if ($produk) {
        echo json_encode($produk);
        exit;
    }
}
echo json_encode(null);
