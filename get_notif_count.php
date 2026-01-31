<?php
session_start();
include 'config/db.php';

// Set header agar browser mengenali ini sebagai data JSON
header('Content-Type: application/json');

if (!isset($_SESSION['id_user'])) { 
    echo json_encode(['total' => 0]); 
    exit(); 
}

$user_id = $_SESSION['id_user'];
$role = $_SESSION['role'];
$data = ['total' => 0];

if ($role == 'admin') {
    // Admin: Menghitung semua pesanan pending + revisi + semua chat dari client
    $q = mysqli_query($conn, "SELECT 
        (SELECT COUNT(*) FROM orders WHERE status = 'pending') + 
        (SELECT COUNT(*) FROM orders WHERE status = 'revisi') +
        (SELECT COUNT(*) FROM messages WHERE sender_role = 'client') as total");
    $data = mysqli_fetch_assoc($q);
} 
elseif ($role == 'staff') {
    // Staf: Menghitung pesanan/revisi di divisinya + chat client pada order miliknya
    $q = mysqli_query($conn, "SELECT 
        (SELECT COUNT(*) FROM orders o 
         JOIN services s ON o.id_service = s.id_service 
         WHERE s.id_staf = '$user_id' AND o.status IN ('pending','revisi')) +
        (SELECT COUNT(*) FROM messages m 
         JOIN orders o ON m.id_order = o.id_order 
         JOIN services s ON o.id_service = s.id_service 
         WHERE s.id_staf = '$user_id' AND m.sender_role = 'client') as total");
    $data = mysqli_fetch_assoc($q);
} 
else {
    // Client: Menghitung pesanan miliknya yang sudah 'selesai' + chat dari tim (admin/staf)
    $q = mysqli_query($conn, "SELECT 
        (SELECT COUNT(*) FROM orders WHERE id_client = '$user_id' AND status = 'selesai') +
        (SELECT COUNT(*) FROM messages m 
         JOIN orders o ON m.id_order = o.id_order 
         WHERE o.id_client = '$user_id' AND m.sender_role = 'admin') as total");
    $data = mysqli_fetch_assoc($q);
}

// Pastikan nilai total adalah angka (integer)
$data['total'] = (int)$data['total'];

echo json_encode($data);