<?php
include_once 'config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verifikoni token-in dhe kontrolloni nëse ka skaduar
    $result = mysqli_query($conn, "SELECT * FROM users WHERE reset_token = '$token' AND reset_expiry > NOW()");
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        if (isset($_POST['reset'])) {
            // Kontrollo dhe përditëso fjalëkalimin
            if ($_POST['new_password'] === $_POST['confirm_password']) {
                $new_pass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                mysqli_query($conn, "UPDATE users SET password = '$new_pass', reset_token = NULL, reset_expiry = NULL WHERE id = {$row['id']}");
                echo "Fjalëkalimi u ndryshua me sukses.";
            } else {
                echo "Fjalëkalimet nuk përputhen.";
            }
        }
    } else {
        echo "Tokeni është i pavlefshëm ose ka skaduar.";
    }
}
?>

<form action="" method="post">
    <h3>Vendos një fjalëkalim të ri</h3>
    <input type="password" name="new_password" placeholder="Fjalëkalim i ri" required>
    <input type="password" name="confirm_password" placeholder="Konfirmo fjalëkalimin" required>
    <input type="submit" name="reset" value="Ndrysho fjalëkalimin">
</form>
