<?php
require_once __DIR__ . "/../auth/staff_only.php";
include "../config.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$q = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_transaksi=$id");
if (!$q || mysqli_num_rows($q) == 0) {
    die("Data transaksi tidak ditemukan.");
}
$row = mysqli_fetch_assoc($q);

if (isset($_POST['submit'])) {
    $dp_booking = (float) $_POST['dp_booking'];
    $metode = mysqli_real_escape_string($conn, $_POST['metode_pembayaran']);
    $status_manual = mysqli_real_escape_string($conn, $_POST['status']);

    mysqli_begin_transaction($conn);

    try {
        $total = (float) $row['total'];

        if ($dp_booking < 500000) {
            throw new Exception("DP / Booking Fee minimal Rp500.000.");
        }

        if ($dp_booking > $total) {
            throw new Exception("DP / Booking Fee tidak boleh melebihi total transaksi.");
        }

        $sisa_pembayaran = $total - $dp_booking;

        if ($status_manual === 'Batal') {
            $status = 'Batal';
        } else {
            $status = ($sisa_pembayaran <= 0) ? "Lunas" : "Belum Dibayar";
        }

        $sql = "
            UPDATE transaksi 
            SET dp_booking=$dp_booking,
                sisa_pembayaran=$sisa_pembayaran,
                metode_pembayaran='$metode',
                status='$status'
            WHERE id_transaksi=$id
        ";

        if (!mysqli_query($conn, $sql)) {
            throw new Exception(mysqli_error($conn));
        }

        mysqli_query($conn, "UPDATE pemesanan SET status='$status' WHERE id_pemesanan=" . (int)$row['id_pemesanan']);

        if ($status === 'Lunas') {
            $id_pemesanan = (int) $row['id_pemesanan'];
            $details = mysqli_query($conn, "SELECT id_mobil, jumlah FROM detail_pemesanan WHERE id_pemesanan=$id_pemesanan");

            while ($d = mysqli_fetch_assoc($details)) {
                $id_mobil = (int) $d['id_mobil'];
                $jumlah = (int) $d['jumlah'];

                $stok = mysqli_query($conn, "SELECT stock FROM mobil WHERE id_mobil=$id_mobil FOR UPDATE");
                $m = mysqli_fetch_assoc($stok);
                $stock_sekarang = (int) $m['stock'];

                if ($stock_sekarang > 0) {
                    $stock_baru = max(0, $stock_sekarang - $jumlah);
                    $status_mobil = $stock_baru <= 0 ? "Terjual" : "Tersedia";
                    mysqli_query($conn, "UPDATE mobil SET stock=$stock_baru, status='$status_mobil' WHERE id_mobil=$id_mobil");
                }
            }
        }

        mysqli_commit($conn);
        header("Location: index.php?status=edit_berhasil");
        exit;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $error = "Gagal memperbarui transaksi: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Transaksi</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="header"><div class="inner"><h1>Sistem Penjualan Mobil Bekas</h1><div class="top-user"><?php if (isset($_SESSION['role'])) { ?><span class="user-badge"><?= htmlspecialchars($_SESSION['role']); ?><?php if (isset($_SESSION['username'])) { ?> / <?= htmlspecialchars($_SESSION['username']); ?><?php } ?></span><a class="logout-link" href="../logout.php">Logout</a><?php } ?></div></div></div>
<div class="container"><div class="card">
<h2>Edit Transaksi</h2>
<p class="subtitle">Perbarui DP / Booking Fee dan status transaksi.</p>

<?php if (isset($error)) { ?>
    <div class="alert-error"><?= htmlspecialchars($error); ?></div>
<?php } ?>

<form method="POST">
    <div class="form-group">
        <label>Total Transaksi</label>
        <input type="number" value="<?= htmlspecialchars($row['total']); ?>" readonly>
    </div>

    <div class="form-group">
        <label>DP / Booking Fee</label>
        <input type="number" name="dp_booking" step="0.01" min="500000" value="<?= htmlspecialchars($row['dp_booking']); ?>" required>
    </div>

    <div class="form-group">
        <label>Sisa Pembayaran Saat Ini</label>
        <input type="number" value="<?= htmlspecialchars($row['sisa_pembayaran']); ?>" readonly>
    </div>

    <div class="form-group">
        <label>Metode Pembayaran</label>
        <select name="metode_pembayaran" required>
            <option value="Tunai" <?= $row['metode_pembayaran'] == 'Tunai' ? 'selected' : ''; ?>>Tunai</option>
            <option value="Transfer" <?= $row['metode_pembayaran'] == 'Transfer' ? 'selected' : ''; ?>>Transfer</option>
        </select>
    </div>

    <div class="form-group">
        <label>Status</label>
        <select name="status" required>
            <option value="Belum Dibayar" <?= $row['status'] == 'Belum Dibayar' ? 'selected' : ''; ?>>Belum Dibayar</option>
            <option value="Lunas" <?= $row['status'] == 'Lunas' ? 'selected' : ''; ?>>Lunas</option>
            <option value="Batal" <?= $row['status'] == 'Batal' ? 'selected' : ''; ?>>Batal</option>
        </select>
    </div>

    <div class="form-actions">
        <button type="submit" name="submit">Update</button>
        <a class="btn btn-secondary" href="index.php">Batal</a>
    </div>
</form>
</div></div>
</body>
</html>
