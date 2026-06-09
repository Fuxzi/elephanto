<?php
require_once __DIR__ . "/../auth/staff_only.php";
include "../config.php";
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) { header("Location: index.php"); exit; }
$cek = mysqli_query($conn, "SELECT id_pemesanan FROM pemesanan WHERE id_pembeli=$id LIMIT 1");
if ($cek && mysqli_num_rows($cek)>0) { echo "<script>alert('Pembeli tidak bisa dihapus karena sudah memiliki pemesanan.'); window.location='index.php';</script>"; exit; }
mysqli_query($conn,"DELETE FROM dokumen_pembeli WHERE id_pembeli=$id");
if (!mysqli_query($conn,"DELETE FROM pembeli WHERE id_pembeli=$id")) { echo "<script>alert('Gagal menghapus pembeli: ".mysqli_error($conn)."'); window.location='index.php';</script>"; exit; }
header("Location: index.php?status=hapus_berhasil"); exit;
?>
