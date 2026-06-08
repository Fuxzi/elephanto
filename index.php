<?php
session_start();
include "config.php";

if (isset($_SESSION['id_user'])) {
    header("Location: dashboard.php");
    exit;
}

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM user WHERE username='$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && $password == $user['password']) {
        $_SESSION['login'] = true;
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistem Penjualan Mobil Bekas</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">
    <div class="login-card">
        <div class="login-logo">JM</div>
        <h2>Login Sistem</h2>
        <p>Penjualan Mobil Bekas</p>

        <?php if (isset($error)) { ?>
            <div class="alert-error"><?= htmlspecialchars($error); ?></div>
        <?php } ?>

        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input name="username" type="text" required autofocus>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input name="password" type="password" required>
            </div>

            <button type="submit" name="login" style="width:100%;">Login</button>
        </form>
    </div>
</body>
</html>
