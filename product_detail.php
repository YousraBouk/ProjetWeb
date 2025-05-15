<?php
include 'config.php';

if (isset($_GET['id'])) {
   $id = $_GET['id'];
   $select_product = mysqli_query($conn, "SELECT * FROM products WHERE id = '$id'") or die('query failed');
   
   if (mysqli_num_rows($select_product) > 0) {
      $fetch_product = mysqli_fetch_assoc($select_product);
   } else {
      echo "<p>Produit introuvable.</p>";
      exit;
   }
} else {
   echo "<p>Aucun produit sélectionné.</p>";
   exit;
}
?>

<h1><?php echo $fetch_product['name']; ?></h1>
<img src="uploaded_img/<?php echo $fetch_product['image']; ?>" alt="">
<p><?php echo $fetch_product['description']; ?></p>
<p>Prix : <?php echo $fetch_product['price']; ?> DZD</p>
