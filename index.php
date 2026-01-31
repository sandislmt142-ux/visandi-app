<?php 
include 'config/db.php'; 
session_start();

// Data untuk 7 Layanan Marketplace Wajib dengan Penanggung Jawab & Warna Khusus
$services = [
    ["id" => 1, "title" => "Identitas Logo & Brand", "price" => "1.500.000", "icon" => "fa-gem", "img" => "https://images.pexels.com/photos/1766664/pexels-photo-1766664.jpeg?auto=compress&cs=tinysrgb&w=800", "desc" => "Panduan brand profesional dan identitas visual yang ikonik.", "color" => "linear-gradient(135deg, #f59e0b, #d97706)", "pj" => "Sandi (Logo Expert)"],
    ["id" => 2, "title" => "Desain Media Sosial", "price" => "750.000", "icon" => "fa-hashtag", "img" => "https://images.pexels.com/photos/267350/pexels-photo-267350.jpeg?auto=compress&cs=tinysrgb&w=800", "desc" => "Konten feed dan visual kampanye yang meningkatkan interaksi.", "color" => "linear-gradient(135deg, #f43f5e, #e11d48)", "pj" => "Sarah (Social Expert)"],
    ["id" => 3, "title" => "UI/UX Website", "price" => "2.500.000", "icon" => "fa-desktop", "img" => "https://images.pexels.com/photos/1779487/pexels-photo-1779487.jpeg?auto=compress&cs=tinysrgb&w=800", "desc" => "Tata letak web responsif untuk bisnis modern masa kini.", "color" => "linear-gradient(135deg, #6366f1, #4f46e5)", "pj" => "Alex (Web Designer)"],
    ["id" => 4, "title" => "UI/UX Aplikasi Mobile", "price" => "3.500.000", "icon" => "fa-mobile-screen", "img" => "https://images.pexels.com/photos/1092671/pexels-photo-1092671.jpeg?auto=compress&cs=tinysrgb&w=800", "desc" => "Antarmuka aplikasi intuitif yang dibangun untuk konversi.", "color" => "linear-gradient(135deg, #a855f7, #9333ea)", "pj" => "Budi (Mobile Expert)"],
    ["id" => 5, "title" => "Brosur & Selebaran", "price" => "500.000", "icon" => "fa-file-lines", "img" => "https://images.pexels.com/photos/4068314/pexels-photo-4068314.jpeg?auto=compress&cs=tinysrgb&w=800", "desc" => "Aset pemasaran cetak yang dioptimalkan secara digital.", "color" => "linear-gradient(135deg, #06b6d4, #0891b2)", "pj" => "Rina (Print Specialist)"],
    ["id" => 6, "title" => "Desain Kemasan", "price" => "1.200.000", "icon" => "fa-box-open", "img" => "https://images.pexels.com/photos/3951615/pexels-photo-3951615.jpeg?auto=compress&cs=tinysrgb&w=800", "desc" => "Kemasan siap ritel yang menjual produk Anda lebih mahal.", "color" => "linear-gradient(135deg, #f97316, #ea580c)", "pj" => "Dedi (Package Master)"],
    ["id" => 7, "title" => "Aset Digital", "price" => "250.000", "icon" => "fa-layer-group", "img" => "https://images.pexels.com/photos/356056/pexels-photo-356056.jpeg?auto=compress&cs=tinysrgb&w=800", "desc" => "Set ikon premium dan template media sosial siap pakai.", "color" => "linear-gradient(135deg, #84cc16, #65a30d)", "pj" => "Tim Kreatif Senior"]
];

