<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
// proseslogin.php
session_start();
require_once 'koneksi.php'; // Ganti pakai require_once!

// HAPUS BARIS $koneksi = mysqli_connect(...) DI SINI! LANGSUNG MASUK KE IF:

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    // Cek kecocokan di database
    $query = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE username='$username' AND password='$password'");
    
    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        
        // Daftarkan tiket login resmi (Session)
        $_SESSION['status_login'] = true;
        $_SESSION['id_pengguna']  = $data['id_pengguna'];
        $_SESSION['username']     = $data['username'];
        $_SESSION['nama_lengkap']  = $data['nama_lengkap'];
        $_SESSION['jurusan']       = $data['jurusan'];
        
        header("Location: dashboard.php");
        exit();
    } else {
        echo "<script>
                alert('Usernya capruk engga terdaftar di database Pusdik');
                window.location.href='login.php';
              </script>";
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>

