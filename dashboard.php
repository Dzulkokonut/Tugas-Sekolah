<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    echo "<script>alert('Akses Ditolak! Login dulu sana kocak!');window.location.href='login.php';</script>";
    exit();
}

// Logika Upload (Cuma jalan kalau bukan guest)
if (isset($_POST['upload_galeri']) && $_SESSION['jurusan'] !== 'GUEST') {
    $judul      = mysqli_real_escape_string($koneksi, $_POST['judul_kenangan']);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
    $id_user    = $_SESSION['id_pengguna'];
    $jurusan    = $_SESSION['jurusan'];

    $blacklist = ['anjing', 'bangsat', 'tolol', 'p**no'];
    $is_kotor  = false;
    foreach ($blacklist as $kata) {
        if (stripos($judul, $kata) !== false || stripos($keterangan, $kata) !== false) {
            $is_kotor = true;
            break;
        }
    }
    if ($is_kotor) {
        echo "<script>alert('Gagal! Catatan Maneh mengandung unsur tidak senonoh, EA');window.location.href='dashboard.php';</script>";
        exit();
    }

    $nama_file  = $_FILES['foto']['name'];
    $tmp_file   = $_FILES['foto']['tmp_name'];
    $error_file = $_FILES['foto']['error'];

    if ($error_file === 4) {
        echo "<script>alert('Pilih foto dulu Ari Kamu Capruk');window.location.href='dashboard.php';</script>";
        exit();
    }

    $ekstensi_valid = ['jpg', 'jpeg', 'png', 'gif'];
    $pecah          = explode('.', $nama_file);
    $ekstensi_file  = strtolower(end($pecah));

    if (!in_array($ekstensi_file, $ekstensi_valid)) {
        echo "<script>alert('Yang Maneh Upload bukan berbentuk JPG/PNG/GIF');window.location.href='dashboard.php';</script>";
        exit();
    }

    $nama_file_baru = uniqid() . '.' . $ekstensi_file;
    $path_tujuan    = 'uploads/' . $nama_file_baru;

    if (move_uploaded_file($tmp_file, $path_tujuan)) {
        $query_insert = "INSERT INTO galeri (id_pengguna, judul_kenangan, keterangan, nama_file_foto, tag_jurusan, status)
                         VALUES ('$id_user', '$judul', '$keterangan', '$nama_file_baru', '$jurusan', 'aman')";
        mysqli_query($koneksi, $query_insert);
        echo "<script>alert('Arsip kenangan berhasil ditambahkan!');window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal mindahin file! Cek hak akses folder uploads Si Kamu.');window.location.href='dashboard.php';</script>";
    }
}

// Setel default view buat guest biar ga kosong layarnya
if ($_SESSION['jurusan'] == 'GUEST' && !isset($_GET['filter_jurusan'])) {
    $jurusan_aktif = 'RPL'; 
} else {
    $jurusan_aktif = isset($_GET['filter_jurusan']) ? $_GET['filter_jurusan'] : $_SESSION['jurusan'];
}

// --- LOGIKA SORTIR TANGGAL/BULAN/TAHUN (NATURAL) ---
// Nangkep pilihan user dari dropdown, defaultnya 'baru' (DESC)
$urutan = isset($_GET['urut']) ? $_GET['urut'] : 'baru';
$sql_urut = ($urutan == 'lama') ? "ASC" : "DESC";

