# CASP - Catatan Anak Sekolah Pusdikhubad

aplikasi ini buat nyimpen Kenang kenangan foto Atau Catatan Antar Jutusan. dibuat pake PHP, MySQL, sama javascript biasa (yang java script make ai). ada fitur login, upload foto, sama hapus postingan.

## Fitur yang ada

- login pake username atau NISN kalau mau dan password, atau bisa masuk sebagai tamu
- upload foto sama judul & keterangan
- galeri dipisah per jurusan Ada RPL, TKR, TBSM, MEKA, ELIND
- bisa filter postingan dari yang terbaru atau terlama
- ada kolom pencarian langsung. ngetik langsung kefilter Jadi yang di search user
- hapus postingan sendiri tanpa reload halaman
- klik foto biar bisa keliatan lebih gede make (lightbox)
- tema terang dan gelap

## Isi File

 File Dan Fungsinya
 
koneksi.php nyambungin aplikasi ke database MySQL 
login.php  halaman form login sama tombol masuk sebagai tamu 
proseslogin.php  proses data loginnya, cek ke database, bikin session 
dashboard.php  halaman utama, isi galeri foto sama form upload 
hapus_postingan.php. hapus postingan sama filenya dari server 
db_casp.sql. file database, struktur tabelnya ada disini 
style.css. tampilan dashboard 
stylelogin.css. tampilan halaman login 



## Cara Kerjanya

### koneksi.php
file ini cuma buat nyambungin ke database pake mysqli_connect(). kalo gagal konek langsung berhenti dan muncul pesan error. file ini dipanggil di hampir semua file php lainnya pake require_once.

### login.php + proseslogin.php
di halaman login ada dua cara masuk, bisa login biasa pake username sama password, atau langsung masuk sebagai tamu. kalo login biasa, datanya dikirim ke proseslogin.php buat dicek ke tabel pengguna di database. kalo cocok, data penggunanya disimpen ke session terus diarahin ke dashboard. kalo ga cocok ya balik lagi ke login.

buat login tamu ga perlu cek database sama sekali, langsung dikasih session dengan ID 0 dan jurusan GUEST.

### dashboard.php
ini file paling penting. Jadi pas dibuka, yang pertama dicek adalah sessionnya, kalo belum login langsung ditolak.

buat upload foto, sistemnya bakal:
1. nyaring judul sama keterangan dari kata-kata kasar pake blacklist
2. ngecek apakah filenya beneran gambar (JPG, PNG, atau GIF)
3. mindahin file ke folder uploads/ dengan nama unik dari uniqid()
4. nyimpen data postingannya ke tabel galeri di database

postingan ditampilin sebagai kartu berdasarkan jurusan yang dipilih. tombol hapus cuma muncul di postingan milik sendiri.

soal hak akses, pengguna cuma bisa upload di galeri jurusannya sendiri. di jurusan lain cuma bisa liat. tamu ga bisa upload sama sekali.

### hapus_postingan.php
file ini jalan di balik layar pake AJAX (fetch API), jadi postingan bisa kehapus tanpa reload halaman. alurnya: cek kepemilikan dulu, hapus file fotonya dari server pake unlink(), terus hapus dari database. hasilnya dikirim balik ke halaman dalam bentuk JSON, dan javascript langsung ilangin kartunya dari tampilan pake animasi fade.

### fitur-fitur lain di dashboard
  live search ngetik di kolom cari langsung nyembunyiin kartu yang ga cocok, tanpa ke server
  lightbox  klik foto buat liat lebih gede di tengah layar
  jam realtime  jam yang jalan terus pake setInterval
  tema tersimpan pilihan tema nyimpen di localStorage jadi ga ilang pas refresh


## Database

ada dua tabel utama:
pengguna nyimpen data akun (username, password, nama, jurusan)
galeri  nyimpen data postingan (judul, keterangan, nama file foto, jurusan, waktu upload)

Jadi sebelum jalanin aplikasinya, import dulu file db_casp.sql ke phpMyAdmin.


## Bahasa Penrograman yang dioakai

- PHP
- MySQL
- HTML & CSS
- JavaScript

