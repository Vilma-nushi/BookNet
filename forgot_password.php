<?php
include_once 'config.php';
session_start();

$error = '';
$success = '';

// Hapi 1: Futja e emailit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_email'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");

    if (mysqli_num_rows($query) > 0) {
        $user = mysqli_fetch_assoc($query);
        $_SESSION['reset_email'] = $user['email'];
        $_SESSION['security_question'] = $user['security_question'];
    } else {
        $error = "Emaili nuk ekziston në sistem.";
    }
}

// Hapi 2: Verifikimi i përgjigjes dhe ndryshimi i fjalëkalimit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_answer'])) {
    $email = $_SESSION['reset_email'] ?? '';
    $answer = mysqli_real_escape_string($conn, $_POST['answer']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($email)) {
        $error = "Sesioni ka skaduar. Provo përsëri.";
    } else {
        $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
        $user = mysqli_fetch_assoc($query);

        // Krahasimi i përgjigjes së sigurisë me atë të ruajtur (e kriptuar)
        if (password_verify($answer, $user['security_answer'])) {
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                mysqli_query($conn, "UPDATE users SET password = '$hashed_password' WHERE email = '$email'");
                $success = "Fjalëkalimi u ndryshua me sukses!";
                session_unset();
                session_destroy();
            } else {
                $error = "Fjalëkalimet nuk përputhen.";
            }
        } else {
            $error = "Përgjigjja nuk është e saktë.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <title>Rikupero Fjalëkalimin</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="forgot-password-container">
    <form action="forgot_password.php" method="POST">
        <h3>Rikupero fjalëkalimin</h3>

        <?php if ($error): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="message success"><?= htmlspecialchars($success) ?></div>
            <a href="login.php" class="btn">Shko tek login</a>
            <?php exit; ?>
<?php endif; ?>

        <?php if (!isset($_SESSION['security_question'])): ?>
            <!-- Hapi 1: Futja e emailit -->
            <input type="email" name="email" placeholder="Email" required class="box">
            <input type="submit" name="submit_email" value="Vazhdo" class="btn">
        <?php else: ?>
            <!-- Hapi 2: Pyetja e sigurisë dhe fjalëkalimi i ri -->
            <p><strong>Pyetja e sigurisë:</strong> <?= htmlspecialchars($_SESSION['security_question']) ?></p>
            <input type="text" name="answer" placeholder="Përgjigjja" required class="box">
            <input type="password" name="new_password" placeholder="Fjalëkalimi i ri" required class="box">
            <input type="password" name="confirm_password" placeholder="Konfirmo fjalëkalimin" required class="box">
            <input type="submit" name="submit_answer" value="Ndrysho fjalëkalimin" class="btn">
        <?php endif; ?>

        <a href="login.php" class="link">Kthehu tek login</a>
    </form>
</div>
</body>
</html>