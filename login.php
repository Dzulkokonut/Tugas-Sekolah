<?php
session_start();
// --- BYPASS LOGIN BUAT GUEST ---
if (isset($_POST['login_guest'])) {
    $_SESSION['status_login'] = true;
    $_SESSION['id_pengguna']  = 0;
    $_SESSION['username']     = 'tamu_nyasar';
    $_SESSION['nama_lengkap'] = 'Tamu Anonim';
    $_SESSION['jurusan']      = 'GUEST';
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CASP - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="stylelogin.css">
</head>
<body>

<div class="login-box">
    <!-- Toggle Tema -->
    <button class="theme-toggle" id="themeToggle" title="Ganti tema" type="button">🌙</button>

    <!-- Header -->
    <div class="logo-area">
        <div class="logo-dot">👤</div>
        <h2>LOGIN KE CASP</h2>
        <p class="subtitle">Masukkan kredensial kamu</p>
    </div>

    <!-- Form Login -->
    <form action="proseslogin.php" method="POST">
        <div class="form-group">
            <label>Username / NIS</label>
            <input type="text" name="username" placeholder="Masukkan username" required autocomplete="off">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan password" required>
        </div>
        <button type="submit" name="login" class="btn-login">MASUK</button>
    </form>

    <div class="divider">ATAU</div>

    <form method="POST">
        <button type="submit" name="login_guest" class="btn-guest">👀 Masuk Sebagai Tamu</button>
    </form>
</div>

<script>
    const html = document.documentElement;
    const btn  = document.getElementById('themeToggle');

    // Default: light mode
    const saved = localStorage.getItem('casp-theme') || 'light';
    applyTheme(saved);

    btn.addEventListener('click', () => {
        const next = html.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
        applyTheme(next);
        localStorage.setItem('casp-theme', next);
    });

    function applyTheme(theme) {
        html.setAttribute('data-theme', theme);
        btn.textContent = theme === 'light' ? '🌙' : '☀️';
        btn.title = theme === 'light' ? 'Ganti ke mode gelap' : 'Ganti ke mode terang';
    }
</script>

</body>
</html>
