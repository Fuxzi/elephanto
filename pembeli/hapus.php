<?php
require_once __DIR__ . "/../auth/staff_only.php";
include "../config.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: index.php?status=id_tidak_valid");
    exit;
}

if (mysqli_query($conn, "DELETE FROM pembeli WHERE id_pembeli=$id")) {
    header("Location: index.php?status=hapus_berhasil");
    exit;
}

echo "Gagal menghapus pembeli: " . mysqli_error($conn);
?>
