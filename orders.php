<?php
include_once 'config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('location:login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Porositë</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include_once 'header.php'; ?>

<div class="heading">
    <h3>Porositë e mia</h3>
    <p><a href="home.php">Faqja kryesore</a> / Porositë</p>
</div>

<section class="placed-orders">
    <h1 class="title">Porositë e vendosura</h1>
    <div class="box-container">
        <?php
        $select_orders = mysqli_query($conn, "SELECT * FROM porosi WHERE user_id = '$user_id'") or die('query failed');
        if (mysqli_num_rows($select_orders) > 0):
            while ($order = mysqli_fetch_assoc($select_orders)):
        ?>
        <div class="box">
            <p>Vendosur më: <span><?= htmlspecialchars($order['dt_krijimit']) ?></span></p>
            <p>Emri: <span><?= htmlspecialchars($order['emri']) ?></span></p>
            <p>Email: <span><?= htmlspecialchars($order['email']) ?></span></p>
            <p>Numri: <span><?= htmlspecialchars($order['numri']) ?></span></p>
            <p>Adresa: <span><?= htmlspecialchars($order['adresa']) ?></span></p>
            <p>Produkte: <span><?= htmlspecialchars($order['produkti_total']) ?></span></p>
            <p>Çmimi total: <span>$<?= htmlspecialchars($order['cmimi_total']) ?>/-</span></p>
            <p>Metoda: <span><?= htmlspecialchars($order['metoda']) ?></span></p>
            <p>Statusi i pagesës: 
                <span style="color:<?= ($order['statusi_pageses'] === 'pending') ? 'red' : 'green' ?>;">
                    <?= htmlspecialchars($order['statusi_pageses']) ?>
                </span>
            </p>
        </div>
        <?php endwhile; else: ?>
            <p class="empty">Nuk ke bërë ende porosi.</p>
        <?php endif; ?>
    </div>
</section>

<?php include_once 'footer.php'; ?>
<script src="jscript/script.js"></script>
</body>
</html>
