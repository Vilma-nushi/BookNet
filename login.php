<?php
include_once 'config.php';
session_start();

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = $_POST['password']; // nuk e escapo sepse do përdoret në password_verify më vonë

    // Kërkon përdoruesin nga databaza
    $select = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'") or die('query failed');
    if (mysqli_num_rows($select) > 0) {
        $row = mysqli_fetch_assoc($select);


        if (password_verify($pass, $row['password'])) {
            // Kontrollo nëse është admin dhe drejtohu në faqen e admin
            if ($row['user_type'] === 'admin') {
                $_SESSION['admin_id'] = $row['id']; // Ruaj ID e adminit në session
                header('location:admin_page.php'); // Drejtohu te faqja e adminit
                exit;
            }
            // Kontrollo nëse është përdorues i zakonshëm dhe drejtohu në faqen e përdoruesit
            elseif ($row['user_type'] === 'user') {
                $_SESSION['user_id'] = $row['id']; // Ruaj ID e përdoruesit në session
                header('location:home.php'); // Drejtohu te faqja e përdoruesit
                exit;
            }
        } else {
            $message[] = 'Email ose fjalëkalim i pasaktë!'; // Nëse fjalëkalimi është i gabuar
        }
    } else {
        $message[] = 'Email ose fjalëkalim i pasaktë, nuk u gjet përdoruesi!'; // Nëse nuk u gjet përdoruesi
    }
}
?>

<!DOCTYPE html>
<html lang="sq">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Kyçu</title>

   <!-- Font awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Stili CSS -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php
if (isset($message)) {
   foreach ($message as $msg) {
      echo '
      <div class="message">
         <span>'.$msg.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>';
   }
}
?>
   
   <div class="form-container">
   <form action="" method="post">
      <h3>Kyçu tani</h3>
      <input type="email" name="email" placeholder="Vendosni emailin tuaj" required class="box">
      <input type="password" name="password" placeholder="Vendosni fjalëkalimin tuaj" required class="box">
      <input type="submit" name="submit" value="Kyçu tani" class="btn">
      <p>Nuk keni një llogari? <a href="register.php">Regjistrohuni tani</a></p>
      <p><a href="forgot_password.php">Keni harruar fjalëkalimin?</a></p>
   </form>
</div>


</body>
</html>
