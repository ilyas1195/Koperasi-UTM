<?php
require_once 'config/database.php';
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$qty = isset($_POST['qty']) ? intval($_POST['qty']) : 1;

if ($id > 0 && $qty > 0) {
    $stmt = $pdo->prepare("SELECT stok FROM produk WHERE id_produk = ?");
    $stmt->execute([$id]);
    $produk = $stmt->fetch();

    if ($produk) {
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] += $qty;
            if ($_SESSION['cart'][$id] > $produk['stok']) {
                $_SESSION['cart'][$id] = $produk['stok'];
            }
        } else {
            $_SESSION['cart'][$id] = min($qty, $produk['stok']);
        }
        echo json_encode(['success' => true, 'count' => array_sum($_SESSION['cart'])]);
        exit;
    }
}

echo json_encode(['success' => false]);
