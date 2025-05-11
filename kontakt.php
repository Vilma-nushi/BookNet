<?php
include_once 'config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('location:login.php');
    exit;
}

if (isset($_POST['send'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $msg = mysqli_real_escape_string($conn, $_POST['message']);

    $check_msg = mysqli_query($conn, "SELECT * FROM mesazhe WHERE emri = '$name' AND email = '$email' AND numri = '$number' AND mesazhe = '$msg'");
    if (mysqli_num_rows($check_msg) > 0) {
        $message[] = 'Mesazhi është dërguar më parë!';
    } else {
        mysqli_query($conn, "INSERT INTO mesazhe (user_id, emri, email, numri, mesazhe) VALUES ('$user_id', '$name', '$email', '$number', '$msg')");
        $message[] = 'Mesazhi u dërgua me sukses!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>kontakti</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>kontaktoni me ne</h3>
   <p> <a href="home.php">Faqja kryesore</a> / kontakt </p>
</div>

<section class="contact">

   <form action="" method="post">
      <h3>Thoni dicka!</h3>
      <input type="text" name="name" required placeholder="shkruani emrin tuaj" class="box">
      <input type="email" name="email" required placeholder="shkruani email-in tuaj" class="box">
      <input type="number" name="number" required placeholder="shkruani numrin tuaj" class="box">
      <textarea name="message" class="box" placeholder="shkruani mesazhin tuaj" id="" cols="30" rows="10"></textarea>
      <input type="submit" value="dergo mesazh" name="send" class="btn">
   </form>

</section>

<?php include 'footer.php'; ?>

<script src="jscript/script.js"></script>

</body>
</html>
