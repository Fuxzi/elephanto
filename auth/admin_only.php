<?php
require_once __DIR__ . "/check.php";

if ($_SESSION['role'] !== 'Admin') {
    header("Location: /jual_mobil/dashboard.php");
    exit;
}
?>
