<?php
include_once 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'] ?? null;

if (!$admin_id) {
    header('location:login.php');
    exit;
}

if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt = mysqli_prepare($conn, "DELETE FROM mesazhe WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $delete_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header('location:admin_contacts.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesazhe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>

<?php include_once 'admin_header.php'; ?>

<section class="messages">
    <h1 class="title">Mesazhet</h1>
    <div class="box-container">
        <?php
        $select_message = mysqli_query($conn, "SELECT * FROM mesazhe") or die('query failed');
        if (mysqli_num_rows($select_message) > 0):
            while ($fetch_message = mysqli_fetch_assoc($select_message)):
        ?>
        <div class="box">
            <p>Perdorues ID: <span><?= htmlspecialchars($fetch_message['user_id']) ?></span></p>
            <p>Emri: <span><?= htmlspecialchars($fetch_message['emri']) ?></span></p>
            <p>Numri: <span><?= htmlspecialchars($fetch_message['numri']) ?></span></p>
            <p>Email: <span><?= htmlspecialchars($fetch_message['email']) ?></span></p>
            <p>Mesazh: <span><?= htmlspecialchars($fetch_message['mesazhe'] ?? '') ?></span></p>
            <a href="admin_contacts.php?delete=<?= $fetch_message['id'] ?>" onclick="return confirm('Fshi këtë mesazh?');" class="delete-btn">Fshi mesazhin</a>
        </div>
        <?php endwhile; else: ?>
            <p class="empty">Nuk ka asnjë mesazh!</p>
        <?php endif; ?>
    </div>
</section>

<script src="jscript/admin_script.js"></script>
</body>
</html>
