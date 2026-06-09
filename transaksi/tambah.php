<?php
require_once __DIR__ . "/../auth/staff_only.php";
include "../config.php";

$pemesanan = mysqli_query($conn, "
SELECT 
    pm.id_pemesanan,
    pm.tanggal,
    pb.nama AS nama_pembeli,
    COALESCE(SUM(dp.subtotal), 0) AS total
FROM pemesanan pm
JOIN pembeli pb ON pm.id_pembeli = pb.id_pembeli
LEFT JOIN detail_pemesanan dp ON pm.id_pemesanan = dp.id_pemesanan
LEFT JOIN transaksi t ON pm.id_pemesanan = t.id_pemesanan AND t.status <> 'Batal'
WHERE pm.status='Pending' AND t.id_transaksi IS NULL
GROUP BY pm.id_pemesanan, pm.tanggal, pb.nama
ORDER BY pm.id_pemesanan ASC
");

if (isset($_POST['submit'])) {
    $id_pemesanan = (int) $_POST['id_pemesanan'];
    $dp_booking = (float) $_POST['dp_booking'];
    $metode = mysqli_real_escape_string($conn, $_POST['metode_pembayaran']);

    mysqli_begin_transaction($conn);

    try {
        if ($dp_booking < 500000) {
            throw new Exception("DP / Booking Fee minimal Rp500.000.");
        }

        $sub = mysqli_query($conn, "SELECT SUM(subtotal) AS total FROM detail_pemesanan WHERE id_pemesanan=$id_pemesanan");
        if (!$sub) {
            throw new Exception(mysqli_error($conn));
        }

        $rowTotal = mysqli_fetch_assoc($sub);
        $total = (float) $rowTotal['total'];

        if ($total <= 0) {
            throw new Exception("Total transaksi tidak valid. Pastikan detail pemesanan tersedia.");
        }

        if ($dp_booking > $total) {
            throw new Exception("DP / Booking Fee tidak boleh melebihi total transaksi.");
        }

        $sisa_pembayaran = $total - $dp_booking;
        $status = ($sisa_pembayaran <= 0) ? "Lunas" : "Belum Dibayar";

        $cek = mysqli_query($conn, "SELECT id_transaksi FROM transaksi WHERE id_pemesanan=$id_pemesanan AND status <> 'Batal'");
        if ($cek && mysqli_num_rows($cek) > 0) {
            throw new Exception("Pemesanan ini sudah memiliki transaksi aktif.");
        }

        $insert = mysqli_query($conn, "
            INSERT INTO transaksi 
            (id_pemesanan, total, dp_booking, sisa_pembayaran, metode_pembayaran, status)
            VALUES
            ($id_pemesanan, $total, $dp_booking, $sisa_pembayaran, '$metode', '$status')
        ");

        if (!$insert) {
            throw new Exception(mysqli_error($conn));
        }

        if ($status === 'Lunas') {
            mysqli_query($conn, "UPDATE pemesanan SET status='Lunas' WHERE id_pemesanan=$id_pemesanan");

            $details = mysqli_query($conn, "SELECT id_mobil, jumlah FROM detail_pemesanan WHERE id_pemesanan=$id_pemesanan");
            while ($d = mysqli_fetch_assoc($details)) {
                $id_mobil = (int) $d['id_mobil'];
                $jumlah = (int) $d['jumlah'];

                $stok = mysqli_query($conn, "SELECT stock FROM mobil WHERE id_mobil=$id_mobil FOR UPDATE");
                $m = mysqli_fetch_assoc($stok);
                $stock_sekarang = (int) $m['stock'];
                $stock_baru = max(0, $stock_sekarang - $jumlah);
                $status_mobil = $stock_baru <= 0 ? "Terjual" : "Tersedia";

                mysqli_query($conn, "UPDATE mobil SET stock=$stock_baru, status='$status_mobil' WHERE id_mobil=$id_mobil");
            }
        }

        mysqli_commit($conn);
        header("Location: index.php?status=tambah_berhasil");
        exit;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $error = "Gagal membuat transaksi: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Transaksi</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="header"><div class="inner"><h1>Sistem Penjualan Mobil Bekas</h1><div class="top-user"><?php if (isset($_SESSION['role'])) { ?><span class="user-badge"><?= htmlspecialchars($_SESSION['role']); ?><?php if (isset($_SESSION['username'])) { ?> / <?= htmlspecialchars($_SESSION['username']); ?><?php } ?></span><a class="logout-link" href="../logout.php">Logout</a><?php } ?></div></div></div>
<div class="container"><div class="card">
<h2>Tambah Transaksi</h2>
<p class="subtitle">Input DP / Booking Fee minimal Rp500.000.</p>

<?php if (isset($error)) { ?>
    <div class="alert-error"><?= htmlspecialchars($error); ?></div>
<?php } ?>

<form method="POST">
    <div class="form-group">
        <label>Pemesanan</label>
        <select name="id_pemesanan" required>
            <option value="">-- Pilih Pemesanan Pending --</option>
            <?php while ($p = mysqli_fetch_assoc($pemesanan)) { ?>
                <option value="<?= $p['id_pemesanan']; ?>">
                    ID <?= $p['id_pemesanan']; ?> - <?= htmlspecialchars($p['nama_pembeli']); ?> - Rp <?= number_format($p['total'], 0, ',', '.'); ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <div class="form-group">
        <label>DP / Booking Fee</label>
        <input type="number" name="dp_booking" step="0.01" min="500000" value="500000" required>
    </div>

    <div class="form-group">
        <label>Metode Pembayaran</label>
        <select name="metode_pembayaran" required>
            <option value="Tunai">Tunai</option>
            <option value="Transfer">Transfer</option>
        </select>
    </div>

    <div class="form-actions">
        <button type="submit" name="submit">Simpan Transaksi</button>
        <a class="btn btn-secondary" href="index.php">Batal</a>
    </div>
</form>
</div></div>
</body>
</html>
