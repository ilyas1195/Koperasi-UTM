<?php
session_start();

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id > 0 && isset($_SESSION['cart'][$id])) {
    unset($_SESSION['cart'][$id]);
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => false]);
