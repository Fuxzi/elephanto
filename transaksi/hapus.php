<?php
require_once __DIR__ . "/../auth/staff_only.php";
include "../config.php";
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) { header("Location: index.php"); exit; }
$cek=mysqli_query($conn,"SELECT id_pembatalan FROM pembatalan WHERE id_transaksi=$id LIMIT 1");
if($cek && mysqli_num_rows($cek)>0){ echo "<script>alert('Transaksi tidak bisa dihapus karena sudah memiliki data pembatalan.'); window.location='index.php';</script>"; exit; }
if(!mysqli_query($conn,"DELETE FROM transaksi WHERE id_transaksi=$id")){ echo "<script>alert('Gagal menghapus transaksi: ".mysqli_error($conn)."'); window.location='index.php';</script>"; exit; }
header("Location: index.php?status=hapus_berhasil"); exit;
?>
