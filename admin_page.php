<?php
include_once 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'] ?? null;
if (!$admin_id) {
    header('location:login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>

<?php include_once 'admin_header.php'; ?>

<section class="dashboard">
    <h1 class="title">Dashboard</h1>

    <div class="box-container">

        <div class="box">
            <?php
            $total_pendings = 0;
            $result = mysqli_query($conn, "SELECT cmimi_total FROM porosi WHERE statusi_pageses = 'pending'");
            while ($row = mysqli_fetch_assoc($result)) {
                $total_pendings += floatval($row['cmimi_total']);
            }
            ?>
            <h3>$<?= number_format($total_pendings, 2) ?>/-</h3>
            <p>Pagesat në pritje</p>
        </div>

        <div class="box">
            <?php
            $total_completed = 0;
            $result = mysqli_query($conn, "SELECT cmimi_total FROM porosi WHERE statusi_pageses = 'completed'");
            while ($row = mysqli_fetch_assoc($result)) {
                $total_completed += floatval($row['cmimi_total']);
            }
            ?>
            <h3>$<?= number_format($total_completed, 2) ?>/-</h3>
            <p>Pagesat e përfunduara</p>
        </div>

        <div class="box">
            <?php
            $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM porosi");
            $row = mysqli_fetch_assoc($result);
            ?>
            <h3><?= $row['total'] ?></h3>
            <p>Porositë e bëra</p>
        </div>

        <div class="box">
            <?php
            $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM produkt");
            $row = mysqli_fetch_assoc($result);
            ?>
            <h3><?= $row['total'] ?></h3>
            <p>Produktet</p>
        </div>

        <div class="box">
            <?php
            $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE user_type = 'user'");
            $row = mysqli_fetch_assoc($result);
            ?>
            <h3><?= $row['total'] ?></h3>
            <p>Përdorues normal</p>
        </div>

        <div class="box">
            <?php
            $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE user_type = 'admin'");
            $row = mysqli_fetch_assoc($result);
            ?>
            <h3><?= $row['total'] ?></h3>
            <p>Admin</p>
        </div>

        <div class="box">
            <?php
            $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
            $row = mysqli_fetch_assoc($result);
            ?>
            <h3><?= $row['total'] ?></h3>
            <p>Gjithsej llogari</p>
        </div>

        <div class="box">
            <?php
            $result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM mesazhe");
            $row = mysqli_fetch_assoc($result);
            ?>
            <h3><?= $row['total'] ?></h3>
            <p>Mesazhe</p>
        </div>

    </div>
</section>

<script src="jscript/admin_script.js"></script>
</body>
</html>
