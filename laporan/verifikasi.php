<?php
require_once __DIR__ . "/../auth/admin_only.php";
include "../config.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$q = mysqli_query($conn, "
SELECT 
    t.id_transaksi,
    t.total,
    t.metode_pembayaran,
    t.status,
    t.status_verifikasi,
    t.catatan_verifikasi,
    t.tanggal_verifikasi,
    p.tanggal,
    pb.nama AS nama_pembeli
FROM transaksi t
JOIN pemesanan p ON t.id_pemesanan = p.id_pemesanan
JOIN pembeli pb ON p.id_pembeli = pb.id_pembeli
WHERE t.id_transaksi=$id
");

if (!$q || mysqli_num_rows($q) == 0) {
    die("Data transaksi tidak ditemukan.");
}

$row = mysqli_fetch_assoc($q);

if (isset($_POST['submit'])) {
    $status_verifikasi = mysqli_real_escape_string($conn, $_POST['status_verifikasi']);
    $catatan = mysqli_real_escape_string($conn, $_POST['catatan_verifikasi']);
    $tanggal_verifikasi = $status_verifikasi === 'Diverifikasi' ? date('Y-m-d') : null;

    if (!in_array($status_verifikasi, ['Belum Diverifikasi', 'Diverifikasi'])) {
        die("Status verifikasi tidak valid.");
    }

    if ($tanggal_verifikasi) {
        $sql = "
            UPDATE transaksi
            SET status_verifikasi='$status_verifikasi',
                catatan_verifikasi='$catatan',
                tanggal_verifikasi='$tanggal_verifikasi'
            WHERE id_transaksi=$id
        ";
    } else {
        $sql = "
            UPDATE transaksi
            SET status_verifikasi='$status_verifikasi',
                catatan_verifikasi='$catatan',
                tanggal_verifikasi=NULL
            WHERE id_transaksi=$id
        ";
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: transaksi.php?status=verifikasi_berhasil");
        exit;
    } else {
        $error = "Gagal memperbarui verifikasi laporan: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Laporan Transaksi</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="header"><div class="inner"><h1>Sistem Penjualan Mobil Bekas</h1><div class="top-user"><?php if (isset($_SESSION['role'])) { ?><span class="user-badge"><?= htmlspecialchars($_SESSION['role']); ?><?php if (isset($_SESSION['username'])) { ?> / <?= htmlspecialchars($_SESSION['username']); ?><?php } ?></span><a class="logout-link" href="../logout.php">Logout</a><?php } ?></div></div></div>
<div class="container"><div class="card">
<h2>Verifikasi Laporan Transaksi</h2>
<p class="subtitle">Admin mengelola laporan dengan memberi status dan catatan verifikasi.</p>

<?php if (isset($error)) { ?>
    <div class="alert-error"><?= htmlspecialchars($error); ?></div>
<?php } ?>

<table>
    <tr><th>ID Transaksi</th><td><?= htmlspecialchars($row['id_transaksi']); ?></td></tr>
    <tr><th>Tanggal Transaksi</th><td><?= htmlspecialchars($row['tanggal']); ?></td></tr>
    <tr><th>Pembeli</th><td><?= htmlspecialchars($row['nama_pembeli']); ?></td></tr>
    <tr><th>Total</th><td>Rp <?= number_format($row['total'], 0, ',', '.'); ?></td></tr>
    <tr><th>Metode Pembayaran</th><td><?= htmlspecialchars($row['metode_pembayaran']); ?></td></tr>
    <tr><th>Status Transaksi</th><td><?= htmlspecialchars($row['status']); ?></td></tr>
</table>

<br>

<form method="POST">
    <div class="form-group">
        <label>Status Verifikasi</label>
        <select name="status_verifikasi" required>
            <option value="Belum Diverifikasi" <?= $row['status_verifikasi'] == 'Belum Diverifikasi' ? 'selected' : ''; ?>>Belum Diverifikasi</option>
            <option value="Diverifikasi" <?= $row['status_verifikasi'] == 'Diverifikasi' ? 'selected' : ''; ?>>Diverifikasi</option>
        </select>
    </div>

    <div class="form-group">
        <label>Catatan Verifikasi</label>
        <textarea name="catatan_verifikasi"><?= htmlspecialchars($row['catatan_verifikasi']); ?></textarea>
    </div>

    <div class="form-actions">
        <button type="submit" name="submit">Simpan Verifikasi</button>
        <a class="btn btn-secondary" href="transaksi.php">Batal</a>
    </div>
</form>
</div></div>
</body>
</html>
