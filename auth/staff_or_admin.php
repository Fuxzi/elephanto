<?php
require_once __DIR__ . "/check.php";

if ($_SESSION['role'] !== 'Staff') {
    header("Location: /jual_mobil/dashboard.php");
    exit;
}
?>
