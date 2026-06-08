<?php
require_once __DIR__ . "/../auth/staff_only.php";
include "../config.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: index.php?status=id_tidak_valid");
    exit;
}

mysqli_begin_transaction($conn);

try {
    $q = mysqli_query($conn, "SELECT status FROM pemesanan WHERE id_pemesanan=$id FOR UPDATE");
    if (!$q || mysqli_num_rows($q) == 0) {
        throw new Exception("Data pemesanan tidak ditemukan.");
    }

    mysqli_query($conn, "UPDATE pemesanan SET status='Batal' WHERE id_pemesanan=$id");
    mysqli_query($conn, "UPDATE transaksi SET status='Batal' WHERE id_pemesanan=$id");

    mysqli_commit($conn);
    header("Location: index.php?status=pemesanan_dibatalkan");
    exit;
} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "Gagal membatalkan pemesanan: " . htmlspecialchars($e->getMessage());
}
?>
