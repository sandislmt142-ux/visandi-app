<?php 
// 1. KONEKSI KE DATABASE
include 'config/db.php'; 
session_start();

// 2. QUERY PALING PASTI: Menggunakan id_review DESC
// Ini menjamin ulasan yang baru saja masuk (ID tertinggi) muncul di paling atas, 
// meskipun ada masalah pada format tanggal created_at.
$query_ulasan_lengkap = mysqli_query($conn, "SELECT * FROM reviews ORDER BY id_review DESC");

if (!$query_ulasan_lengkap) {
    die("Query Bermasalah: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Ulasan Klien | VISANDI</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root { --vs-blue: #2563eb; --vs-dark: #0f172a; --vs-card: #1e293b; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--vs-dark); color: #fff; text-align: left; }
        .navbar { background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(255,255,255,0.05); }
        .review-card { 
            background: var(--vs-card); 
            border: 1px solid rgba(255,255,255,0.05); 
            border-radius: 24px; 
            padding: 25px; 
            transition: 0.3s ease; 
            height: 100%; 
        }
        .review-card:hover { transform: translateY(-5px); border-color: var(--vs-blue); }
        .star-rating { color: #fbbf24; font-size: 0.9rem; }
        .admin-reply { 
            background: rgba(37, 99, 235, 0.1); 
            border-left: 4px solid var(--vs-blue); 
            border-radius: 0 15px 15px 0; 
            margin-bottom: 15px;
        }
        .user-avatar { 
            width: 42px; 
            height: 42px; 
            background: linear-gradient(135deg, var(--vs-blue), #4f46e5); 
            color: white; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-weight: 800; 
            border-radius: 50%; 
        }
        .back-link { color: var(--vs-blue); text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg py-3 sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3 text-white" href="index.php">VISANDI<span class="text-info">.</span></a>
        <a href="index.php" class="back-link"><i class="fa-solid fa-arrow-left me-2"></i> Kembali</a>
    </div>
</nav>

<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-800 display-6">Testimonial Klien ⭐</h2>
        <p class="text-white-50">Apa yang mereka katakan tentang hasil kerja kami.</p>
    </div>

    <div class="row g-4">
        <?php if(mysqli_num_rows($query_ulasan_lengkap) > 0): ?>
            <?php while($rev = mysqli_fetch_assoc($query_ulasan_lengkap)): ?>
            <div class="col-md-6 col-lg-4">
                <div class="review-card shadow-lg d-flex flex-column">
                    <div class="star-rating mb-3">
                        <?php 
                        $rating = (int)($rev['rating'] ?? 5);
                        for($i=1; $i<=5; $i++) {
                            echo ($i <= $rating) ? '<i class="bi bi-star-fill"></i> ' : '<i class="bi bi-star"></i> ';
                        }
                        ?>
                    </div>
                    
                    <p class="small opacity-75 mb-4">"<?= htmlspecialchars($rev['komentar']) ?>"</p>

                    <?php if (!empty($rev['balasan_admin']) && $rev['balasan_admin'] !== 'NULL' && $rev['balasan_admin'] !== ''): ?>
                        <div class="p-3 admin-reply shadow-sm">
                            <small class="text-info fw-bold d-block mb-1">
                                <i class="bi bi-patch-check-fill me-1"></i> Admin VISANDI:
                            </small>
                            <p class="small m-0 text-white-75 italic">
                                "<?= htmlspecialchars($rev['balasan_admin']) ?>"
                            </p>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex align-items-center mt-auto pt-3 border-top border-white border-opacity-10">
                        <div class="user-avatar me-3">
                            <?= strtoupper(substr($rev['nama_klien'], 0, 1)) ?>
                        </div>
                        <div>
                            <h6 class="fw-bold m-0 small"><?= htmlspecialchars($rev['nama_klien']) ?></h6>
                            <small class="text-white-50" style="font-size: 11px;">
                                <?php 
                                if(!empty($rev['created_at']) && $rev['created_at'] != '0000-00-00 00:00:00') {
                                    echo date('d M Y', strtotime($rev['created_at']));
                                } else {
                                    echo "Baru saja";
                                }
                                ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center opacity-50 py-5">
                <p>Belum ada ulasan publik.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>