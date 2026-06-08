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
    $metode = mysqli_real_escape_string($conn, $_POST['metode']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    if (!in_array($metode, ['Tunai', 'Transfer']) || !in_array($status, ['Belum Dibayar', 'Lunas', 'Batal'])) {
        die("Input tidak valid.");
    }

    if (mysqli_query($conn, "UPDATE transaksi SET metode_pembayaran='$metode', status='$status' WHERE id_transaksi=$id")) {
        mysqli_query($conn, "UPDATE pemesanan SET status='$status' WHERE id_pemesanan=" . (int)$row['id_pemesanan']);
        header("Location: index.php?status=edit_berhasil");
        exit;
    } else {
        $error = "Gagal memperbarui transaksi: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="../assets/css/style.css">
    <meta charset="UTF-8">
    <title>Edit Transaksi</title>
</head>
<body>
<div class="header"><div class="inner"><h1>Sistem Penjualan Mobil Bekas</h1><div class="top-user"><?php if (isset($_SESSION['role'])) { ?><span class="user-badge"><?= htmlspecialchars($_SESSION['role']); ?><?php if (isset($_SESSION['username'])) { ?> / <?= htmlspecialchars($_SESSION['username']); ?><?php } ?></span><a class="logout-link" href="../logout.php">Logout</a><?php } ?></div></div></div>
<div class="container"><div class="card">
<h2>Edit Transaksi</h2>

<?php if (isset($error)) { ?>
    <p style="color:red;"><?= htmlspecialchars($error); ?></p>
<?php } ?>

<form method="POST">
    Metode Pembayaran:<br>
    <select name="metode" required>
        <option value="Tunai" <?= $row['metode_pembayaran'] == 'Tunai' ? 'selected' : ''; ?>>Tunai</option>
        <option value="Transfer" <?= $row['metode_pembayaran'] == 'Transfer' ? 'selected' : ''; ?>>Transfer</option>
    </select><br><br>

    Status:<br>
    <select name="status" required>
        <option value="Belum Dibayar" <?= $row['status'] == 'Belum Dibayar' ? 'selected' : ''; ?>>Belum Dibayar</option>
        <option value="Lunas" <?= $row['status'] == 'Lunas' ? 'selected' : ''; ?>>Lunas</option>
        <option value="Batal" <?= $row['status'] == 'Batal' ? 'selected' : ''; ?>>Batal</option>
    </select><br><br>

    <button type="submit" name="submit">Update</button>
    <a class="btn btn-secondary" href="index.php">Batal</a>
</form>
</div></div>
</body>
</html>
