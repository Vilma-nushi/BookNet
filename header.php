<?php
if (isset($message)) {
   foreach ($message as $msg) {
      echo '
      <div class="message">
         <span>' . htmlspecialchars($msg) . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>';
   }
}
?>

<header class="header">

   <!-- Header Top Bar -->
   <div class="header-1">
      <div class="flex">
         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="#" class="fab fa-instagram"></a>
            <a href="#" class="fab fa-linkedin"></a>
         </div>
         <p>new <a href="login.php">kycu</a> | <a href="register.php">rregjistrohu</a></p>
      </div>
   </div>

   <!-- Main Header -->
   <div class="header-2">
      <div class="flex">
         <a href="home.php" class="logo">BookNet</a>

         <nav class="navbar">
            <a href="home.php">faqja kryesore</a>
            <a href="about.php">rreth nesh</a>
            <a href="shop.php">dyqani</a>
            <a href="kontakt.php">kontakt</a>
            <a href="orders.php">porosite</a>
         </nav>

         <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="search_page.php" class="fas fa-search"></a>
            <a href="shporta.php" class="fas fa-shopping-cart"><span>(0)</span></a>
            <div id="user-btn" class="fas fa-user"></div>
         </div>

         <div class="user-box">
   <?php if (isset($_SESSION['user_id'])): ?>
      <?php
         // Merr të dhënat e përdoruesit nga databaza bazuar në ID-në e përdoruesit
         $user_id = $_SESSION['user_id'];
         $query = mysqli_query($conn, "SELECT emri, email FROM users WHERE id = '$user_id'");
         $user = mysqli_fetch_assoc($query);
      ?>
      <p>Emri: <span><?= htmlspecialchars($user['emri']) ?></span></p>
      <p>Email: <span><?= htmlspecialchars($user['email']) ?></span></p>
      <a href="logout.php" class="delete-btn">Dil</a>
   <?php endif; ?>
</div>

      </div>
   </div>

</header>
