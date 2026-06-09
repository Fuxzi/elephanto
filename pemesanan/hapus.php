<?php
require_once __DIR__ . "/../auth/staff_only.php";
include "../config.php";
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) { header("Location: index.php"); exit; }
$cek=mysqli_query($conn,"SELECT id_transaksi FROM transaksi WHERE id_pemesanan=$id LIMIT 1");
if($cek && mysqli_num_rows($cek)>0){ echo "<script>alert('Pemesanan tidak bisa dihapus karena sudah memiliki transaksi.'); window.location='index.php';</script>"; exit; }
mysqli_query($conn,"DELETE FROM detail_pemesanan WHERE id_pemesanan=$id");
if(!mysqli_query($conn,"DELETE FROM pemesanan WHERE id_pemesanan=$id")){ echo "<script>alert('Gagal menghapus pemesanan: ".mysqli_error($conn)."'); window.location='index.php';</script>"; exit; }
header("Location: index.php?status=hapus_berhasil"); exit;
?>
