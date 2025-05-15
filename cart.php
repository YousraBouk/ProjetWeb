<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

// Check if user is logged in
if(!isset($user_id)){
    header('location:login.php');
}

if(isset($_POST['update_cart'])){
    $cart_id = $_POST['cart_id'];
    $cart_quantity = $_POST['cart_quantity'];
    mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id'") or die('query failed');
    $message[] = 'cart quantity updated!';
}

// Delete a single item from the cart
if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die('query failed');
    header('location:cart.php');
}

// Delete all items from the cart
if(isset($_GET['delete_all'])){
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    header('location:cart.php');
}

// Handling cart using cookies if the user is not logged in
if(!isset($user_id)) {
    // If the cart cookie doesn't exist, initialize it
    if (!isset($_COOKIE['cart'])) {
        $cart_items = [];
        setcookie('cart', json_encode($cart_items), time() + (86400 * 30), "/"); // 30 days expiry
    } else {
        $cart_items = json_decode($_COOKIE['cart'], true);
    }

    // Update the cart in cookies
    if (isset($_POST['update_cart'])) {
        $cart_id = $_POST['cart_id'];
        $cart_quantity = $_POST['cart_quantity'];

        foreach ($cart_items as &$item) {
            if ($item['cart_id'] == $cart_id) {
                $item['quantity'] = $cart_quantity;
                break;
            }
        }

        // Update the cookie with the new cart data
        setcookie('cart', json_encode($cart_items), time() + (86400 * 30), "/");
        $message[] = 'cart quantity updated!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>cart</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Tailwind CSS -->
   <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

</head>
<body>

<?php include 'header.php'; ?>

<div class="heading bg-gray-200 py-6 text-center">
   <h3 class="text-3xl font-bold">shopping cart</h3>
   <p class="mt-2 text-gray-600"> <a href="home.php" class="text-blue-400 hover:underline">home</a> / cart </p>
</div>

<section class="shopping-cart py-12">

   <h1 class="text-4xl font-bold text-center mb-8">Products Added</h1>

   <div class="box-container grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

  <?php
     $grand_total = 0;

     // If the user is logged in, get the cart from the database
     if(isset($user_id)) {
        $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        if(mysqli_num_rows($select_cart) > 0){
           while($fetch_cart = mysqli_fetch_assoc($select_cart)){   
  ?>
  <div class="box bg-white shadow-md rounded-lg overflow-hidden">
     <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times text-red-500 hover:text-red-600 cursor-pointer" onclick="return confirm('delete this from cart?');"></a>
     <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="" class="w-full h-48 object-cover">
     <div class="p-4">
        <div class="name font-semibold text-lg"><?php echo $fetch_cart['name']; ?></div>
        <div class="price text-gray-500 mt-2">DZD <?php echo $fetch_cart['price']; ?></div>
        <form action="" method="post" class="mt-4">
           <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
           <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>" class="border border-gray-300 rounded-lg w-full px-4 py-2">
           <input type="submit" name="update_cart" value="update" class="option-btn bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg mt-2">
        </form>
        <div class="sub-total mt-4 text-gray-700">Sub Total: <span class="font-bold">DZD <?php echo $sub_total = ($fetch_cart['quantity'] * $fetch_cart['price']); ?></span></div>
     </div>
  </div>
  <?php
  $grand_total += $sub_total;
     }
  } else {
     echo '<p class="col-span-full text-center text-gray-500">Your cart is empty</p>';
  }

  // If the user is not logged in, get the cart from cookies
  } else {
     if(count($cart_items) > 0) {
        foreach($cart_items as $item) {
            ?>
            <div class="box bg-white shadow-md rounded-lg overflow-hidden">
               <a href="cart.php?delete=<?php echo $item['cart_id']; ?>" class="fas fa-times text-red-500 hover:text-red-600 cursor-pointer" onclick="return confirm('delete this from cart?');"></a>
               <img src="uploaded_img/<?php echo $item['image']; ?>" alt="" class="w-full h-48 object-cover">
               <div class="p-4">
                  <div class="name font-semibold text-lg"><?php echo $item['name']; ?></div>
                  <div class="price text-gray-500 mt-2">DZD <?php echo $item['price']; ?></div>
                  <form action="" method="post" class="mt-4">
                     <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                     <input type="number" min="1" name="cart_quantity" value="<?php echo $item['quantity']; ?>" class="border border-gray-300 rounded-lg w-full px-4 py-2">
                     <input type="submit" name="update_cart" value="update" class="option-btn bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg mt-2">
                  </form>
                  <div class="sub-total mt-4 text-gray-700">Sub Total: <span class="font-bold">DZD <?php echo $sub_total = ($item['quantity'] * $item['price']); ?></span></div>
               </div>
            </div>
            <?php
            $grand_total += $sub_total;
        }
     } else {
        echo '<p class="col-span-full text-center text-gray-500">Your cart is empty</p>';
     }
  }
  ?>

   </div>

   <div class="text-center mt-8">
      <a href="cart.php?delete_all" class="delete-btn bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg <?php echo ($grand_total > 1)?'':'opacity-50 cursor-not-allowed'; ?>" onclick="return confirm('delete all from cart?');">Delete All</a>
   </div>

   <div class="cart-total mt-12 bg-gray-100 p-6 rounded-lg shadow-md">
      <p class="text-xl font-semibold">Grand Total: <span class="text-green-500">DZD <?php echo $grand_total; ?></span></p>
      <div class="flex justify-between items-center mt-6">
         <a href="shop.php" class="option-btn bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">Continue Shopping</a>
         <a href="checkout.php" class="btn bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg <?php echo ($grand_total > 1)?'':'opacity-50 cursor-not-allowed'; ?>">Proceed to Checkout</a>
      </div>
   </div>

</section>

<!-- custom js file link -->
<script src="js/script.js"></script>

</body>
</html>

