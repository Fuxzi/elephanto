<?php
require_once __DIR__ . "/../auth/admin_only.php";
include "../config.php";
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) { header("Location: index.php"); exit; }
$cek1 = mysqli_query($conn, "SELECT id_detail FROM detail_pemesanan WHERE id_mobil=$id LIMIT 1");
$cek2 = mysqli_query($conn, "SELECT id_pembelian FROM pembelian WHERE id_mobil=$id LIMIT 1");
if (($cek1 && mysqli_num_rows($cek1)>0) || ($cek2 && mysqli_num_rows($cek2)>0)) {
    echo "<script>alert('Mobil tidak bisa dihapus karena sudah terhubung dengan pemesanan atau pembelian.'); window.location='index.php';</script>"; exit;
}
if (!mysqli_query($conn,"DELETE FROM mobil WHERE id_mobil=$id")) {
    echo "<script>alert('Gagal menghapus mobil: " . mysqli_error($conn) . "'); window.location='index.php';</script>"; exit;
}
header("Location: index.php?status=hapus_berhasil"); exit;
?>
