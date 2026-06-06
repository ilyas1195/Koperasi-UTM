<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Katalog Produk KOPMA UTM' ?></title>
    <meta name="description" content="Katalog Digital Koperasi Mahasiswa Universitas Trunodjoyo Madura. Menyediakan berbagai kebutuhan mahasiswa dengan pelayanan mudah, cepat, dan terpercaya.">
    <meta name="keywords" content="kopma, koperasi mahasiswa, utm, trunojoyo, katalog produk, madura">
    <meta name="author" content="KOPMA UTM">

    <meta property="og:title" content="<?= $title ?? 'Katalog Produk KOPMA UTM' ?>">
    <meta property="og:description" content="Katalog Digital Koperasi Mahasiswa Universitas Trunodjoyo Madura">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">

    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 36 36'><rect width='36' height='36' rx='8' fill='%23D4AF37'/><text x='18' y='24' text-anchor='middle' fill='%230F5132' font-size='18' font-weight='bold' font-family='Arial'>K</text></svg>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<div class="loading-screen" id="loadingScreen">
    <div class="loading-content">
        <div class="loading-logo">
            <svg width="56" height="56" viewBox="0 0 36 36" fill="none">
                <rect width="36" height="36" rx="8" fill="#D4AF37"/>
                <text x="18" y="24" text-anchor="middle" fill="#0F5132" font-size="18" font-weight="bold" font-family="Arial">K</text>
            </svg>
        </div>
        <div class="loading-brand">KOPMA UTM</div>
        <div class="loading-bar-track">
            <div class="loading-bar-fill"></div>
        </div>
    </div>
</div>

<?php include 'includes/navbar.php'; ?>

<div class="scroll-progress" id="scrollProgress"></div>

<main id="mainContent">
