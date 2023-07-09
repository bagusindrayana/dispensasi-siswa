<?php

include_once __DIR__ . "/../../config/database.php";
include_once __DIR__ . "/../../config/application.php";
include_once __DIR__ . "/../../library/cek_session.php";
include_once __DIR__ . "/../../actions/_models/Izin.php";
include_once __DIR__ . "/../../actions/_models/Pengguna.php";
$modelIzin = new Izin();
$izin = $modelIzin->rawQuery("SELECT izin.*,siswa.nomor,siswa.nama_lengkap as nama_siswa,guru.nama_lengkap as nama_guru,waka.nama_lengkap as nama_waka FROM izin 
INNER JOIN pengguna as siswa ON siswa.id = izin.siswa_id 
INNER JOIN pengguna as guru ON guru.id = izin.guru_id
INNER JOIN pengguna as waka ON waka.id = izin.waka_id
WHERE izin.id = " . $_GET['id'])->fetch();
$path = base_url().$logo_url;
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembar Ijin Siswa -
        <?= $izin['nomor'] ?> -
        <?= $izin['nama_siswa'] ?>
    </title>
    <style>
        table {
            border-collapse: collapse;
        }

        table tr td,
        table tr th {
            padding: 10px;
        }

        /* make stamp box with red border and rotation 30 degree */
        .stamp {
            position: relative;
            display: inline-block;
            /* padding: 0.5em 1em; */
            padding-top: 25px;
            border-style: double;
            border-width: 10px;
            text-transform: uppercase;
            font-weight: 700;
            border-radius: 0.5em;
            transform: rotate(-30deg);
            box-shadow: 0 0 0 0.2em #fff;
            width: 170px;
            height: 50px;
            text-align: center;
            align-items: center;
            align-content: center;
        }

        /* red stamp variant */
        .stamp.red {

            border-color: red;
            color: red;
        }

        /* green stamp variant */
        .stamp.green {

            border-color: green;
            color: green;
        }
    </style>
</head>

<body>
    <table style="width: 100%;">
        <thead>
            <tr style="border:1px solid black;text-align: center;">
                <th style="width: 30%;">
                    <img src="<?= $base64 ?>" alt="Logo Sekolah" style="width:75px;">
                </th>

                <th colspan="2">
                    <b>LEMBAR IJIN SISWA
                        <br>
                        SMK TI AIRLANGGA SAMARINDA</b>
                </th>
                <th style="width: 20%;"></th>
            </tr>

        </thead>
        <tbody style="border:1px solid black;">
            <tr>
                <td colspan="2" style="width: 50%;">
                    1. Nama Siswa
                </td>
                <td colspan="2">
                    :
                    <?= $izin['nama_siswa'] ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    2. Kelas/Jurusan
                </td>
                <td colspan="2">
                    :
                    <?= $izin['kelas_jurusan'] ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    3. Tanggal Ijin
                </td>
                <td colspan="2">
                    :
                    <?= $izin['tanggal'] ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    4. Waktu Ijin
                </td>
                <td colspan="2">
                    :
                    <?= date("H:i", strtotime($izin['waktu'])) ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    5. Keterangan Ijin
                </td>
                <td colspan="2">
                    : <u>
                        <?= $izin['keterangan'] ?>
                    </u>
                </td>
            </tr>
            <tr style="border:1px solid black;text-align: center;">
                <td colspan="2" style="border:1px solid black;">
                    <b>
                        MENGETAHUI
                    </b>
                </td>
                <td colspan="2" style="border:1px solid black;">
                    <b>
                        MENGETAHUI
                    </b>
                </td>

            </tr>
            <tr style="border:1px solid black;text-align: center;">
                <td colspan="2" style="border:1px solid black;">
                    <br>
                    <br>
                    <?php
                    if($izin['status'] == "disetujui"){
                        ?>
                        <div class="stamp green">Disetujui</div>
                        <?php
                    }else{
                        ?>
                        <div class="stamp red">Ditolak</div>
                        <?php
                    }
                    ?>
                    <br>
                    <br>
                </td>
                <td colspan="2" style="border:1px solid black;">
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                </td>

            </tr>
            <tr style="border:1px solid black;text-align: center;">
                <td colspan="2" style="border:1px solid black;">
                    <b>
                        <?= $izin['nama_waka'] ?>
                    </b>
                </td>
                <td colspan="2" style="border:1px solid black;">
                    <b>
                        <?= $izin['nama_guru'] ?>
                    </b>
                </td>

            </tr>
            <tr style="border:1px solid black;text-align: center;">
                <td colspan="2" style="border:1px solid black;">
                    Waka. Kesiswaan
                </td>
                <td colspan="2" style="border:1px solid black;">
                    Guru
                </td>

            </tr>
        </tbody>
    </table>
</body>

</html>