$query_galeri  = mysqli_query($koneksi, "SELECT g.*, p.nama_lengkap FROM galeri g
                                         JOIN pengguna p ON g.id_pengguna = p.id_pengguna
                                         WHERE g.tag_jurusan = '$jurusan_aktif' AND g.status = 'aman'
                                         ORDER BY g.waktu_upload $sql_urut");

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CASP - Kenangan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div id="efek-tv"></div>

<div id="lb" onclick="lbC(event)">
    <div id="lbx" onclick="lbC()">✕</div>
    <img id="lbi" src="" alt="">
    <div id="lbc"></div>
</div>

<header>
    <div class="logo">
        <img src="logo_smk.png" alt="Logo SMK" height="42" style="margin-right: 8px; object-fit: contain;">
        CAS <span>PUSDIKHUBAD</span>
    </div>
    <div class="hr">
        <span class="uc">👤 <b><?php echo $_SESSION['nama_lengkap']; ?></b> 
        <?php echo ($_SESSION['jurusan'] != 'GUEST') ? "· " . $_SESSION['jurusan'] : ""; ?></span>
        <span id="jamDigital" style="margin-left:15px; font-family:monospace; color:var(--a); font-size:14px; font-weight:bold;"></span>
        <button class="btt" onclick="ubahTema()" title="Ganti tema">🌓</button>
        <a href="login.php" class="blo" onclick="return confirm('Yakin mau keluar Dik?')">Logout</a>
    </div>
</header>

<div class="wrap">

    <div class="toolbars">
        <input type="text" id="cariKenangan" class="inp-cari" placeholder="🔍 Cari judul Kenang Kenagan Lek" onkeyup="filterPostingan()">

        <form action="" method="GET" style="margin: 0; display: flex;" id="formSortir">
            <input type="hidden" name="filter_jurusan" value="<?php echo $jurusan_aktif; ?>">
            <select name="urut" class="inp-urut" onchange="document.getElementById('formSortir').submit();">
                <option value="baru" <?php if($urutan=='baru') echo 'selected'; ?>>📅 Paling Baru</option>
                <option value="lama" <?php if($urutan=='lama') echo 'selected'; ?>>⏳ Paling Lama</option>
            </select>
        </form>
    </div>


    <div class="tabs">
        <a href="dashboard.php?filter_jurusan=RPL"   class="tab <?php echo ($jurusan_aktif == 'RPL')   ? 'on' : ''; ?>">RPL</a>
        <a href="dashboard.php?filter_jurusan=TKR"   class="tab <?php echo ($jurusan_aktif == 'TKR')   ? 'on' : ''; ?>">TKR</a>
        <a href="dashboard.php?filter_jurusan=TBSM"  class="tab <?php echo ($jurusan_aktif == 'TBSM')  ? 'on' : ''; ?>">TBSM</a>
        <a href="dashboard.php?filter_jurusan=MEKA"  class="tab <?php echo ($jurusan_aktif == 'MEKA')  ? 'on' : ''; ?>">MEKA</a>
        <a href="dashboard.php?filter_jurusan=ELIND" class="tab <?php echo ($jurusan_aktif == 'ELIND') ? 'on' : ''; ?>">ELIND</a>
    </div>

    <?php if ($_SESSION['jurusan'] === 'GUEST') : ?>
        <div class="vob" style="border-left-color: #ff6b6b;">
            <span> 👤</span>
            <div>Maneh login sebagai <b>Tamu Ataw Guest </b>. Silakan pantau kelakuan warga sekolah ini. <b>Maneh cuma bisa liat, Karunya Teuing, Matakna Daftar Di Pusdik</b></div>
        </div>
    <?php elseif ($jurusan_aktif === $_SESSION['jurusan']) : ?>
        <div class="upbox">
            <h2>+ Upload Kenangan — <em><?php echo $_SESSION['jurusan']; ?></em></h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <label class="lbl">Judul</label>
                <input type="text" name="judul_kenangan" class="inp" required autocomplete="off" placeholder="Tulis judul singkat Ajah Meh Teu Lieur">

                <label class="lbl">Isi / Keterangan</label>
                <textarea name="keterangan" class="inp" required placeholder="Keterangan" ></textarea>

                <label class="lbl">Foto (JPG / PNG / GIF)</label>
                <input type="file" name="foto" accept="image/*" class="inp" required>

                <button type="submit" name="upload_galeri" class="bup">Kirim ke Galeri →</button>
            </form>
        </div>
    <?php else : ?>
        <div class="vob">
            <span>👁</span>
            <div>Maneh anak jurusan <b><?php echo $_SESSION['jurusan']; ?></b>. Di galeri <b><?php echo $jurusan_aktif; ?></b> cuma bisa liat doang ga bisa upload atau ngedit. <b>Lau Sape Mpruy 😹.</b></div>
        </div>
    <?php endif; ?>

    <div class="sh">
        <h3>Kenangan Jurusan <?php echo $jurusan_aktif; ?></h3>
        <span class="ct"><?php echo mysqli_num_rows($query_galeri); ?> post</span>
    </div>

    <div class="grid" id="lapakGrid">
        <?php
        if (mysqli_num_rows($query_galeri) > 0) {
            while ($row = mysqli_fetch_assoc($query_galeri)) {
                echo '<div class="card">';

                $foto_path = 'uploads/' . $row['nama_file_foto'];
                if (file_exists($foto_path) && !is_dir($foto_path)) {
                    $judul_esc = addslashes(htmlspecialchars($row['judul_kenangan']));
                    echo '<img class="ci" src="'.$foto_path.'" alt="Kenangan" onclick="lbO(this.src,\''.$judul_esc.'\')">';
                } else {
                    echo '<div class="ni">📷 '.$row['nama_file_foto'].'</div>';
                }

                echo '<div class="cb">';
                echo '  <div class="ct2">'.htmlspecialchars($row['judul_kenangan']).'</div>';
                echo '  <div class="cd">'.htmlspecialchars($row['keterangan']).'</div>';
                echo '  <div class="cm">';
                echo '    <span class="by">oleh <b>'.htmlspecialchars($row['nama_lengkap']).'</b></span>';
                echo '    <span>'.date('d M Y', strtotime($row['waktu_upload'])).'</span>';
                echo '  </div>';

                // Tombol hapus gak bakal nongol buat Guest karena ID user guest = 0
                if ($row['id_pengguna'] == $_SESSION['id_pengguna'] && $_SESSION['jurusan'] !== 'GUEST') {
                    echo '<button class="bhp" onclick="hapus('.$row['id_galeri'].',this)">🗑 Hapus</button>';
                }

                echo '</div></div>';
            }
        } else {
            echo '<div class="emp"><span>📭</span>Belum ada postingan di jurusan ini.</div>';
        }
        ?>
    </div>

</div>

<script>
    // FUNGSI NYARI POSTINGAN ALA INTEL (Live Search Dom)
    function filterPostingan() {
        let inputan = document.getElementById('cariKenangan').value.toLowerCase();
        let kumpulankartu = document.querySelectorAll('.card');

        kumpulankartu.forEach(kartu => {
            let isiTeks = kartu.innerText.toLowerCase();
            // Kalo kata yang dicari ada di dalem teks kartunya, tampilin. Kalo gaada, sembunyiin.
            if(isiTeks.includes(inputan)) {
                kartu.style.display = '';
            } else {
                kartu.style.display = 'none';
            }
        });
    }

    // Lightbox bawaan lu
    function lbO(s, j) {
        document.getElementById('lbi').src = s;
        document.getElementById('lbc').textContent = j;
        document.getElementById('lb').classList.add('on');
        document.body.style.overflow = 'hidden';
    }
    function lbC(e) {
        if (!e || e.target !== document.getElementById('lbi')) {
            document.getElementById('lb').classList.remove('on');
            document.getElementById('lbi').src = '';
            document.body.style.overflow = '';
        }
    }
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') lbC(); });

    // AJAX hapus bawaan lu
    function hapus(id, t) {
        if (!confirm('Yakin Deks Mau Hapus Ga bisa balik lagi Ari Kamu')) return;
        var card = t.closest('.card');
        fetch('hapus_postingan.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id_galeri=' + id
        })
        .then(function(r) { return r.json(); })
        .then(function(d) {
            if (d.sukses) {
                card.style.transition = 'opacity .35s';
                card.style.opacity = '0';
                setTimeout(function() { card.remove(); }, 360);
            } else {
                alert('Gagal hapus: ' + (d.pesan || 'Error'));
            }
        })
        .catch(function() { alert('Koneksi error, coba lagi.'); });
    }

    // --- EKSPERIMENTAL: Easter Egg (Klik Logo 5x) ---
