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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rreth Nesh</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include_once 'header.php'; ?>

<div class="heading">
    <h3>Rreth Nesh</h3>
    <p><a href="home.php">Faqja kryesore</a> / Rreth nesh</p>
</div>

<section class="about">
    <div class="flex">
        <div class="image">
            <img src="images/about.jpg" alt="imazh rreth nesh">
        </div>
        <div class="content">
            <h3>Pse duhet të na zgjidhni ne?</h3>
            <p>Dashuria për tekstin është thelbësore. Edhe kur ka vështirësi apo shqetësime, lindja e ideve dhe rrjedhja e tyre është e rëndësishme, edhe nëse përjashtimet dhe arsyet ligjore janë të pranishme. Përjashtimet mund të tolerohen kur vendosim drejtësi dhe bëjmë atë që është e drejtë në kohën e duhur.</p>
            <p>Besimi dhe kënaqësia e klientit janë prioriteti ynë.</p>
            <a href="kontakt.php" class="btn">Na kontaktoni</a>
        </div>
    </div>
</section>

<section class="reviews">
    <h1 class="title">Vlerësimet e klientëve</h1>
    <div class="box-container">
        <?php for ($i = 0; $i < 6; $i++): ?>
        <div class="box">
            <img src="images/user.jpg" alt="">
            <p>Shërbim i shkëlqyer!</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Ana</h3>
        </div>
        <?php endfor; ?>
    </div>
</section>

<section class="authors">
    <h1 class="title">Autorët më të njohur</h1>
    <div class="box-container">
        <?php
        $authors = [
            ['Ismail-Kadare.jpg', 'Ismail Kadare'],
            ['dritero.jpg', 'Dritëro Agolli'],
            ['elJames.jpg', 'El James'],
            ['klenti.jpg', 'Klenti Hodo'],
            ['colleen.jpg', 'Colleen Hoover'],
            ['AnnaPremoli.jpg', 'Anna Premoli']
        ];
        foreach ($authors as [$img, $name]):
        ?>
        <div class="box">
            <img src="images/<?= htmlspecialchars($img) ?>" alt="">
            <div class="share">
                <a href="#" class="fab fa-facebook-f"></a>
                <a href="#" class="fab fa-twitter"></a>
                <a href="#" class="fab fa-instagram"></a>
                <a href="#" class="fab fa-linkedin"></a>
            </div>
            <h3><?= htmlspecialchars($name) ?></h3>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<?php include_once 'footer.php'; ?>
<script src="jscript/script.js"></script>

</body>
</html>
