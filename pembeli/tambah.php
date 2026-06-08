<?php
require_once __DIR__ . "/../auth/staff_only.php";
include "../config.php";

if (isset($_POST['submit'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);

    $sql = "INSERT INTO pembeli (nama, alamat, no_hp) VALUES ('$nama', '$alamat', '$no_hp')";

    if (mysqli_query($conn, $sql)) {
        header("Location: index.php?status=tambah_berhasil");
        exit;
    } else {
        $error = "Gagal menambah pembeli: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="../assets/css/style.css">
    <meta charset="UTF-8">
    <title>Tambah Pembeli</title>
</head>
<body>
<div class="header"><div class="inner"><h1>Sistem Penjualan Mobil Bekas</h1><div class="top-user"><?php if (isset($_SESSION['role'])) { ?><span class="user-badge"><?= htmlspecialchars($_SESSION['role']); ?><?php if (isset($_SESSION['username'])) { ?> / <?= htmlspecialchars($_SESSION['username']); ?><?php } ?></span><a class="logout-link" href="../logout.php">Logout</a><?php } ?></div></div></div>
<div class="container"><div class="card">
<h2>Tambah Pembeli</h2>

<?php if (isset($error)) { ?>
    <p style="color:red;"><?= htmlspecialchars($error); ?></p>
<?php } ?>

<form method="POST">
    Nama:<br>
    <input name="nama" type="text" required><br><br>

    Alamat:<br>
    <textarea name="alamat"></textarea><br><br>

    No HP:<br>
    <input name="no_hp" type="text"><br><br>

    <button type="submit" name="submit">Simpan</button>
    <a class="btn btn-secondary" href="index.php">Batal</a>
</form>
</div></div>
</body>
</html>
