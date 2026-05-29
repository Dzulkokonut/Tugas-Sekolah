<?php
session_start();
require_once 'koneksi.php';

header('Content-Type: application/json');

// Blokir kalau belum login
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    echo json_encode(['sukses' => false, 'pesan' => 'Akses ditolak, login dulu!']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_galeri'])) {
    $id_galeri  = (int) $_POST['id_galeri'];
    $id_user    = (int) $_SESSION['id_pengguna'];

    // Ambil data postingan, pastikan milik user sendiri
    $cek = mysqli_query($koneksi, "SELECT nama_file_foto, id_pengguna FROM galeri WHERE id_galeri = '$id_galeri' LIMIT 1");
    
    if (!$cek || mysqli_num_rows($cek) === 0) {
        echo json_encode(['sukses' => false, 'pesan' => 'Postingan tidak ditemukan.']);
        exit();
    }

    $data = mysqli_fetch_assoc($cek);

    // Cek kepemilikan — hanya pemilik yang boleh hapus
    if ((int)$data['id_pengguna'] !== $id_user) {
        echo json_encode(['sukses' => false, 'pesan' => 'Bukan postingan maneh, jink!']);
        exit();
    }

    // Hapus file foto dari server
    $file_path = 'uploads/' . $data['nama_file_foto'];
    if (file_exists($file_path) && !is_dir($file_path)) {
        unlink($file_path);
    }

    // Hapus record dari database
    $hapus = mysqli_query($koneksi, "DELETE FROM galeri WHERE id_galeri = '$id_galeri'");

    if ($hapus) {
        echo json_encode(['sukses' => true, 'pesan' => 'Postingan berhasil dihapus!']);
    } else {
        echo json_encode(['sukses' => false, 'pesan' => 'Gagal hapus dari database.']);
    }

} else {
    echo json_encode(['sukses' => false, 'pesan' => 'Request tidak valid.']);
}
?>
