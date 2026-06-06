<?php
require_once 'config/database.php';
session_start();

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$qty = isset($_POST['qty']) ? intval($_POST['qty']) : 0;

if ($id > 0 && isset($_SESSION['cart'][$id])) {
    if ($qty > 0) {
        $stmt = $pdo->prepare("SELECT stok FROM produk WHERE id_produk = ?");
        $stmt->execute([$id]);
        $produk = $stmt->fetch();
        $_SESSION['cart'][$id] = min($qty, $produk['stok']);
    } else {
        unset($_SESSION['cart'][$id]);
    }
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => false]);
