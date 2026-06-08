<?php
require_once __DIR__ . "/../auth/staff_only.php";
include "../config.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$p = mysqli_query($conn, "SELECT * FROM pembeli WHERE id_pembeli=$id");
if (!$p || mysqli_num_rows($p) == 0) {
    die("Data pembeli tidak ditemukan.");
}
$row = mysqli_fetch_assoc($p);

if (isset($_POST['submit'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);

    $sql = "UPDATE pembeli SET nama='$nama', alamat='$alamat', no_hp='$no_hp' WHERE id_pembeli=$id";

    if (mysqli_query($conn, $sql)) {
        header("Location: index.php?status=edit_berhasil");
        exit;
    } else {
        $error = "Gagal memperbarui pembeli: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="../assets/css/style.css">
    <meta charset="UTF-8">
    <title>Edit Pembeli</title>
</head>
<body>
<div class="header"><div class="inner"><h1>Sistem Penjualan Mobil Bekas</h1><div class="top-user"><?php if (isset($_SESSION['role'])) { ?><span class="user-badge"><?= htmlspecialchars($_SESSION['role']); ?><?php if (isset($_SESSION['username'])) { ?> / <?= htmlspecialchars($_SESSION['username']); ?><?php } ?></span><a class="logout-link" href="../logout.php">Logout</a><?php } ?></div></div></div>
<div class="container"><div class="card">
<h2>Edit Pembeli</h2>

<?php if (isset($error)) { ?>
    <p style="color:red;"><?= htmlspecialchars($error); ?></p>
<?php } ?>

<form method="POST">
    Nama:<br>
    <input name="nama" type="text" value="<?= htmlspecialchars($row['nama']); ?>" required><br><br>

    Alamat:<br>
    <textarea name="alamat"><?= htmlspecialchars($row['alamat']); ?></textarea><br><br>

    No HP:<br>
    <input name="no_hp" type="text" value="<?= htmlspecialchars($row['no_hp']); ?>"><br><br>

    <button type="submit" name="submit">Update</button>
    <a class="btn btn-secondary" href="index.php">Batal</a>
</form>
</div></div>
</body>
</html>
