<?php
// guru/cetak-pdf.php (versi lengkap: menerima start/end via GET)
session_start();

// jangan tampilkan error ke browser; kita log saja
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php_error.log'); // pastikan folder logs ada & writable

// cek hak akses guru
if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'guru') {
    header("location: ../login.php");
    exit();
}

require '../config.php';
require __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

try {
    // bersihkan semua output buffer agar PDF tidak korup
    while (ob_get_level()) { ob_end_clean(); }

    // tambah memori/time limit jika perlu
    ini_set('memory_limit', '512M');
    set_time_limit(60);

    // baca parameter start & end dari GET (opsional)
    $start = isset($_GET['start']) ? trim($_GET['start']) : null;
    $end   = isset($_GET['end'])   ? trim($_GET['end'])   : null;

    // fungsi validasi tanggal: YYYY-MM-DD
    function is_valid_date($d) {
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $d) && (bool)strtotime($d);
    }

    $where = "1=1"; // default (semua data)
    if ($start && is_valid_date($start)) {
        $start_esc = mysqli_real_escape_string($koneksi, $start);
        $where .= " AND tanggal >= '$start_esc'";
    }
    if ($end && is_valid_date($end)) {
        $end_esc = mysqli_real_escape_string($koneksi, $end);
        $where .= " AND tanggal <= '$end_esc'";
    }

    // jika ingin membatasi hanya data siswa tertentu, misal guru melihat semua siswanya,
    // sesuaikan WHERE. Saat ini kode default menampilkan semua data yang cocok filter.
    // Kalau ingin hanya menampilkan data milik guru tertentu, ubah sesuai struktur DB.
    // Contoh: $where .= " AND id_guru = " . (int) $_SESSION['id_user'];

    // Query ambil data (urut descending)
    $sql = "SELECT l.*, u.nama_lengkap 
            FROM log_ibadah l
            LEFT JOIN users u ON l.id_user = u.id
            WHERE $where
            ORDER BY l.tanggal DESC";
    $res = mysqli_query($koneksi, $sql);
    if ($res === false) {
        error_log("[cetak-pdf] Query gagal: " . mysqli_error($koneksi));
        echo "Gagal mengambil data.";
        exit();
    }

    // setup DOMPDF
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true); // agar DOMPDF bisa load resource remote jika perlu
    $pdf = new Dompdf($options);

    // safe load logos (opsional)
    $logo_src = '';
    $logo_path = __DIR__ . "/../assets/logo ITM.png"; // sesuaikan nama file
    if (file_exists($logo_path) && is_readable($logo_path)) {
        $logo_data = @file_get_contents($logo_path);
        if ($logo_data !== false) $logo_src = 'data:image/png;base64,' . base64_encode($logo_data);
    }

    $logo2_src = '';
    $logo2_path = __DIR__ . "/../assets/PTI.png";
    if (file_exists($logo2_path) && is_readable($logo2_path)) {
        $logo2_data = @file_get_contents($logo2_path);
        if ($logo2_data !== false) $logo2_src = 'data:image/png;base64,' . base64_encode($logo2_data);
    }

    // informasi sekolah & user
    $nama_sekolah = "INSTITUT TEKNOLOGI MOJOSARI";
    $alamat_sekolah = "Dsn. Mojosari, Ds. Ngepeh, Kecamatan Loceret, Kabupaten Nganjuk, Jawa Timur 64471";
    $username = htmlspecialchars($_SESSION['username'] ?? 'Guru');
    $tanggal_cetak = date('d M Y');

    // build HTML (layout PDF)
    $html = '<!doctype html><html><head><meta charset="utf-8"/>';
    $html .= '<style>
        body{font-family: DejaVu Sans, sans-serif; font-size:12px}
        table{border-collapse:collapse;width:100%}
        th,td{border:1px solid #000;padding:6px;text-align:center}
        .header-table td{border:none;vertical-align:middle}
        .tabel-ttd td{border:none!important}
        .footer{font-size:10px;text-align:right;margin-top:10px}
    </style></head><body>';

    // header
    $html .= '<table class="header-table"><tr>';
    $html .= '<td style="width:15%;">' . ($logo_src? '<img src="'.$logo_src.'" style="width:80px">' : '') . '</td>';
    $html .= '<td style="width:70%;text-align:center"><h2>'.htmlspecialchars($nama_sekolah).'</h2><div>'.htmlspecialchars($alamat_sekolah).'</div></td>';
    $html .= '<td style="width:15%;text-align:right;">' . ($logo2_src? '<img src="'.$logo2_src.'" style="width:80px">' : '') . '</td>';
    $html .= '</tr></table><hr>';

    // title & filter info
    $html .= '<h3 style="text-align:center;margin-top:-10px">Laporan Ibadah</h3>';
    if ($start && is_valid_date($start)) $html .= '<p><b>Mulai:</b> ' . htmlspecialchars($start) . '</p>';
    if ($end && is_valid_date($end)) $html .= '<p><b>Sampai:</b> ' . htmlspecialchars($end) . '</p>';
    $html .= '<p><b>Dicetak oleh:</b> ' . $username . '</p>';

    // table header
    $html .= '<table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Subuh</th>
                        <th>Dzuhur</th>
                        <th>Ashar</th>
                        <th>Maghrib</th>
                        <th>Isya</th>
                        <th>Tilawah</th>
                        <th>Status</th>
                    </tr>
                </thead>
            <tbody>';

    // isi tabel
    while ($row = mysqli_fetch_assoc($res)) {
        $html .= '<tr>';
        $html .= '<td>'.htmlspecialchars(date('d M Y', strtotime($row['tanggal']))).'</td>';
        $html .= '<td>'.htmlspecialchars($row['nama_lengkap'] ?? '-').'</td>';
        $html .= '<td>'.($row['sholat_subuh'] ? '✔' : '✗').'</td>';
        $html .= '<td>'.($row['sholat_dzuhur'] ? '✔' : '✗').'</td>';
        $html .= '<td>'.($row['sholat_ashar'] ? '✔' : '✗').'</td>';
        $html .= '<td>'.($row['sholat_maghrib'] ? '✔' : '✗').'</td>';
        $html .= '<td>'.($row['sholat_isya'] ? '✔' : '✗').'</td>';
        $html .= '<td>'.htmlspecialchars($row['tilawah_halaman']).'</td>';
        $html .= '<td>'.htmlspecialchars($row['status_validasi']).'</td>';
        $html .= '</tr>';
    }

    $html .= '</tbody></table>';

    // tanda tangan
    $html .= '<table class="tabel-ttd" style="margin-top:40px"><tr>
        <td style="text-align:center"><br><br><br>.......................................<br><b>Guru Pembimbing</b></td>
        <td style="text-align:center"><br><br><br>.......................................<br><b>Orang Tua/Wali</b></td>
        </tr></table>';

    $html .= '<div class="footer">Dicetak pada: '.$tanggal_cetak.'</div>';
    $html .= '</body></html>';

    // render & stream
    $pdf->loadHtml($html);
    $pdf->setPaper('A4','landscape');
    $pdf->render();

    // pastikan buffer bersih
    while (ob_get_level()) { ob_end_clean(); }

    // tampilkan di browser (Attachment=>false). Set true untuk force-download.
    $pdf->stream("laporan-ibadah.pdf", ["Attachment" => false]);
    exit();

} catch (Throwable $e) {
    error_log("[cetak-pdf] Exception: " . $e->getMessage());
    echo "Terjadi kesalahan saat membuat PDF. Silakan cek log.";
    exit();
}
