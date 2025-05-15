<?php

if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>
<body>
<header class=" text-black">

   <div class=" py-4">
      <div class="flex justify-between items-center container mx-auto px-6">
         <div class="flex space-x-4">
            <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
            <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
            <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-linkedin"></i></a>
         </div>
         <p class="text-sm">new <a href="login.php" class="text-blue-400 hover:underline">login</a> | <a href="register.php" class="text-blue-400 hover:underline">register</a></p>
      </div>
   </div>

   <div class=" py-4">
      <div class="flex justify-between items-center container mx-auto px-6">
         <a href="home.php" class="text-4xl font-bold text-black">ReadMe</a>

         <nav class="space-x-4">
            <a href="home.php" class="text-gray-600 hover:text-white">home</a>
            <a href="shop.php" class="text-gray-600 hover:text-white">shop</a>
            <a href="orders.php" class="text-gray-600 hover:text-white">orders</a>
            <a href="contact.php" class="text-gray-600 hover:text-white">contact</a>
         </nav>

         <div class="flex items-center space-x-4">
            <div id="menu-btn" class="text-gray-600 hover:text-gray-900 fas fa-bars"></div>
           
            <div id="user-btn" class="text-gray-600 cursor-pointer hover:text-gray-900 fas fa-user"></div>
            <?php
               $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
               $cart_rows_number = mysqli_num_rows($select_cart_number); 
            ?>
            <a href="cart.php" class="text-gray-600 hover:text-gray-900"> <i class="fas fa-shopping-cart"></i> <span>(<?php echo $cart_rows_number; ?>)</span> </a>
         </div>

         <div id="user-box" class="hidden p-3 bg-gray-300 opacity-95 ">
                  <p class="px-4 py-2">username : <span><?php echo $_SESSION['user_name']; ?></span></p>
                  <p  class="px-4 py-2">email : <span><?php echo $_SESSION['user_email']; ?></span></p>
                  <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">logout</a>
         </div>

      </div>
   </div>

</header>
<script src="js/script.js"></script>

   
</body>

