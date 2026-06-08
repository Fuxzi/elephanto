<?php
require_once __DIR__ . "/../auth/staff_only.php";
include "../config.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$q = mysqli_query($conn, "SELECT * FROM pemesanan WHERE id_pemesanan=$id");
if (!$q || mysqli_num_rows($q) == 0) {
    die("Data pemesanan tidak ditemukan.");
}
$row = mysqli_fetch_assoc($q);

if (isset($_POST['submit'])) {
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    if (!in_array($status, ['Pending', 'Lunas', 'Batal'])) {
        die("Status tidak valid.");
    }

    if (mysqli_query($conn, "UPDATE pemesanan SET status='$status' WHERE id_pemesanan=$id")) {
        header("Location: index.php?status=edit_berhasil");
        exit;
    } else {
        $error = "Gagal memperbarui pemesanan: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="../assets/css/style.css">
    <meta charset="UTF-8">
    <title>Edit Pemesanan</title>
</head>
<body>
<div class="header"><div class="inner"><h1>Sistem Penjualan Mobil Bekas</h1><div class="top-user"><?php if (isset($_SESSION['role'])) { ?><span class="user-badge"><?= htmlspecialchars($_SESSION['role']); ?><?php if (isset($_SESSION['username'])) { ?> / <?= htmlspecialchars($_SESSION['username']); ?><?php } ?></span><a class="logout-link" href="../logout.php">Logout</a><?php } ?></div></div></div>
<div class="container"><div class="card">
<h2>Edit Pemesanan</h2>

<?php if (isset($error)) { ?>
    <p style="color:red;"><?= htmlspecialchars($error); ?></p>
<?php } ?>

<form method="POST">
    Status:<br>
    <select name="status" required>
        <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
        <option value="Lunas" <?= $row['status'] == 'Lunas' ? 'selected' : ''; ?>>Lunas</option>
        <option value="Batal" <?= $row['status'] == 'Batal' ? 'selected' : ''; ?>>Batal</option>
    </select><br><br>

    <button type="submit" name="submit">Update</button>
    <a class="btn btn-secondary" href="index.php">Batal</a>
</form>
</div></div>
</body>
</html>
