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
<header class="header bg-gray-800 text-white py-6">

<div class="flex items-center justify-between container mx-auto px-4">

   <!-- Logo -->
   <a href="admin_page.php" class="logo text-2xl font-bold hover:text-blue-400">
      Admin<span class="text-blue-500">Panel</span>
   </a>

   <!-- Navbar -->
   <nav class="navbar space-x-6 text-lg">
      <a href="admin_page.php" class="hover:text-blue-400">home</a>
      <a href="admin_products.php" class="hover:text-blue-400">products</a>
     <a href="admin_orders.php" class="hover:text-blue-400">orders</a>
      <a href="admin_users.php" class="hover:text-blue-400">users</a>
      <a href="admin_contacts.php" class="hover:text-blue-400">messages</a>
   </nav>

  <!-- Icons and User Account Box -->
<div class="icons flex items-center space-x-4">
   <div id="menu-btn" class="fas fa-bars cursor-pointer hover:text-blue-400"></div>
   <div id="admin-btn" class="fas fa-user cursor-pointer hover:text-blue-400"></div>
</div>

<!-- admin Box -->
<div id="admin-box" class=" bg-gray-900 p-4 rounded-lg shadow-lg hidden">
   <p class="text-sm">Username: <span class="font-semibold"><?php echo $_SESSION['admin_name']; ?></span></p>
   <p class="text-sm">Email: <span class="font-semibold"><?php echo $_SESSION['admin_email']; ?></span></p>
   <a href="logout.php" class="mt-4 block bg-red-500 hover:bg-red-600 text-white text-center py-2 rounded">Logout</a>
   <div class="text-sm mt-2 text-center">
      New? <a href="login.php" class="hover:text-blue-400">Login</a> | <a href="register.php" class="hover:text-blue-400">Register</a>
   </div>
</div>


</div>

</header>
<script src="js/admin_script.js"></script>
</body>