// Ambil Notifikasi jika user login
$jml_notif = 0;
if(isset($_SESSION['id_user'])){
    $user_id = $_SESSION['id_user'];
    $q_notif_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM notifications WHERE id_user = '$user_id' AND is_read = 0");
    $jml_notif = mysqli_fetch_assoc($q_notif_count)['total'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VISANDI | Studio Desain AI Premium & Artistik</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <link rel="manifest" href="/manifest.json">

    <style>
        :root { 
            --vs-primary: #6366f1;
            --vs-rose: #f43f5e;
            --vs-text: #1e293b;
        }
        
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            color: var(--vs-text); 
            background: url('https://images.unsplash.com/photo-1497215728101-856f4ea42174?q=80&w=2000') no-repeat center center fixed;
            background-size: cover;
            scroll-behavior: smooth; 
            overflow-x: hidden; 
        }

        body::before {
            content: ""; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(245,247,255,0.92) 100%);
            z-index: -1;
        }

        h1, h2, h3, h4, .navbar-brand { font-family: 'Sora', sans-serif; font-weight: 800; }

        .navbar { 
            background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(20px); 
            border-bottom: 1px solid rgba(99, 102, 241, 0.1); padding: 15px 0;
        }
        .notif-bell { position: relative; font-size: 1.5rem; color: var(--vs-text); cursor: pointer; transition: 0.3s; }
        .notif-bell:hover { color: var(--vs-primary); transform: scale(1.1); }
        .notif-badge { position: absolute; top: -5px; right: -5px; font-size: 0.65rem; border: 2px solid white; }

        .user-pill {
            background: #fff; border: 1px solid rgba(99, 102, 241, 0.1);
            padding: 8px 18px; border-radius: 14px; font-weight: 700;
            display: flex; align-items: center; gap: 10px; cursor: pointer; color: var(--vs-primary);
            box-shadow: 0 4px 15px rgba(0,0,0,0.03); transition: 0.3s;
        }
        .user-pill:hover { background: #f8fafc; }

        .card-vs { 
            border-radius: 40px; overflow: hidden; height: 100%; position: relative;
            transition: 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); color: white;
        }
        .card-vs:hover { transform: translateY(-15px); box-shadow: 0 30px 60px rgba(0,0,0,0.25); }

        .pj-badge { background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3); font-size: 0.65rem; font-weight: 700; padding: 6px 12px; border-radius: 100px; }

        .service-img-mask { height: 210px; overflow: hidden; margin: 15px; border-radius: 30px; border: 2px solid rgba(255,255,255,0.3); }
        .service-img-mask img { width: 100%; height: 100%; object-fit: cover; transition: 0.8s; }
        .card-vs:hover .service-img-mask img { transform: scale(1.15); }

        .btn-order-white { 
            background: white; color: black; border: none; border-radius: 15px; 
            padding: 12px 25px; font-weight: 800; transition: 0.3s; text-decoration: none; display: inline-block;
        }
        .btn-order-white:hover { background: #f8fafc; transform: scale(1.05); color: #000; }

        .port-card { 
            border-radius: 30px; overflow: hidden; height: 380px; position: relative; 
            border: 6px solid white; box-shadow: 0 15px 35px rgba(0,0,0,0.1); transition: 0.4s;
        }
        .port-card:hover { transform: rotate(1deg) scale(1.02); }
        .port-card img { width: 100%; height: 100%; object-fit: cover; }
        .port-overlay { position: absolute; bottom: 0; left: 0; width: 100%; padding: 25px; background: linear-gradient(transparent, rgba(0,0,0,0.9)); color: white; }

        .hero-img-box { animation: floating 6s ease-in-out infinite; }
        @keyframes floating { 0%, 100% { transform: translateY(0) rotate(1deg); } 50% { transform: translateY(-25px) rotate(-1deg); } }
        .gradient-text { background: linear-gradient(90deg, #6366f1, #f43f5e, #8b5cf6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

        .wa-float {
            position: fixed; bottom: 40px; right: 40px; background: #25d366; color: white; 
            padding: 18px 30px; border-radius: 25px; font-weight: 800; z-index: 1000;
            box-shadow: 0 20px 40px rgba(37, 211, 102, 0.3); transition: 0.4s; text-decoration: none !important;
        }
        .wa-float:hover { transform: scale(1.1); color: white; }

        /* Section Tentang Kami Style */
        .section-tentang { background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(20px); border-radius: 60px; padding: 80px 0; margin-top: 50px; }
        .icon-box-about { width: 60px; height: 60px; background: white; border-radius: 20px; display: flex; align-items: center; justify-content: center; color: var(--vs-primary); font-size: 1.5rem; box-shadow: 0 10px 20px rgba(0,0,0,0.05); margin-bottom: 20px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand text-primary" href="index.php">VISANDI<span class="text-rose">.</span></a>
        <div class="collapse navbar-collapse d-none d-lg-block">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link px-3 text-dark fw-bold" href="#layanan">Layanan</a></li>
                <li class="nav-item"><a class="nav-link px-3 text-dark fw-bold" href="#tentang">Tentang</a></li>
                <li class="nav-item"><a class="nav-link px-3 text-dark fw-bold" href="#portfolio">Portofolio</a></li>
                <li class="nav-item"><a class="nav-link px-3 text-dark fw-bold" href="#ulasan">Ulasan</a></li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-3">
            <?php if(isset($_SESSION['nama'])): ?>
                <div class="dropdown">
                    <div class="notif-bell" data-bs-toggle="dropdown" id="notifDropdown">
                        <i class="bi bi-bell-fill"></i>
                        <?php if($jml_notif > 0): ?>
                            <span class="badge rounded-pill bg-danger notif-badge"><?= $jml_notif ?></span>
                        <?php endif; ?>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-3 rounded-4 mt-3 animate__animated animate__fadeIn" style="width: 300px; max-height: 400px; overflow-y: auto;">
                        <h6 class="fw-800 mb-3 small text-uppercase text-muted border-bottom pb-2">Pemberitahuan</h6>
                        <?php
                        $res_list_notif = mysqli_query($conn, "SELECT * FROM notifications WHERE id_user = '$user_id' ORDER BY id_notif DESC LIMIT 5");
                        if(mysqli_num_rows($res_list_notif) > 0):
                            while($n = mysqli_fetch_assoc($res_list_notif)):
                        ?>
                            <li class="mb-2 pb-2 border-bottom shadow-none">
                                <div class="small fw-bold text-dark"><?= htmlspecialchars($n['judul']) ?></div>
                                <div class="text-muted small" style="font-size: 0.75rem;"><?= htmlspecialchars($n['pesan']) ?></div>
                            </li>
                        <?php endwhile; else: ?>
                            <li class="text-center text-muted small py-3">Tidak ada kabar baru</li>
                        <?php endif; ?>
                        <li><a class="dropdown-item text-center small fw-bold text-primary mt-2" href="client/dashboard.php">Lihat Semua</a></li>
                    </ul>
                </div>

                <div class="user-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#logoutConfirmModal">
                    <i class="fa-solid fa-circle-user fs-5"></i>
                    <span class="d-none d-md-inline"><?= htmlspecialchars($_SESSION['nama']) ?></span>
                </div>
                
                <a href="client/dashboard.php" class="text-primary text-decoration-none fw-800 small border-start ps-3">Workspace</a>
            <?php else: ?>
                <a href="auth/login" class="btn btn-link text-dark text-decoration-none fw-bold">Masuk</a>
                <a href="auth/register" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" style="background: var(--vs-primary); border:none;">Mulai</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="modal fade" id="logoutConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 p-4 shadow-2xl rounded-5">
            <div class="modal-body text-center text-dark">
                <div class="mb-4 text-primary"><i class="bi bi-door-open display-4"></i></div>
                <h4 class="fw-800">Sudah Selesai Berkreasi?</h4>
                <p class="text-muted">Apakah Anda yakin ingin keluar dari sesi VISANDI?</p>
                <div class="d-flex gap-3 justify-content-center mt-5">
                    <button type="button" class="btn btn-light rounded-pill px-5 py-2 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <a href="auth/logout.php" class="btn btn-danger rounded-pill px-5 py-2 fw-bold shadow-lg">Ya, Keluar</a>
                </div>
            </div>
        </div>
    </div>
</div>

<header class="hero-section mt-5 pt-5">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-7 animate__animated animate__fadeInLeft">
                <span class="badge rounded-pill bg-white text-primary shadow-sm px-4 py-2 mb-4 fw-800 border">🎨 KREATIVITAS TINGKAT TINGGI</span>
                <h1 class="hero-title mb-4">Desain <span class="gradient-text">Mahakarya</span> Untuk Bisnis Anda.</h1>
                <p class="lead text-muted mb-5 fs-5">Studio desain premium yang menggabungkan rasa seni desainer elit dengan efisiensi teknologi kecerdasan buatan.</p>
                <div class="d-flex flex-column flex-sm-row gap-4">
                    <a href="#layanan" class="btn btn-primary rounded-pill px-5 py-3 fs-5 fw-bold shadow-lg" style="background: var(--vs-primary); border:none;">Eksplor Layanan</a>
                    <a href="#portfolio" class="btn btn-outline-dark rounded-pill px-5 py-3 fs-5 fw-bold">Lihat Portofolio</a>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-center mt-5 mt-lg-0">
                <div class="hero-img-box">
                    <img src="https://images.unsplash.com/photo-1542744094-3a31f272c490?q=80&w=1000" class="img-fluid rounded-5 shadow-2xl border-white border-8" alt="Professional Designer">
                </div>
            </div>
        </div>
    </div>
</header>

<section id="tentang" class="section-tentang">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 class="display-5 fw-800 mb-4">Mengenal <span class="text-primary">VISANDI.</span> Studio</h2>
                <p class="text-muted fs-5 mb-5">Kami bukan sekadar studio desain biasa. VISANDI lahir dari visi untuk menyatukan keindahan seni murni dengan kekuatan teknologi masa depan. Kami percaya bahwa setiap brand memiliki cerita yang pantas untuk divisualisasikan menjadi sebuah mahakarya.</p>
                <div class="row g-4">
                    <div class="col-sm-6">
                        <div class="icon-box-about"><i class="bi bi-lightbulb"></i></div>
                        <h5 class="fw-bold">Visi Artistik</h5>
                        <p class="small text-muted">Menghadirkan desain yang tidak hanya indah dipandang, tapi juga memiliki makna mendalam.</p>
                    </div>
                    <div class="col-sm-6">
                        <div class="icon-box-about"><i class="bi bi-cpu"></i></div>
                        <h5 class="fw-bold">Teknologi AI</h5>
                        <p class="small text-muted">Memanfaatkan efisiensi AI untuk mempercepat proses kreatif tanpa mengurangi kualitas seni.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="p-5 bg-white shadow-lg rounded-5">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div style="width: 10px; height: 40px; background: var(--vs-primary); border-radius: 5px;"></div>
                        <h4 class="fw-800 m-0">Mengapa Memilih Kami?</h4>
                    </div>
                    <ul class="list-unstyled">
                        <li class="mb-4 d-flex gap-3">
                            <i class="bi bi-check-circle-fill text-primary"></i>
                            <div><strong class="d-block">Eksklusifitas Terjamin</strong> <span class="small text-muted">Setiap desain dibuat khusus (custom) dan unik untuk setiap klien kami.</span></div>
                        </li>
                        <li class="mb-4 d-flex gap-3">
                            <i class="bi bi-check-circle-fill text-primary"></i>
                            <div><strong class="d-block">Penanggung Jawab Ahli</strong> <span class="small text-muted">Tiap layanan dipegang oleh spesialis yang sudah berpengalaman di bidangnya.</span></div>
                        </li>
                        <li class="d-flex gap-3">
                            <i class="bi bi-check-circle-fill text-primary"></i>
                            <div><strong class="d-block">Workspace Terintegrasi</strong> <span class="small text-muted">Pantau perkembangan proyek Anda secara real-time melalui sistem dashboard kami.</span></div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="portfolio" class="py-5">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-800 display-5 text-dark">Portofolio Kami ✨</h2>
            <p class="text-muted">Inilah alasan mengapa ratusan brand mempercayai kami.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="port-card"><img src="https://images.pexels.com/photos/1766664/pexels-photo-1766664.jpeg" alt="P1"><div class="port-overlay"><h6 class="m-0 fw-bold">Brand Identity</h6></div></div>
            </div>
            <div class="col-md-4">
                <div class="port-card"><img src="https://images.pexels.com/photos/1779487/pexels-photo-1779487.jpeg" alt="P2"><div class="port-overlay"><h6 class="m-0 fw-bold">UI/UX Website</h6></div></div>
            </div>
            <div class="col-md-4">
                <div class="port-card"><img src="https://images.pexels.com/photos/267350/pexels-photo-267350.jpeg" alt="P3"><div class="port-overlay"><h6 class="m-0 fw-bold">Digital Ads</h6></div></div>
            </div>
        </div>
    </div>
</section>

<section id="layanan" class="py-5" style="background: rgba(243, 244, 246, 0.4); border-radius: 80px 80px 0 0;">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-800 display-5 text-dark">Solusi Desain Eksklusif</h2>
            <p class="text-muted">Setiap proyek dikerjakan oleh ahli di bidangnya.</p>
        </div>
        <div class="row g-5">
            <?php foreach($services as $s): ?>
            <div class="col-xl-4 col-md-6">
                <div class="card-vs animate__animated animate__fadeInUp" style="background: <?= $s['color'] ?>;">
                    <div class="service-img-mask"><img src="<?= $s['img'] ?>" alt="<?= $s['title'] ?>"></div>
                    <div class="card-body p-4 pt-0">
                        <div class="mb-3">
                            <span class="pj-badge">
                                <i class="bi bi-person-check-fill me-1"></i> PJ: <?= $s['pj'] ?>
                            </span>
                        </div>
                        <h4 class="fw-800 mb-2 text-white"><?= $s['title'] ?></h4>
                        <p class="text-white opacity-75 small mb-4" style="height:45px; overflow:hidden;"><?= $s['desc'] ?></p>
                        
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top border-white border-opacity-20">
                            <div>
                                <small class="text-white opacity-75 d-block fw-bold" style="font-size: 0.6rem;">HARGA TERBAIK</small>
                                <h4 class="fw-bold m-0 text-white">Rp <?= $s['price'] ?></h4>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#tanyaModal<?= $s['id'] ?>">Tanya Dulu</button>
                                <a href="client/order_detail.php?id=<?= $s['id'] ?>" class="btn-order-white">Pesan</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="tanyaModal<?= $s['id'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-5 text-dark">
                        <div class="modal-header border-0 p-4">
                            <h5 class="fw-800 m-0">Konsultasi: <?= $s['title'] ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <?php if(isset($_SESSION['id_user'])): ?>
                                <form action="proses_tanya.php" method="POST">
                                    <input type="hidden" name="id_service" value="<?= $s['id'] ?>">
                                    <div class="mb-3">
                                        <label class="small fw-bold text-muted mb-2">APA YANG INGIN ANDA TANYAKAN?</label>
                                        <textarea name="pesan" class="form-control" rows="4" placeholder="Tuliskan detail kebutuhan atau pertanyaan Anda..." required></textarea>
                                    </div>
                                    <button type="submit" name="kirim_tanya" class="btn btn-primary w-100 rounded-pill fw-bold py-2 shadow-sm" style="background: var(--vs-primary); border:none;">Kirim Pertanyaan</button>
                                </form>
                            <?php else: ?>
                                <div class="text-center py-3">
                                    <i class="bi bi-lock display-4 text-muted mb-3 d-block"></i>
                                    <p class="text-muted">Silakan login untuk berkonsultasi.</p>
                                    <a href="auth/login" class="btn btn-primary rounded-pill px-4 fw-bold">Masuk Sekarang</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="ulasan" class="py-5 bg-white">
    <div class="container py-5 text-center text-dark">
        <h2 class="fw-800 display-5 mb-5">Kepuasan Klien</h2>
        <div class="row g-4">
            <?php 
            $get_revs = mysqli_query($conn, "SELECT * FROM reviews ORDER BY id_review DESC LIMIT 3");
            while($rev = mysqli_fetch_assoc($get_revs)): ?>
            <div class="col-md-4 text-start">
                <div class="p-5 bg-light rounded-5 h-100 shadow-sm border-0">
                    <div class="text-warning mb-3"><?php for($i=1; $i<=(int)$rev['rating']; $i++) echo '⭐'; ?></div>
                    <p class="text-muted mb-4 fst-italic">"<?= htmlspecialchars($rev['komentar']) ?>"</p>
                    <h6 class="m-0 fw-800 text-dark"><?= htmlspecialchars($rev['nama_klien']) ?></h6>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<footer class="py-5 border-top bg-white text-center text-dark">
    <h3 class="fw-800 mb-4 text-primary">VISANDI<span class="text-rose">.</span></h3>
    <div class="d-flex justify-content-center gap-4 mb-4 fs-3">
        <a href="https://instagram.com/sandislmt_" target="_blank" class="text-primary"><i class="fa-brands fa-instagram"></i></a>
        <a href="https://youtube.com/@Sandi._27" target="_blank" class="text-primary"><i class="fa-brands fa-youtube"></i></a>
    </div>
    <div class="small text-muted">© 2026 VISANDI STUDIO. Crafting Visual Excellence.</div>
</footer>

<a href="https://wa.me/6281234567890" class="wa-float" target="_blank">
    <i class="fa-brands fa-whatsapp me-3 fs-3"></i><span>Bantuan Cepat</span>
</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('/sw.js').then(reg => {
        console.log('VISANDI App Ready!');
      }).catch(err => {
        console.log('Gagal pasang app:', err);
      });
    });
  }
</script>

</body>
</html>