let jumlahKlik = 0;
let timerReset;
const tulisanLogo = document.querySelector('.logo');

if (tulisanLogo) {
    // Cegah teks logonya ke-blok (ke-highlight) pas diklik cepet-cepet
    tulisanLogo.style.userSelect = 'none'; 
    
    tulisanLogo.addEventListener('click', function() {
        jumlahKlik++;
        
        // Kalau udah diklik 5 kali
        if (jumlahKlik === 5) {
            alert("Nu Maca Botak Eweh Gawe pepencetan Teu Jelas\n\nGeus Balik Deui Kaditu Botak");
            jumlahKlik = 0; // Reset lagi biar bisa dipake ulang
        }
        
        // Fitur keamanan: Kalau user berhenti ngeklik selama 2 detik, counternya di-reset ke 0
        clearTimeout(timerReset);
        timerReset = setTimeout(function() {
            jumlahKlik = 0;
        }, 2000);
    });
}

// --- EKSPERIMENTAL: Jam Realtime ---
setInterval(function() {
    var waktu = new Date();
    var jam = waktu.getHours().toString().padStart(2, '0');
    var menit = waktu.getMinutes().toString().padStart(2, '0');
    var detik = waktu.getSeconds().toString().padStart(2, '0');
    var elemenJam = document.getElementById('jamDigital');
    if(elemenJam) elemenJam.innerText = "🕛 " + jam + ":" + menit ;
}, 1000);


// --- EKSPERIMENTAL: Ingatan Tema (Local Storage) ---

// 1. Fungsi buat nge-save pilihan tema ke memori browser
function ubahTema() {
    document.body.classList.toggle('lm');
    localStorage.setItem('tema_terang', document.body.classList.contains('lm'));
}

function ubahRetro() {
    document.body.classList.toggle('retro-mode');
    localStorage.setItem('tema_retro', document.body.classList.contains('retro-mode'));
}

// 2. Fungsi buat ngebaca memori pas halaman baru beres dimuat (biar ga balik gelap lagi)
document.addEventListener('DOMContentLoaded', function() {
    if (localStorage.getItem('tema_terang') === 'true') {
        document.body.classList.add('lm');
    }
    if (localStorage.getItem('tema_termux') === 'true') {
        document.body.classList.add('termux-mode');
    }
    if (localStorage.getItem('tema_retro') === 'true') {
        document.body.classList.add('retro-mode');
    }
});

</script>

</body>
</html>
