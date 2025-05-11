
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

    // Kontrollo që përdoruesi që do fshihet të mos jetë admin
    $check_user = mysqli_prepare($conn, "SELECT user_type FROM users WHERE id = ?");
    mysqli_stmt_bind_param($check_user, "i", $delete_id);
    mysqli_stmt_execute($check_user);
    mysqli_stmt_bind_result($check_user, $user_type_to_delete);
    mysqli_stmt_fetch($check_user);
    mysqli_stmt_close($check_user);

    if ($user_type_to_delete === 'admin') {
        // Nuk lejohet fshirja e adminëve
        header('location:admin_users.php?error=admin_delete_blocked');
        exit;
    }

    // Fshi vetëm nëse nuk është admin
    $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $delete_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    header('location:admin_users.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>

<?php include_once 'admin_header.php'; ?>

<?php if (isset($_GET['error']) && $_GET['error'] === 'admin_delete_blocked'): ?>
   <div class="message" style="background-color: #ffcccc; padding: 10px; border-left: 4px solid red; margin: 10px;">
       <span>Adminët nuk mund të fshihen!</span>
   </div>
<?php endif; ?>

<section class="users">
    <h1 class="title">Llogaritë e përdoruesve</h1>
    <div class="box-container">
        <?php
        $select_users = mysqli_query($conn, "SELECT * FROM users") or die('query failed');
        if (mysqli_num_rows($select_users) > 0):
            while ($user = mysqli_fetch_assoc($select_users)):
        ?>
        <div class="box">
            <p>ID përdoruesi: <span><?= htmlspecialchars($user['id']) ?></span></p>
            <p>Emri: <span><?= htmlspecialchars($user['emri']) ?></span></p>
            <p>Email: <span><?= htmlspecialchars($user['email']) ?></span></p>
            <p>User type: 
                <span style="color:<?= $user['user_type'] === 'admin' ? 'var(--orange)' : '#333'; ?>">
                    <?= htmlspecialchars($user['user_type']) ?>
                </span>
            </p>
            <a href="admin_users.php?delete=<?= $user['id'] ?>" onclick="return confirm('Të fshihet ky përdorues?');" class="delete-btn">Fshi përdoruesin</a>
        </div>
        <?php endwhile; else: ?>
            <p class="empty">Nuk ka përdorues të regjistruar.</p>
        <?php endif; ?>
    </div>
</section>

<script src="jscript/admin_script.js"></script>
</body>
</html>
