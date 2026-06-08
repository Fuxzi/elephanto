<?php
require_once __DIR__ . "/../auth/staff_only.php";
include "../config.php";

$transaksi = mysqli_query($conn, "
SELECT 
    t.id_transaksi,
    t.id_pemesanan,
    pb.nama AS nama_pembeli,
    t.total
FROM transaksi t
JOIN pemesanan pm ON t.id_pemesanan = pm.id_pemesanan
JOIN pembeli pb ON pm.id_pembeli = pb.id_pembeli
LEFT JOIN pembatalan b ON t.id_transaksi = b.id_transaksi
WHERE t.status <> 'Batal' AND b.id_pembatalan IS NULL
ORDER BY t.id_transaksi DESC
");

if (isset($_POST['submit'])) {
    $id_transaksi = (int) $_POST['id_transaksi'];
    $alasan = mysqli_real_escape_string($conn, $_POST['alasan']);
    $tgl = date('Y-m-d');

    mysqli_begin_transaction($conn);

    try {
        $q = mysqli_query($conn, "SELECT id_pemesanan FROM transaksi WHERE id_transaksi=$id_transaksi FOR UPDATE");
        if (!$q || mysqli_num_rows($q) == 0) {
            throw new Exception("Transaksi tidak ditemukan.");
        }

        $tr = mysqli_fetch_assoc($q);
        $id_pemesanan = (int) $tr['id_pemesanan'];

        $insert = mysqli_query($conn, "INSERT INTO pembatalan (id_transaksi, tanggal_batal, alasan) VALUES ($id_transaksi, '$tgl', '$alasan')");
        if (!$insert) {
            throw new Exception(mysqli_error($conn));
        }

        mysqli_query($conn, "UPDATE transaksi SET status='Batal' WHERE id_transaksi=$id_transaksi");
        mysqli_query($conn, "UPDATE pemesanan SET status='Batal' WHERE id_pemesanan=$id_pemesanan");

        $details = mysqli_query($conn, "SELECT id_mobil, jumlah FROM detail_pemesanan WHERE id_pemesanan=$id_pemesanan");
        while ($d = mysqli_fetch_assoc($details)) {
            $id_mobil = (int) $d['id_mobil'];
            $jumlah = (int) $d['jumlah'];
            mysqli_query($conn, "UPDATE mobil SET stock=stock+$jumlah, status='Tersedia' WHERE id_mobil=$id_mobil");
        }

        mysqli_commit($conn);
        header("Location: index.php?status=tambah_berhasil");
        exit;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $error = "Gagal menambahkan pembatalan: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="../assets/css/style.css">
    <meta charset="UTF-8">
    <title>Tambah Pembatalan</title>
</head>
<body>
<div class="header"><div class="inner"><h1>Sistem Penjualan Mobil Bekas</h1><div class="top-user"><?php if (isset($_SESSION['role'])) { ?><span class="user-badge"><?= htmlspecialchars($_SESSION['role']); ?><?php if (isset($_SESSION['username'])) { ?> / <?= htmlspecialchars($_SESSION['username']); ?><?php } ?></span><a class="logout-link" href="../logout.php">Logout</a><?php } ?></div></div></div>
<div class="container"><div class="card">
<h2>Tambah Pembatalan</h2>

<?php if (isset($error)) { ?>
    <p style="color:red;"><?= htmlspecialchars($error); ?></p>
<?php } ?>

<form method="POST">
    Pilih Transaksi:<br>
    <select name="id_transaksi" required>
        <option value="">-- Pilih Transaksi --</option>
        <?php while ($t = mysqli_fetch_assoc($transaksi)) { ?>
            <option value="<?= $t['id_transaksi']; ?>">
                Transaksi #<?= $t['id_transaksi']; ?> - Pemesanan #<?= $t['id_pemesanan']; ?> - <?= htmlspecialchars($t['nama_pembeli']); ?> - Rp <?= number_format($t['total'], 0, ',', '.'); ?>
            </option>
        <?php } ?>
    </select><br><br>

    Alasan Pembatalan:<br>
    <textarea name="alasan" required></textarea><br><br>

    <button type="submit" name="submit">Simpan Pembatalan</button>
    <a class="btn btn-secondary" href="index.php">Batal</a>
</form>
</div></div>
</body>
</html>
