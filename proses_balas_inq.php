<?php
session_start();
include 'config/db.php';

if (isset($_POST['message'])) {
    $id_inquiry = mysqli_real_escape_string($conn, $_POST['id_inquiry']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $role = $_POST['role']; // 'admin' atau 'client'

    // Simpan pesan
    mysqli_query($conn, "INSERT INTO inquiry_messages (id_inquiry, sender_role, message) VALUES ('$id_inquiry', '$role', '$message')");

    if ($role == 'admin') {
        $id_user = $_POST['id_user'];
        // Update status ke 'dibalas' dan kirim notifikasi
        mysqli_query($conn, "UPDATE inquiries SET status = 'dibalas' WHERE id_inquiry = '$id_inquiry'");
        mysqli_query($conn, "INSERT INTO notifications (id_user, judul, pesan, is_read) VALUES ('$id_user', 'Konsultasi Dibalas! 💬', 'Admin telah membalas chat konsultasi Anda.', 0)");
        header("Location: admin/manage_orders.php");
    } else {
        // Update status ke 'pending' agar admin tahu
        mysqli_query($conn, "UPDATE inquiries SET status = 'pending' WHERE id_inquiry = '$id_inquiry'");
        header("Location: client/dashboard.php");
    }
    exit();
}
?>