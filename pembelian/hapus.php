<?php
require_once __DIR__ . "/../auth/staff_only.php";
include "../config.php";
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) { header("Location: index.php"); exit; }
$q=mysqli_query($conn,"SELECT id_mobil FROM pembelian WHERE id_pembelian=$id");
if(!$q || mysqli_num_rows($q)==0){ header("Location: index.php"); exit; }
$row=mysqli_fetch_assoc($q); $id_mobil=(int)$row['id_mobil'];
mysqli_begin_transaction($conn);
try{
    if(!mysqli_query($conn,"DELETE FROM detail_pembelian WHERE id_pembelian=$id")) throw new Exception(mysqli_error($conn));
    if(!mysqli_query($conn,"DELETE FROM pembelian WHERE id_pembelian=$id")) throw new Exception(mysqli_error($conn));
    $cek=mysqli_query($conn,"SELECT id_detail FROM detail_pemesanan WHERE id_mobil=$id_mobil LIMIT 1");
    if($cek && mysqli_num_rows($cek)==0){ mysqli_query($conn,"DELETE FROM mobil WHERE id_mobil=$id_mobil"); }
    mysqli_commit($conn); header("Location: index.php?status=hapus_berhasil"); exit;
}catch(Exception $e){ mysqli_rollback($conn); echo "<script>alert('Gagal menghapus pembelian: ".$e->getMessage()."'); window.location='index.php';</script>"; exit; }
?>
