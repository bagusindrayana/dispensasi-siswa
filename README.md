## Aplikasi Izin/Dispensasi Siswa
di buat dengan PHP Native

## Requirements
- PHP 7.2
- mysql 5.7

## Library frontend/desain yang di gunakan
- [Bootstrap 4](https://getbootstrap.com/)
- [Jquery](https://jquery.com/)
- [Font Awesome](https://fontawesome.com/)
- [Template Admin](https://github.com/HackerThemes/spur-template)

## Instalasi
- Clone repository ini
- buat database baru
- import database yang ada di file `database.sql`
- buka file `config/database.php` dan sesuaikan dengan konfigurasi database anda

## Struktur Folder
- `assets` berisi file-file seperti css/js/image dll
- `config` berisi file-file konfigurasi dari nama aplikasi hingga konfigurasi database
- `library` berisi file-file helper seperti pengecekan session dan session message
- `actions` berisi file-file yang akan menerima perintah dari form seperi POST dan GET
  - `actions/_models` berisi file-file model yang mewakili tabel di database serta birisi fungsi-fungsi yang berhubungan dengan tabel tersebut
- `pages` berisi file-file halaman yang akan di tampilkan


Halaman dashboard berada di file `index.php` di direktori utama


## Session
- setelah login, aplikasi akan menyimpan session dengan nama `user` yang berisi data user yang sedang login dan sesion `login` dengan nilai true atau false
- pengecekan di lakukan di file `library/cek_session.php`, jika tidak di temukan sessin `user` atau `login` maka akan di redirect ke halaman login
- di  `library/cek_session.php` akan mendeklarasikan variable `$user` berdasarkan informasi session `user` yang di dapatkan dan di cek lagi apakah data session `user` ada di database (berdasarkan nilai id dan username)
- jika ingin mengambil data user yang login pastikan selalu include/require file `library/cek_session.php` di setiap halaman yang membutuhkan data user yang login


## Rule / Hak Akses
terdapat 3 hak akses, yaitu waka, guru, dan siswa

- waka, dapat mengakses semua halaman
- guru, hanya dapat melihat izin
- siswa, dapat mengajukan izin dan melihat izin yang di ajukan

pembatasan hak akses di lakukan di setiap halaman secara manual, contoh di halaman `pages/pengguna/index.php` terdapat kode
```php
if($user['rule']!='waka'){
    http_response_code(404);
    echo "404 Not Found";
    die();
}
```
yang artinya halaman pengguna hanya bisa di akses oleh user dengan rule waka, jika user yang login bukan waka maka akan di redirect ke halaman 404