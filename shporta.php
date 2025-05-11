<?php
include_once 'config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('location:login.php');
    exit;
}

if (isset($_POST['update_cart'])) {
    $cart_id = intval($_POST['cart_id']);
    $cart_quantity = intval($_POST['cart_quantity']);
    mysqli_query($conn, "UPDATE shporta SET sasia = '$cart_quantity' WHERE id = '$cart_id'") or die('query failed');
    $message[] = 'Sasia në shportë u përditësua!';
}

if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM shporta WHERE id = '$delete_id'") or die('query failed');
    header('location:shporta.php');
    exit;
}

if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM shporta WHERE user_id = '$user_id'") or die('query failed');
    header('location:shporta.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shporta</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include_once 'header.php'; ?>

<div class="heading">
    <h3>Shporta</h3>
    <p><a href="home.php">Home</a> / Shporta</p>
</div>

<section class="shopping-cart">
    <h1 class="title">Produktet në Shportë</h1>
    <div class="box-container">
        <?php
        $grand_total = 0;
        $select_cart = mysqli_query($conn, "SELECT * FROM shporta WHERE user_id = '$user_id'") or die('query failed');
        if (mysqli_num_rows($select_cart) > 0):
            while ($fetch_cart = mysqli_fetch_assoc($select_cart)):
                $sub_total = $fetch_cart['cmimi'] * $fetch_cart['sasia'];
                $grand_total += $sub_total;
        ?>
        <div class="box">
            <a href="shporta.php?delete=<?= $fetch_cart['id'] ?>" class="fas fa-times" onclick="return confirm('Heq nga shporta?');"></a>
            <img src="uploaded_img/<?= htmlspecialchars($fetch_cart['image']) ?>" alt="">
            <div class="name"><?= htmlspecialchars($fetch_cart['emri']) ?></div>
            <div class="price">$<?= number_format($fetch_cart['cmimi'], 2) ?>/-</div>
            <form action="" method="post">
                <input type="hidden" name="cart_id" value="<?= $fetch_cart['id'] ?>">
                <input type="number" name="cart_quantity" value="<?= $fetch_cart['sasia'] ?>" min="1" class="qty">
                <input type="submit" name="update_cart" value="Përditëso" class="option-btn">
            </form>
            <div class="sub-total">Nën-total: <span>$<?= number_format($sub_total, 2) ?>/-</span></div>
        </div>
        <?php endwhile; else: ?>
            <p class="empty">Shporta juaj është bosh.</p>
        <?php endif; ?>
    </div>

    <div style="margin-top: 2rem; text-align:center;">
        <a href="shporta.php?delete_all" class="delete-btn <?= ($grand_total > 1) ? '' : 'disabled' ?>" onclick="return confirm('Fshij të gjitha nga shporta?');">Fshi të gjitha</a>
    </div>

    <div class="cart-total">
        <p>Total: <span>$<?= number_format($grand_total, 2) ?>/-</span></p>
        <div class="flex">
            <a href="shop.php" class="option-btn">Vazhdo Blerjen</a>
            <a href="checkout.php" class="btn <?= ($grand_total > 1) ? '' : 'disabled' ?>">Përfundo Blerjen</a>
        </div>
    </div>
</section>

<?php include_once 'footer.php'; ?>
<script src="jscript/script.js"></script>
</body>
</html>
