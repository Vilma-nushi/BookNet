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
   <title>home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="home">

   <div class="content">
      <h3>Libra të përzgjedhur me kujdes deri në derën tuaj</h3>
      <p>Mirë se vini në Librarine Online
      Zbuloni një përzgjedhje të gjerë librash të përzgjedhur me kujdes, të cilët mund t’i lexoni ose t’i porositni për t’ju dërguar direkt në derën tuaj. 
      Regjistrohuni sot dhe përjetoni kënaqësinë e leximit në mënyrë të thjeshtë dhe të rehatshme.</p>
      <a href="about.php" class="white-btn">Zbulo më shumë</a>
   </div>

</section>

<section class="products">

   <h1 class="title">Produktet e reja</h1>

   <div class="box-container">

      <?php  
         $select_products = mysqli_query($conn, "SELECT * FROM `produkt` LIMIT 6") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
     <form action="" method="post" class="box">
      <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
      <div class="name"><?php echo $fetch_products['name']; ?></div>
      <div class="price">$<?php echo $fetch_products['price']; ?>/-</div>
      <input type="number" min="1" name="product_quantity" value="1" class="qty">
      <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
      <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
      <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
      <input type="submit" value="add to cart" name="add_to_cart" class="btn">
     </form>
      <?php
         }
      }else{
         echo '<p class="empty">Nuk janë shtuar produkte ende!</p>';
      }
      ?>
   </div>

   <div class="load-more" style="margin-top: 2rem; text-align:center">
      <a href="shop.php" class="option-btn">Shiko më shumë.</a>
   </div>

</section>

<section class="about">

   <div class="flex">

      <div class="image">
         <img src="images/about-img.jpg" alt="">
      </div>

      <div class="content">
         <h3>Rreth nesh</h3>
         <p>Ne jemi një platformë online e dedikuar për dashamirësit e librave. Me pasion për dijen dhe leximin, ofrojmë një përzgjedhje të gjerë librash për çdo moshë dhe interes. 
            Qëllimi ynë është t’i sjellim librat më të mirë direkt tek ju, me vetëm disa klikime.</p>
         <a href="about.php" class="btn">Lexo me shume</a>
      </div>

   </div>

</section>

<section class="home-contact">

   <div class="content">
      <h3>A keni ndonjë pyetje?</h3>
      <p>Jemi këtu për t'ju ndihmuar! Nëse keni pyetje në lidhje me librat, porositë ose shërbimet tona, mos hezitoni të na kontaktoni. 
         Do t'ju përgjigjemi sa më shpejt të mundemi dhe do të sigurohemi që përvoja juaj të jetë sa më e këndshme dhe e lehtë. </p>
      <a href="kontakt.php" class="white-btn">Na kontaktoni</a>
   </div>

</section>

<?php include 'footer.php'; ?>
<script src="jscript/script.js"></script>

</body>
</html>