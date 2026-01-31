<?php 
include 'config/db.php'; 
session_start();

// Query mengambil hasil desain dari proyek yang sudah selesai
// Bergantung pada tabel digital_assets yang menampung file_path hasil kerja admin
$query = "SELECT digital_assets.file_path, services.nama_layanan, users.nama as nama_klien, orders.order_date 
          FROM digital_assets 
          JOIN orders ON digital_assets.id_order = orders.id_order 
          JOIN services ON orders.id_service = services.id_service 
          JOIN users ON orders.id_client = users.id_user
          WHERE orders.status = 'selesai' 
          ORDER BY digital_assets.id_asset DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Portfolio | VISANDI Studio</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&family=Sora:wght@700&display=swap" rel="stylesheet">
    
    <style>
        :root { 
            --vs-dark: #0f172a; --vs-card: #1e293b; --vs-blue: #2563eb; 
        }
        
        body { 
            background: var(--vs-dark); 
            color: #fff; 
            font-family: 'Plus Jakarta Sans', sans-serif; 
        }

        .navbar { 
            background: rgba(15, 23, 42, 0.9); 
            backdrop-filter: blur(20px); 
            border-bottom: 1px solid rgba(255,255,255,0.05); 
        }

        .portfolio-card { 
            background: var(--vs-card); 
            border-radius: 24px; 
            border: 1px solid rgba(255,255,255,0.05); 
            overflow: hidden; 
            transition: 0.4s;
            height: 100%;
        }

        .portfolio-card:hover { 
            transform: translateY(-12px); 
            border-color: var(--vs-blue); 
            box-shadow: 0 20px 40px rgba(37, 99, 235, 0.2);
        }

        .img-container {
            height: 280px;
            overflow: hidden;
            position: relative;
        }

        .img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.5s;
        }

        .portfolio-card:hover img {
            transform: scale(1.1);
        }

        .category-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            background: var(--vs-blue);
            padding: 5px 15px;
            border-radius: 100px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            z-index: 2;
        }

        .client-info {
            padding: 25px;
        }

        .client-info h5 {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg py-3 sticky-top">
    <div class="container d-flex justify-content-between">
        <a class="navbar-brand fw-bold fs-3 text-white" href="index.php">VISANDI<span class="text-info">.</span></a>
        <a href="index.php" class="btn btn-outline-light rounded-pill px-4 fw-bold shadow-sm">
            <i class="fa-solid fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
</nav>

<div class="container py-5 mt-4">
    <div class="text-center mb-5">
        <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3 py-2 mb-3">✨ SELECTED WORKS</span>
        <h1 class="fw-800 display-5" style="font-family: 'Sora', sans-serif;">Portfolio Visual Kami</h1>
        <p class="text-white-50 mx-auto" style="max-width: 600px;">Kumpulan proyek desain digital yang telah membantu brand klien bertumbuh dan tampil stand-out.</p>
    </div>

    <div class="row g-4">
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="col-lg-4 col-md-6">
                <div class="portfolio-card shadow-lg">
                    <div class="img-container">
                        <span class="category-badge shadow-sm"><?= htmlspecialchars($row['nama_layanan']) ?></span>
                        <img src="uploads/<?= $row['file_path'] ?>" alt="Portfolio VISANDI">
                    </div>
                    <div class="client-info">
                        <small class="text-info fw-bold">Project for:</small>
                        <h5><?= htmlspecialchars($row['nama_klien']) ?></h5>
                        <small class="text-white-50 d-block mt-2" style="font-size: 11px;">
                            <i class="fa-regular fa-calendar me-1"></i> <?= date('d F Y', strtotime($row['order_date'])) ?>
                        </small>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <div class="opacity-25 mb-3"><i class="fa-solid fa-layer-group fs-1"></i></div>
                <h5 class="text-white-50 italic">Belum ada karya yang dipublikasikan saat ini.</h5>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>