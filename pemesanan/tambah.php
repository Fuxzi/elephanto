<?php
require_once __DIR__ . "/../auth/staff_only.php";
include "../config.php";

$pembeli = mysqli_query($conn, "SELECT * FROM pembeli ORDER BY nama ASC");
$mobil = mysqli_query($conn, "
SELECT 
    m.*,
    COALESCE(mm.merk, m.merk) AS nama_merk,
    COALESCE(mm.tipe, m.tipe) AS nama_tipe
FROM mobil m
LEFT JOIN master_mobil mm ON m.id_master = mm.id_master
WHERE m.status='Tersedia' AND m.stock > 0
ORDER BY nama_merk ASC, nama_tipe ASC
");

if (isset($_POST['submit'])) {
    $id_pembeli = (int) $_POST['id_pembeli'];
    $id_mobil = (int) $_POST['id_mobil'];
    $jumlah = (int) $_POST['jumlah'];
    $tgl = date('Y-m-d');

    if ($jumlah <= 0) {
        $error = "Jumlah harus lebih dari 0.";
    } else {
        mysqli_begin_transaction($conn);

        try {
            $m = mysqli_query($conn, "SELECT harga, stock FROM mobil WHERE id_mobil=$id_mobil AND status='Tersedia' FOR UPDATE");
            if (!$m || mysqli_num_rows($m) == 0) {
                throw new Exception("Mobil tidak ditemukan atau tidak tersedia.");
            }

            $row = mysqli_fetch_assoc($m);
            $harga = (float) $row['harga'];
            $stock = (int) $row['stock'];

            if ($jumlah > $stock) {
                throw new Exception("Jumlah pesanan melebihi stok mobil.");
            }

            $subtotal = $harga * $jumlah;

            $insertPemesanan = mysqli_query($conn, "INSERT INTO pemesanan (id_pembeli, tanggal, status) VALUES ($id_pembeli, '$tgl', 'Pending')");
            if (!$insertPemesanan) {
                throw new Exception(mysqli_error($conn));
            }

            $id_pemesanan = mysqli_insert_id($conn);

            $insertDetail = mysqli_query($conn, "INSERT INTO detail_pemesanan (id_pemesanan, id_mobil, jumlah, harga, subtotal) VALUES ($id_pemesanan, $id_mobil, $jumlah, $harga, $subtotal)");
            if (!$insertDetail) {
                throw new Exception(mysqli_error($conn));
            }

            mysqli_commit($conn);
            header("Location: index.php?status=tambah_berhasil");
            exit;
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $error = "Gagal membuat pemesanan: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="../assets/css/style.css">
    <meta charset="UTF-8">
    <title>Tambah Pemesanan</title>
</head>
<body>
<div class="header"><div class="inner"><h1>Sistem Penjualan Mobil Bekas</h1><div class="top-user"><?php if (isset($_SESSION['role'])) { ?><span class="user-badge"><?= htmlspecialchars($_SESSION['role']); ?><?php if (isset($_SESSION['username'])) { ?> / <?= htmlspecialchars($_SESSION['username']); ?><?php } ?></span><a class="logout-link" href="../logout.php">Logout</a><?php } ?></div></div></div>
<div class="container"><div class="card">
<h2>Tambah Pemesanan</h2>

<?php if (isset($error)) { ?>
    <p style="color:red;"><?= htmlspecialchars($error); ?></p>
<?php } ?>

<form method="POST">
    Pembeli:<br>
    <select name="id_pembeli" required>
        <option value="">-- Pilih Pembeli --</option>
        <?php while ($p = mysqli_fetch_assoc($pembeli)) { ?>
            <option value="<?= $p['id_pembeli']; ?>"><?= htmlspecialchars($p['nama']); ?></option>
        <?php } ?>
    </select><br><br>

    Mobil:<br>
    <select name="id_mobil" required>
        <option value="">-- Pilih Mobil --</option>
        <?php while ($m = mysqli_fetch_assoc($mobil)) { ?>
            <option value="<?= $m['id_mobil']; ?>">
                <?= htmlspecialchars($m['nama_merk'] . " " . $m['nama_tipe'] . " - Tahun " . $m['tahun'] . " - Stok: " . $m['stock'] . " - Rp " . number_format($m['harga'], 0, ',', '.')); ?>
            </option>
        <?php } ?>
    </select><br><br>

    Jumlah:<br>
    <input type="number" name="jumlah" value="1" min="1" required><br><br>

    <button type="submit" name="submit">Simpan Pemesanan</button>
    <a class="btn btn-secondary" href="index.php">Batal</a>
</form>
</div></div>
</body>
</html>
