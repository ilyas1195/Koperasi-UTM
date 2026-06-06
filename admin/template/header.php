<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/database.php';

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - KOPMA UTM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<div class="admin-wrapper">
    <!-- Sidebar -->
    <div class="admin-sidebar" id="adminSidebar">
        <div class="admin-sidebar-header">
            <h5>KOPMA UTM</h5>
            <small>Panel Admin</small>
        </div>
        <div class="admin-sidebar-menu">
            <a href="index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="produk.php" class="<?= $current_page == 'produk.php' ? 'active' : '' ?>">
                <i class="fas fa-box"></i> Kelola Produk
            </a>
            <a href="kategori.php" class="<?= $current_page == 'kategori.php' ? 'active' : '' ?>">
                <i class="fas fa-tags"></i> Kelola Kategori
            </a>
        </div>
        <div class="admin-sidebar-footer">
            <a href="../index.php" target="_blank">
                <i class="fas fa-external-link-alt"></i> Lihat Website
            </a>
            <a href="logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="admin-content">
        <div class="admin-header">
            <div class="d-flex align-items-center gap-3">
                <button class="btn d-lg-none sidebar-toggle" id="sidebarToggle" style="border: 1px solid rgba(15,81,50,0.1); border-radius: 8px; padding: 8px 12px;">
                    <i class="fas fa-bars"></i>
                </button>
                <h4><?= $page_title ?? 'Dashboard' ?></h4>
            </div>
            <div>
                <span style="font-size: 14px; color: var(--text-light);">
                    <i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars($_SESSION['admin']['username']) ?>
                </span>
            </div>
        </div>
        <div class="admin-body">
