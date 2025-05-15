<?php
include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_products.php');
}

if(isset($_POST['add_product'])){
   $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
   $product_price = $_POST['product_price'];
   $product_description = mysqli_real_escape_string($conn, $_POST['product_description']);
   $product_image = $_FILES['product_image']['name'];
   $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
   $product_image_folder = 'uploaded_img/'.$product_image;
   $product_stock = $_POST['product_stock'];
   $insert_product = mysqli_query($conn, "INSERT INTO `products`(name, price, description, image, stock) VALUES('$product_name', '$product_price', '$product_description', '$product_image', '$product_stock')");

   // Vérifie si le produit existe déjà
   $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$product_name'") or die(mysqli_error($conn));

   if(mysqli_num_rows($select_product_name) > 0){
      $message[] = 'Le nom du produit est déjà ajouté.';
   }else{
      if($_FILES['product_image']['size'] > 5000000){
         $message[] = 'La taille de l\'image est trop grande.';
      }else{
         $insert_product = mysqli_query($conn, "INSERT INTO `products`(name, price, description, image) VALUES('$product_name', '$product_price', '$product_description', '$product_image')") or die(mysqli_error($conn));
         if($insert_product){
            move_uploaded_file($product_image_tmp_name, $product_image_folder);
            $message[] = 'Produit ajouté avec succès!';
         }else{
            $message[] = 'Le produit n\'a pas pu être ajouté!';
         }
      }
   }
}

if(isset($_POST['update_product'])){
   $update_p_id = $_POST['update_p_id'];
   $update_name = $_POST['update_name'];
   $update_price = $_POST['update_price'];
   $update_description = $_POST['update_description'];

   mysqli_query($conn, "UPDATE `products` SET stock = stock  WHERE id = $update_p_id") or die('Erreur de mise à jour du stock');


   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_folder = 'uploaded_img/'.$update_image;
   $update_old_image = $_POST['update_old_image'];

   if(!empty($update_image)){
      if($update_image_size > 7000000){
         $message[] = 'La taille du fichier image est trop grande.';
      }else{
         mysqli_query($conn, "UPDATE `products` SET image = '$update_image' WHERE id = '$update_p_id'") or die('query failed');
         move_uploaded_file($update_image_tmp_name, $update_folder);
         unlink('uploaded_img/'.$update_old_image);
      }
   }

   header('location:admin_products.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<!-- Product CRUD section -->
<section class="add-products py-12">

   <h1 class="title text-4xl font-bold text-center mb-8">Shop Products</h1>

   <form action="" method="post" enctype="multipart/form-data" class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-lg">
      <h3 class="text-xl font-semibold mb-4">Add Product</h3>
      <input type="text" name="product_name" class="box w-full p-3 border border-gray-300 rounded-lg mb-4" placeholder="Enter product name" required>
      <input type="number" min="0" name="product_price" class="box w-full p-3 border border-gray-300 rounded-lg mb-4" placeholder="Enter product price" required>
      <textarea name="product_description" placeholder="Enter product description" required class="box w-full p-3 border border-gray-300 rounded-lg mb-4"></textarea>
      <input type="number" name="product_stock" required placeholder="Stock">
      <input type="file" name="product_image" accept="image/jpg, image/jpeg, image/png" class="box w-full p-3 border border-gray-300 rounded-lg mb-4" required>
      <input type="submit" value="Add Product" name="add_product" class="btn w-full bg-blue-500 hover:bg-blue-500 text-white px-6 py-3 rounded-lg cursor-pointer">
   </form>

</section>

<!-- Show products -->
<section class="show-products py-12">

   <div class="box-container grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

      <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
      <div class="box bg-white rounded-lg shadow-lg p-6">
         <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="" class="w-full h-48 object-cover rounded-lg mb-4">
         <div class="name font-semibold text-lg mb-2"><?php echo $fetch_products['name']; ?></div>
         <div class="price text-gray-700 mb-4">DZD <?php echo $fetch_products['price']; ?></div>
         <div class="flex space-x-4">
            <a href="admin_products.php?update=<?php echo $fetch_products['id']; ?>" class="option-btn bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">Update</a>
            <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg"  >Delete</a>
         </div>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty text-center text-gray-500">No products added yet!</p>';
      }
      ?>
   </div>

</section>

<section class="edit-product-form">

   <?php
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
   <form action="" method="post" enctype="multipart/form-data" class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-lg">
      <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
      <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
      <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="" class="w-full h-48 object-cover rounded-lg mb-4">
      <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box w-full p-3 border border-gray-300 rounded-lg mb-4" required placeholder="Enter product name">
      <input type="number" name="update_price" value="<?php echo $fetch_update['price']; ?>" min="0" class="box w-full p-3 border border-gray-300 rounded-lg mb-4" required placeholder="Enter product price">
      <textarea name="update_description" placeholder="Enter product description" class="box w-full p-3 border border-gray-300 rounded-lg mb-4"><?php echo $fetch_update['description']; ?></textarea>
      <input type="file" class="box w-full p-3 border border-gray-300 rounded-lg mb-4" name="update_image" accept="image/jpg, image/jpeg, image/png, image/webp">
      <input type="submit" value="Update" name="update_product" class="btn w-full bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg cursor-pointer">
      <input type="reset" value="Cancel" id="close-update" onclick="window.location.href='admin_products.php';" class="btn w-full bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg mt-4 cursor-pointer">
      <input type="number" min="0" name="product_stock" class="box w-full p-3 border border-gray-300 rounded-lg mb-4" placeholder="Enter stock quantity" required>
   </form>
   <?php
         }
      }
      }else{
         echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
      }
   ?>

</section>

<!-- custom admin js file link -->
<script src="js/admin_script.js"></script>

</body>
</html>
