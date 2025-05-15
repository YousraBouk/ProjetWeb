<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['add_to_cart'])){

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if(mysqli_num_rows($check_cart_numbers) > 0){
      $message[] = 'already added to cart!';
   }else{
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
      $message[] = 'product added to cart!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>ReadMe</title>

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Tailwind CSS -->
   <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">
   
<?php include 'header.php'; ?>

<!-- Hero Section -->
<section class="bg-cover bg-center h-screen md:h-[100vh] flex items-center justify-center" style="background-image: url('images/photo.jpg');">

   <div class="text-center text-white px-6">
      <h1 class="text-4xl md:text-6xl bg-gray-800 bg-opacity-50 py-4 test-white font-bold mb-4">Your path to a <strong><em> Bright Mind</em> </strong>!</h1>
      
   </div>
</section>

<!-- Products Section -->
<section class="py-12">
   <div class="container mx-auto">
      <h2 class="text-3xl font-semibold text-center mb-8">Latest Products</h2>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
         <?php  
            $select_products = mysqli_query($conn, "SELECT * FROM `products` LIMIT 6") or die('query failed');
            if(mysqli_num_rows($select_products) > 0){
               while($fetch_products = mysqli_fetch_assoc($select_products)){
         ?>
         <form action="" method="post" class="bg-white rounded-lg shadow-lg p-6 text-center">
            <img class="w-full h-48 object-cover rounded-t-lg mb-4" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="Product Image">
            <h3 class="text-lg font-semibold"><?php echo $fetch_products['name']; ?></h3>
            <p class="text-gray-600 mb-2">DZD <?php echo $fetch_products['price']; ?></p>
            <input type="number" min="1" name="product_quantity" value="1" class="border border-gray-300 rounded-lg px-4 py-2 mb-4 w-full focus:outline-none focus:ring focus:ring-blue-300">
            <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
            <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
            <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
            <button type="submit" name="add_to_cart" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition">Add to Cart</button>
         </form>
         <?php
            }
         } else {
            echo '<p class="text-center text-gray-500 col-span-3">No products added yet!</p>';
         }
         ?>
      </div>

      <div class="mt-8 text-center">
         <a href="shop.php" class="inline-block bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-gray-900 transition">Load More</a>
      </div>
   </div>
</section>

<!-- Custom JS -->
<script src="js/script.js"></script>

</body>
</html>
