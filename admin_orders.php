<?php
include_once 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'] ?? null;
if (!$admin_id) {
    header('location:login.php');
    exit;
}

if (isset($_POST['update_order'])) {
    $order_update_id = intval($_POST['order_id']);
    $update_payment = mysqli_real_escape_string($conn, $_POST['update_payment']);

    $stmt = mysqli_prepare($conn, "UPDATE porosi SET statusi_pageses = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "si", $update_payment, $order_update_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $message[] = 'Statusi i pagesës u përditësua!';
}

if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt = mysqli_prepare($conn, "DELETE FROM porosi WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $delete_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header('location:admin_orders.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Porositë</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>

<?php include_once 'admin_header.php'; ?>

<section class="orders">
    <h1 class="title">Porositë</h1>
    <div class="box-container">
        <?php
        $select_orders = mysqli_query($conn, "SELECT * FROM porosi") or die('query failed');
        if (mysqli_num_rows($select_orders) > 0):
            while ($fetch_orders = mysqli_fetch_assoc($select_orders)):
        ?>
        <div class="box">
            <p>User ID: <span><?= htmlspecialchars($fetch_orders['user_id']) ?></span></p>
            <p>Data krijimit: <span><?= htmlspecialchars($fetch_orders['dt_krijimit']) ?></span></p>
            <p>Emri: <span><?= htmlspecialchars($fetch_orders['emri']) ?></span></p>
            <p>Numri: <span><?= htmlspecialchars($fetch_orders['numri']) ?></span></p>
            <p>Email: <span><?= htmlspecialchars($fetch_orders['email']) ?></span></p>
            <p>Adresa: <span><?= htmlspecialchars($fetch_orders['adresa']) ?></span></p>
            <p>Produkti total: <span><?= htmlspecialchars($fetch_orders['produkti_total']) ?></span></p>
            <p>Çmimi total: <span>$<?= htmlspecialchars($fetch_orders['cmimi_total']) ?>/-</span></p>
            <p>Metoda e pagesës: <span><?= htmlspecialchars($fetch_orders['metoda']) ?></span></p>

            <form action="" method="post">
                <input type="hidden" name="order_id" value="<?= $fetch_orders['id'] ?>">
                <select name="update_payment">
                    <option value="" disabled selected><?= htmlspecialchars($fetch_orders['statusi_pageses']) ?></option>
                    <option value="pending">ne pritje</option>
                    <option value="completed">perfunduar</option>
                </select>
                <input type="submit" value="Përditëso" name="update_order" class="option-btn">
                <a href="admin_orders.php?delete=<?= $fetch_orders['id'] ?>" onclick="return confirm('Fshije këtë porosi?');" class="delete-btn">Fshi</a>
            </form>
        </div>
        <?php endwhile; else: ?>
            <p class="empty">Asnjë porosi e krijuar!</p>
        <?php endif; ?>
    </div>
</section>

<script src="jscript/admin_script.js"></script>
</body>
</html>
