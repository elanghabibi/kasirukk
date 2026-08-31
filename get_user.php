<?php
// get_user.php
header('Content-Type: application/json');

// Pastikan ada parameter ID
if (!isset($_GET['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'ID tidak ditemukan']);
    exit;
}

$id = intval($_GET['id']);

// --- CONTOH QUERY DATABASE (Koneksi PDO/MySQLi) ---
// $stmt = $pdo->prepare("SELECT id, nama, role FROM users WHERE id = ?");
// $stmt->execute([$id]);
// $user = $stmt->fetch(PDO::FETCH_ASSOC);

// Simulasi data dari database:
// $user = [
//     'id' => $id,
//     'nama' => 'Ahmad',
//     'role' => 'kasir'
// ];

if ($user) {
    echo json_encode([
        'status' => 'success',
        'user' => $user
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'User tidak ditemukan'
    ]);
}