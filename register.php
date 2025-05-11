<?php
include_once 'config.php';
session_start();

if (isset($_POST['submit'])) {
   $emri = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $password = $_POST['password'];
   $cpassword = $_POST['cpassword'];
   $security_question = mysqli_real_escape_string($conn, $_POST['security_question']);
   $security_answer_raw = $_POST['security_answer'];
   $security_answer = password_hash($security_answer_raw, PASSWORD_DEFAULT);
   
   $user_type = 'user'; // vetëm përdorues të thjeshtë mund të regjistrohen nga forma

   $check_user = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
   if (mysqli_num_rows($check_user) > 0) {
       $message[] = 'Ky email është regjistruar më parë!';
   } elseif ($password !== $cpassword) {
       $message[] = 'Fjalëkalimet nuk përputhen!';
   } else {
       $pass_hashed = password_hash($password, PASSWORD_DEFAULT);
       mysqli_query($conn, "INSERT INTO users (emri, email, password, user_type, security_question, security_answer)
            VALUES ('$emri', '$email', '$pass_hashed', '$user_type', '$security_question', '$security_answer')");
       $_SESSION['success'] = 'Regjistrimi u krye me sukses! Mund të kyçeni tani.';
       header('location:login.php');
       exit;
   }
}
?>

<!DOCTYPE html>
<html lang="sq">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Regjistrohu</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php
if(isset($message)){
   foreach($message as $msg){
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
      <h3>Regjistrohu tani</h3>
      <input type="text" name="name" placeholder="Shkruani emrin tuaj" required class="box">
      <input type="email" name="email" placeholder="Shkruani emailin tuaj" required class="box">
      <input type="password" name="password" placeholder="Shkruani fjalëkalimin tuaj" required class="box">
      <input type="password" name="cpassword" placeholder="Konfirmoni fjalëkalimin tuaj" required class="box">

      <select name="security_question" required class="box">
         <option value="">Zgjidhni një pyetje sigurie</option>
         <option value="Emri i mësuesit tuaj të parë?">Emri i mësuesit tuaj të parë?</option>
         <option value="Qyteti ku keni lindur?">Qyteti ku keni lindur?</option>
         <option value="Emri i kafshës suaj të parë?">Emri i kafshës suaj të parë?</option>
      </select>

      <input type="text" name="security_answer" placeholder="Përgjigjuni pyetjes së sigurisë" required class="box">

      <input type="submit" name="submit" value="Regjistrohu tani" class="btn">
      <p>Keni një llogari? <a href="login.php">Kyçuni tani</a></p>
   </form>
</div>

</body>
</html>