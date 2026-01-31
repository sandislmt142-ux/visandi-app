<?php
session_start();
include 'config/db.php';

// Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: auth/login.php");
    exit();
}

if (isset($_POST['kirim_tanya'])) {
    $id_user = $_SESSION['id_user'];
    $id_service = mysqli_real_escape_string($conn, $_POST['id_service']);
    $pesan = mysqli_real_escape_string($conn, $_POST['pesan']);

    // 1. CEK APAKAH SUDAH ADA SESI TANYA-JAWAB UNTUK LAYANAN INI
    $cek_inquiry = mysqli_query($conn, "SELECT id_inquiry FROM inquiries WHERE id_user = '$id_user' AND id_service = '$id_service'");
    
    if (mysqli_num_rows($cek_inquiry) > 0) {
        // Jika sudah ada, ambil id_inquiry yang lama
        $data_inq = mysqli_fetch_assoc($cek_inquiry);
        $id_inquiry = $data_inq['id_inquiry'];
    } else {
        // Jika belum ada, buat sesi baru di tabel inquiries
        mysqli_query($conn, "INSERT INTO inquiries (id_user, id_service, status) VALUES ('$id_user', '$id_service', 'pending')");
        $id_inquiry = mysqli_insert_id($conn);
    }

    // 2. MASUKKAN PESAN KE TABEL RIWAYAT CHAT (inquiry_messages)
    // Logika: Menyimpan pesan agar bisa dilihat berkali-kali dalam bentuk percakapan
    $sql_msg = "INSERT INTO inquiry_messages (id_inquiry, sender_role, message) 
                VALUES ('$id_inquiry', 'client', '$pesan')";
    
    if (mysqli_query($conn, $sql_msg)) {
        // Update status sesi tanya-jawab ke 'pending' agar admin tahu ada pesan baru yang belum dibalas
        mysqli_query($conn, "UPDATE inquiries SET status = 'pending' WHERE id_inquiry = '$id_inquiry'");
        
        $_SESSION['notif'] = "Pertanyaan terkirim! Cek balasan admin secara berkala di Workspace Anda. ✨";
    } else {
        $_SESSION['notif'] = "Maaf, terjadi kesalahan teknis saat mengirim pesan.";
    }

    header("Location: index.php");
    exit();
}
?>