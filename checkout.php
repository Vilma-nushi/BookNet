<?php
include_once 'config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('location:login.php');
    exit;
}

if (isset($_POST['order_btn'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $address = mysqli_real_escape_string($conn,
        'Flat ' . $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['state'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code']
    );
    $placed_on = date('d-M-Y');

    $cart_total = 0;
    $cart_products = [];

    $cart_query = mysqli_query($conn, "SELECT * FROM shporta WHERE user_id = '$user_id'") or die('query failed');
    while ($item = mysqli_fetch_assoc($cart_query)) {
        $cart_products[] = $item['emri'] . ' (' . $item['sasia'] . ')';
        $cart_total += $item['cmimi'] * $item['sasia'];
    }

    $total_products = implode(', ', $cart_products);

    if ($cart_total == 0) {
        $message[] = 'Shporta është bosh.';
    } else {
        $check = mysqli_query($conn, "SELECT * FROM porosi WHERE emri = '$name' AND numri = '$number' AND email = '$email' AND metoda = '$method' AND adresa = '$address' AND produkti_total = '$total_products' AND cmimi_total = '$cart_total'") or die('query failed');

        if (mysqli_num_rows($check) > 0) {
            $message[] = 'Porosia është vendosur më parë!';
        } else {
            mysqli_query($conn, "INSERT INTO porosi (user_id, emri, numri, email, metoda, adresa, produkti_total, cmimi_total, dt_krijimit, statusi_pageses) VALUES ('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on', 'pending')") or die('query failed');
            mysqli_query($conn, "DELETE FROM shporta WHERE user_id = '$user_id'");
            $message[] = 'Porosia u vendos me sukses!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>checkout</h3>
   <p> <a href="home.php">home</a> / checkout </p>
</div>

<section class="display-order">

   <?php  
      $grand_total = 0;
      $select_cart = mysqli_query($conn, "SELECT * FROM `shporta` WHERE user_id = '$user_id'") or die('query failed');
      if(mysqli_num_rows($select_cart) > 0){
         while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            $total_price = ($fetch_cart['cmimi'] * $fetch_cart['sasia']);
            $grand_total += $total_price;
   ?>
   <p> <?php echo $fetch_cart['emri']; ?> <span>(<?php echo '$'.$fetch_cart['cmimi'].'/-'.' x '. $fetch_cart['sasia']; ?>)</span> </p>
   <?php
      }
   }else{
      echo '<p class="empty">shporta juaj eshte bosh</p>';
   }
   ?>
   <div class="grand-total"> shuma totale: <span>$<?php echo $grand_total; ?>/-</span> </div>

</section>

<section class="checkout">

   <form action="" method="post">
      <h3>Bëni porosinë tuaj</h3>
      <div class="flex">
         <div class="inputBox">
            <span>emri juaj :</span>
            <input type="text" name="name" required placeholder="shkruani emrin tuaj">
         </div>
         <div class="inputBox">
            <span>numri juaj :</span>
            <input type="number" name="number" required placeholder="shkruani numrin tuaj">
         </div>
         <div class="inputBox">
            <span>email juaj :</span>
            <input type="email" name="email" required placeholder="shkruani email-in tuaj">
         </div>
         <div class="inputBox">
            <span>metoda e pageses :</span>
            <select name="method">
               <option value="cash on delivery">cash </option>
               <option value="paypal">paypal</option>
            </select>
         </div>
         <div class="inputBox">
            <span>adresa 01 :</span>
            <input type="number" min="0" name="flat" required placeholder="p.sh. flat no.">
         </div>
         <div class="inputBox">
            <span>adresa 02 :</span>
            <input type="text" name="street" required placeholder="p.sh. emri i rrugës">
         </div>
         <div class="inputBox">
            <span>qyteti :</span>
            <input type="text" name="city" required placeholder="p.sh. tirana">
         </div>
         <div class="inputBox">
            <span>qyteti:</span>
            <input type="text" name="state" required placeholder="p.sh. tirana">
         </div>
         <div class="inputBox">
            <span>shteti :</span>
            <input type="text" name="country" required placeholder="p.sh. shqipëri">
         </div>
         <div class="inputBox">
            <span>kod postar :</span>
            <input type="number" min="0" name="pin_code" required placeholder="p.sh. 123456">
         </div>
      </div>
      <input type="submit" value="porosit tani" class="btn" name="order_btn">
   </form>

</section>

<?php include 'footer.php'; ?>
<script src="jscript/script.js"></script>

</body>
</html>
