<?php
session_start();
if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'siswa') {
    header("location: ../login.php");
    exit();
}

require '../config.php';
require '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// --- FETCH DATA USER ---
$id_user = $_SESSION['id_user'];

$query = "SELECT * FROM log_ibadah WHERE id_user = $id_user ORDER BY tanggal DESC";
$hasil_log = mysqli_query($koneksi, $query);

// --- DOMPDF SETTING ---
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true); // penting untuk load logo
$pdf = new Dompdf($options);

// --- HEADER TEXT ---
$nama_sekolah = "INSTITUT TEKNOLOGI MOJOSARI";
$alamat_sekolah = "Dsn. Mojosari, Ds. Ngepeh, Kecamatan Loceret, Kabupaten Nganjuk, Jawa Timur 64471";
$nama_siswa = $_SESSION['username'];
$tanggal_cetak = date('d M Y');

// --- LOGO SEKOLAH ---
// Pastikan kamu punya file: ../assets/logo.png
$logo_path = __DIR__ . "/../assets/logo ITM.png";
$logo_data = base64_encode(file_get_contents($logo_path));
$logo_src = 'data:image/png;base64,' . $logo_data;

// LOGO KEDUA
$logo2_path = __DIR__ . "/../assets/PTI.png";
$logo2_data = base64_encode(file_get_contents($logo2_path));
$logo2_src = 'data:image/png;base64,' . $logo2_data;


// --- MULAI HTML ---
$html = '
<style>
    body {
        font-family: DejaVu Sans, sans-serif; font-size: 12px; 
    }
    table {
        border-collapse: collapse; margin-top: 10px;
    }
    th, td { 
        border: 1px solid #000; padding: 6px; text-align: center; 
    }
    .header-table td { 
        border: none; 
    }
    .footer { 
        width: 100%; text-align: right; font-size: 10px; 
    }
    .tabel-ttd, .tabel-ttd td, .tabel-ttd th {
        border: none !important; 
    }

</style>

<!-- HEADER -->
<table class="header-table" width="100%">
<tr>
    <td width="15%" style="text-align:left;">
        <img src="'.$logo_src.'" style="width:80px;">
    </td>

    <td width="70%" style="text-align:center;">
        <h2>'.$nama_sekolah.'</h2>
        <div>'.$alamat_sekolah.'</div>
    </td>

    <td width="15%" style="text-align:right;">
        <img src="'.$logo2_src.'" style="width:80px;">
    </td>
</tr>
</table>

<hr>

<h3 style="text-align:center; margin-top:-10px;">Laporan Ibadah Harian</h3>
<p><b>Nama Siswa:</b> '.$nama_siswa.'</p>

<table width="100%">
<thead>
<tr>
    <th>Tanggal</th>
    <th>Subuh</th>
    <th>Dzuhur</th>
    <th>Ashar</th>
    <th>Maghrib</th>
    <th>Isya</th>
    <th>Tilawah</th>
    <th>Status</th>
</tr>
</thead>
<tbody>
';


while ($row = mysqli_fetch_assoc($hasil_log)) {

    $subuh   = $row['sholat_subuh'] ? "✔" : "✗";
    $dzuhur  = $row['sholat_dzuhur'] ? "✔" : "✗";
    $ashar   = $row['sholat_ashar'] ? "✔" : "✗";
    $maghrib = $row['sholat_maghrib'] ? "✔" : "✗";
    $isya    = $row['sholat_isya'] ? "✔" : "✗";

    $html .= '
    <tr>
        <td>'.date('d M Y', strtotime($row['tanggal'])).'</td>
        <td>'.$subuh.'</td>
        <td>'.$dzuhur.'</td>
        <td>'.$ashar.'</td>
        <td>'.$maghrib.'</td>
        <td>'.$isya.'</td>
        <td>'.$row['tilawah_halaman'].'</td>
        <td>'.$row['status_validasi'].'</td>
    </tr>
    ';
}

$html .= '</tbody></table><br><br><br>';

$html .= '
<table class="tabel-ttd" style="width: 100%; margin-top: 40px;">
    <tr>
        <td style="text-align: center;">
            <br><br><br>
            ....................................... <br>
            <b>Guru Pembimbing</b>
        </td>
        <td style="text-align: center;">
            <br><br><br>
            ....................................... <br>
            <b>Orang Tua/Wali</b>
        </td>
    </tr>
</table>
';

$html .= '
<div class="footer">
    Dicetak pada: '.$tanggal_cetak.'
</div>
';

$pdf->loadHtml($html);
$pdf->setPaper('A4', 'landscape');
$pdf->render();

$pdf->stream("laporan-ibadah.pdf", ["Attachment" => false]);
