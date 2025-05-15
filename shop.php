<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];
if(!isset($user_id)){
   header('location:login.php');
}

$search_item = '';
$order_by = '';

if(isset($_POST['submit'])) {
   $search_item = $_POST['search'];
   $order_by = $_POST['order'] ?? '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shop</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>

<?php include 'header.php'; ?>

<!-- Search & Sort Form -->
<section class="search-form p-4">
   <form action="" method="post" class="flex flex-col md:flex-row justify-center items-center gap-4">
      <input type="text" name="search" value="<?php echo $search_item; ?>" placeholder="Search products..." class="w-full max-w-md p-3 rounded-lg border-2 border-gray-300">
      
      <select name="order" class="p-3 rounded-lg border-2 border-gray-300">
         <option value="">-- Trier par prix --</option>
         <option value="asc" <?php if($order_by == 'asc') echo 'selected'; ?>>Prix croissant</option>
         <option value="desc" <?php if($order_by == 'desc') echo 'selected'; ?>>Prix décroissant</option>
      </select>

      <input type="submit" name="submit" value="Search" class="btn px-6 py-3 bg-blue-600 text-white rounded-lg cursor-pointer hover:bg-blue-700 transition duration-200">
   </form>
</section>

<!-- Products Section -->
<section class="products py-12 bg-gray-100">
   <h1 class="text-4xl font-bold text-center mb-8">Liste des Livres</h1>

   <div class="box-container grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

   <?php  
      $query = "SELECT * FROM products";

      if ($search_item != '') {
         $query .= " WHERE name LIKE '%$search_item%'";
      }

      if ($order_by == 'asc') {
         $query .= " ORDER BY price ASC";
      } elseif ($order_by == 'desc') {
         $query .= " ORDER BY price DESC";
      }

      $select_products = mysqli_query($conn, $query) or die('query failed');

      if(mysqli_num_rows($select_products) > 0){
         while($fetch_products = mysqli_fetch_assoc($select_products)){
   ?>
        <form action="" method="post" class="box bg-white shadow-md rounded-lg overflow-hidden">
   <a href="product_detail.php?id=<?php echo $fetch_products['id']; ?>">
      <img class="w-full h-48 object-cover" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
   </a>
   <div class="p-4">
      <h3 class="text-xl font-semibold">
         <a href="product_detail.php?id=<?php echo $fetch_products['id']; ?>">
            <?php echo $fetch_products['name']; ?>
         </a>
      </h3>
      <p class="text-gray-500 mt-1">DZD <?php echo $fetch_products['price']; ?></p>
      <p class="text-gray-600 mt-2"><?php echo $fetch_products['description']; ?></p>
      <a href="product_detail.php?id=<?php echo $fetch_products['id']; ?>" class="block text-center mt-2 text-blue-500 hover:underline">
         Voir les détails
      </a>

      <input type="number" min="1" name="product_quantity" value="1" class="mt-4 border rounded-lg px-2 py-1 w-full">
      <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
      <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
      <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
      <input type="submit" value="Ajouter au panier" name="add_to_cart" class="btn mt-4 w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
   </div>
</form>



   <?php
         }
      } else {
         echo '<p class="col-span-full text-center text-gray-500">Aucun produit trouvé !</p>';
      }
   ?>
   </div>
</section>

<!-- Cart Items Section -->
<section class="cart-items p-6 bg-white shadow-inner mt-6 max-w-5xl mx-auto rounded-lg">
   <h2 class="text-2xl font-bold mb-4">Votre panier</h2>
   <?php
   // Afficher les produits dans le panier ici si nécessaire
   ?>
   <div class="continue-shopping mt-4">
      <a href="shop.php" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Continue Shopping</a>
   </div>
</section>

<!-- Formulaire vers cart.php -->
<form action="cart.php" method="post" class="p-6 bg-gray-50 shadow-inner mt-6 max-w-4xl mx-auto rounded-lg">
   <!-- Contenu du panier si nécessaire -->
   <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Voir le panier</button>

   <div class="mt-4">
      <a href="shop.php" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Continue Shopping</a>
   </div>
</form>

<!-- Footer -->
<footer class="page-footer p-6 bg-gray-800 text-white text-center mt-12">
   <p>&copy; <?php echo date('Y'); ?> Mon Site E-commerce</p>
   <div class="continue-shopping mt-4">
      <a href="shop.php" class="px-4 py-2 bg-green-500 rounded hover:bg-green-600">Continue Shopping</a>
   </div>
</footer>

<script src="js/script.js"></script>
</body>
</html>

