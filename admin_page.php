<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin panel</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

</head>
<body>
   
<?php include 'admin_header.php'; ?>
<!-- admin dashboard section   -->
<section class="dashboard py-12 bg-gray-100">

   <h1 class="text-4xl font-bold text-center mb-8">Dashboard</h1>

   <div class="box-container grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

      <div class="box bg-white p-6 rounded-lg shadow-lg hover:bg-blue-500 hover:shadow-xl transform hover:scale-105 transition-all duration-300">
         <?php
            $total_pendings = 0;
            $select_pending = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE payment_status = 'pending'") or die('query failed');
            if(mysqli_num_rows($select_pending) > 0){
               while($fetch_pendings = mysqli_fetch_assoc($select_pending)){
                  $total_price = $fetch_pendings['total_price'];
                  $total_pendings += $total_price;
               };
            };
         ?>
         <h3 class="text-2xl font-bold text-center">DZD <?php echo $total_pendings; ?></h3>
         <p class="text-center text-gray-500">Total Pendings</p>
      </div>

      <div class="box bg-white p-6 rounded-lg shadow-lg hover:bg-blue-500 hover:shadow-xl transform hover:scale-105 transition-all duration-300">
         <?php
            $total_completed = 0;
            $select_completed = mysqli_query($conn, "SELECT total_price FROM `orders` WHERE payment_status = 'completed'") or die('query failed');
            if(mysqli_num_rows($select_completed) > 0){
               while($fetch_completed = mysqli_fetch_assoc($select_completed)){
                  $total_price = $fetch_completed['total_price'];
                  $total_completed += $total_price;
               };
            };
         ?>
         <h3 class="text-2xl font-bold text-center">DZD <?php echo $total_completed; ?></h3>
         <p class="text-center text-gray-500">Completed Payments</p>
      </div>

      <div class="box bg-white p-6 rounded-lg shadow-lg hover:bg-blue-500 hover:shadow-xl transform hover:scale-105 transition-all duration-300">
         <?php 
            $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
            $number_of_orders = mysqli_num_rows($select_orders);
         ?>
         <h3 class="text-2xl font-bold text-center"><?php echo $number_of_orders; ?></h3>
         <p class="text-center text-gray-500">Orders Placed</p>
      </div>

      <div class="box bg-white p-6 rounded-lg shadow-lg hover:bg-blue-500 hover:shadow-xl transform hover:scale-105 transition-all duration-300">
         <?php 
            $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
            $number_of_products = mysqli_num_rows($select_products);
         ?>
         <h3 class="text-2xl font-bold text-center"><?php echo $number_of_products; ?></h3>
         <p class="text-center text-gray-500">Products Added</p>
      </div>

      <div class="box bg-white p-6 rounded-lg shadow-lg hover:bg-blue-500 hover:shadow-xl transform hover:scale-105 transition-all duration-300">
         <?php 
            $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'user'") or die('query failed');
            $number_of_users = mysqli_num_rows($select_users);
         ?>
         <h3 class="text-2xl font-bold text-center"><?php echo $number_of_users; ?></h3>
         <p class="text-center text-gray-500">Normal Users</p>
      </div>

      <div class="box bg-white p-6 rounded-lg shadow-lg hover:bg-blue-500 hover:shadow-xl transform hover:scale-105 transition-all duration-300">
         <?php 
            $select_admins = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'admin'") or die('query failed');
            $number_of_admins = mysqli_num_rows($select_admins);
         ?>
         <h3 class="text-2xl font-bold text-center"><?php echo $number_of_admins; ?></h3>
         <p class="text-center text-gray-500">Admin Users</p>
      </div>

      <div class="box bg-white p-6 rounded-lg shadow-lg hover:bg-blue-500 hover:shadow-xl transform hover:scale-105 transition-all duration-300">
         <?php 
            $select_account = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
            $number_of_account = mysqli_num_rows($select_account);
         ?>
         <h3 class="text-2xl font-bold text-center"><?php echo $number_of_account; ?></h3>
         <p class="text-center text-gray-500">Total Accounts</p>
      </div>

      <div class="box bg-white p-6 rounded-lg shadow-lg hover:bg-blue-500 hover:shadow-xl transform hover:scale-105 transition-all duration-300">
         <?php 
            $select_messages = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
            $number_of_messages = mysqli_num_rows($select_messages);
         ?>
         <h3 class="text-2xl font-bold text-center"><?php echo $number_of_messages; ?></h3>
         <p class="text-center text-gray-500">New Messages</p>
      </div>

   </div>

</section>

<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>