<?php
require_once __DIR__ . "/../auth/staff_only.php";
include "../config.php";
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) { header("Location: index.php"); exit; }
if(!mysqli_query($conn,"DELETE FROM pembatalan WHERE id_pembatalan=$id")){ echo "<script>alert('Gagal menghapus pembatalan: ".mysqli_error($conn)."'); window.location='index.php';</script>"; exit; }
header("Location: index.php?status=hapus_berhasil"); exit;
?>
