<?php
include_once 'config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('location:login.php');
    exit;
}

if (isset($_POST['add_to_cart'])) {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $product_price = floatval($_POST['product_price']);
    $product_image = mysqli_real_escape_string($conn, $_POST['product_image']);
    $product_quantity = intval($_POST['product_quantity']);

    $check_cart = mysqli_query($conn, "SELECT * FROM shporta WHERE emri = '$product_name' AND user_id = '$user_id'");
    if (mysqli_num_rows($check_cart) > 0) {
        $message[] = 'Produkti është tashmë në shportë!';
    } else {
        mysqli_query($conn, "INSERT INTO shporta (user_id, emri, cmimi, sasia, image) VALUES ('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')");
        $message[] = 'Produkti u shtua në shportë!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dyqani</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include_once 'header.php'; ?>

<div class="heading">
   <h3>Dyqani ynë</h3>
   <p> <a href="home.php">Faqja kryesore</a> / Dyqani </p>
</div>

<section class="products">
   <h1 class="title">Produktet më të fundit</h1>
   <div class="box-container">

      <?php  
         $select_products = mysqli_query($conn, "SELECT * FROM `produkt`") or die('Query failed: ' . mysqli_error($conn));
         if (mysqli_num_rows($select_products) > 0):
            while ($fetch_products = mysqli_fetch_assoc($select_products)):
      ?>
      <form action="" method="post" class="box">
         <img class="image" src="uploaded_img/<?= htmlspecialchars($fetch_products['image']) ?>" alt="">
         <div class="name"><?= htmlspecialchars($fetch_products['emri']) ?></div>
         <div class="price">$<?= number_format($fetch_products['cmimi'], 2) ?>/-</div>
         <input type="number" min="1" name="product_quantity" value="1" class="qty">
         <input type="hidden" name="product_name" value="<?= htmlspecialchars($fetch_products['emri']) ?>">
         <input type="hidden" name="product_price" value="<?= $fetch_products['cmimi'] ?>">
         <input type="hidden" name="product_image" value="<?= htmlspecialchars($fetch_products['image']) ?>">
         <input type="submit" value="Shto në shportë" name="add_to_cart" class="btn">
      </form>
      <?php
         endwhile;
         else:
            echo '<p class="empty">Nuk ka produkte të shtuara ende!</p>';
         endif;
      ?>
   </div>
</section>

<?php include_once 'footer.php'; ?>
<script src="jscript/script.js"></script>

</body>
</html>
