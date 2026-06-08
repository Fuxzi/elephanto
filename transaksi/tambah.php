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
ORDER BY pm.id_pemesanan DESC
");

if (isset($_POST['submit'])) {
    $id_pemesanan = (int) $_POST['id_pemesanan'];
    $metode = mysqli_real_escape_string($conn, $_POST['metode']);

    if (!in_array($metode, ['Tunai', 'Transfer'])) {
        die("Metode pembayaran tidak valid.");
    }

    mysqli_begin_transaction($conn);

    try {
        $sub = mysqli_query($conn, "SELECT SUM(subtotal) AS total FROM detail_pemesanan WHERE id_pemesanan=$id_pemesanan");
        if (!$sub) {
            throw new Exception(mysqli_error($conn));
        }

        $row = mysqli_fetch_assoc($sub);
        $total = (float) $row['total'];

        if ($total <= 0) {
            throw new Exception("Total transaksi tidak valid. Pastikan detail pemesanan sudah ada.");
        }

        $cek = mysqli_query($conn, "SELECT id_transaksi FROM transaksi WHERE id_pemesanan=$id_pemesanan AND status <> 'Batal'");
        if ($cek && mysqli_num_rows($cek) > 0) {
            throw new Exception("Pemesanan ini sudah memiliki transaksi aktif.");
        }

        $insert = mysqli_query($conn, "INSERT INTO transaksi (id_pemesanan, total, metode_pembayaran, status) VALUES ($id_pemesanan, $total, '$metode', 'Lunas')");
        if (!$insert) {
            throw new Exception(mysqli_error($conn));
        }

        $updatePemesanan = mysqli_query($conn, "UPDATE pemesanan SET status='Lunas' WHERE id_pemesanan=$id_pemesanan");
        if (!$updatePemesanan) {
            throw new Exception(mysqli_error($conn));
        }

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
    <link rel="stylesheet" href="../assets/css/style.css">
    <meta charset="UTF-8">
    <title>Tambah Transaksi</title>
</head>
<body>
<div class="header"><div class="inner"><h1>Sistem Penjualan Mobil Bekas</h1><div class="top-user"><?php if (isset($_SESSION['role'])) { ?><span class="user-badge"><?= htmlspecialchars($_SESSION['role']); ?><?php if (isset($_SESSION['username'])) { ?> / <?= htmlspecialchars($_SESSION['username']); ?><?php } ?></span><a class="logout-link" href="../logout.php">Logout</a><?php } ?></div></div></div>
<div class="container"><div class="card">
<h2>Tambah Transaksi</h2>

<?php if (isset($error)) { ?>
    <p style="color:red;"><?= htmlspecialchars($error); ?></p>
<?php } ?>

<form method="POST">
    Pilih Pemesanan:<br>
    <select name="id_pemesanan" required>
        <option value="">-- Pilih Pemesanan Pending --</option>
        <?php while ($p = mysqli_fetch_assoc($pemesanan)) { ?>
            <option value="<?= $p['id_pemesanan']; ?>">
                ID <?= $p['id_pemesanan']; ?> - <?= htmlspecialchars($p['nama_pembeli']); ?> - Rp <?= number_format($p['total'], 0, ',', '.'); ?>
            </option>
        <?php } ?>
    </select><br><br>

    Metode Pembayaran:<br>
    <select name="metode" required>
        <option value="Tunai">Tunai</option>
        <option value="Transfer">Transfer</option>
    </select><br><br>

    <button type="submit" name="submit">Simpan Transaksi</button>
    <a class="btn btn-secondary" href="index.php">Batal</a>
</form>
</div></div>
</body>
</html>
