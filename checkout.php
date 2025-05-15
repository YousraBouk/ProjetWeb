<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location:login.php');
}

$cart_total = 0;
$cart_products = [];

if (isset($_POST['order_btn'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = $_POST['number'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $adress = mysqli_real_escape_string($conn, 'Flat No. ' . $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code']);
    $placed_on = date('d-M-Y');

    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    while ($cart_item = mysqli_fetch_assoc($cart_query)) {
        $product_id = $cart_item['product_id'];
        $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ')';
        $sub_total = $cart_item['price'] * $cart_item['quantity'];
        $cart_total += $sub_total;
    }

    $total_products = implode(', ', $cart_products);

    $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$adress' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

    if ($cart_total == 0) {
        $message[] = 'Your cart is empty';
    } else {
        if (mysqli_num_rows($order_query) > 0) {
            $message[] = 'Order already placed!';
        } else {
                     mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$adress', '$total_products', '$cart_total', '$placed_on')") or die('query failed');

                      // Décrémenter le stock
                       $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                       while ($cart_item = mysqli_fetch_assoc($cart_query)) {
                           $product_id = $cart_item['product_id'];
                           $quantity = $cart_item['quantity'];
                            var_dump($product_id, $quantity);
 
                            $product_id = trim($product_id);
                            $quantity = trim($quantity);

                             if (is_numeric($product_id) && is_numeric($quantity) && (int)$product_id > 0 && (int)$quantity > 0) {
                              $product_id = (int) $product_id;
                              $quantity = (int) $quantity;
  
                                 mysqli_query($conn, "UPDATE `products` SET stock = stock - $quantity WHERE id = $product_id") or die('Erreur de mise à jour du stock');
                            } else {
                                    die('ID produit ou quantité invalide');
                                   }
                        }
                }

                $message[] = 'Order placed successfully!';
               mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        }
    } 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<?php include 'header.php'; ?>

<div class="heading bg-blue-500 text-white py-4 text-center">
    <h3 class="text-2xl font-semibold">Checkout</h3>
    <p class="mt-2"><a href="home.php" class="underline">Home</a> / Checkout</p>
</div>

<section class="display-order container mx-auto mt-8 p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
    <?php
    $grand_total = 0;
    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    if (mysqli_num_rows($select_cart) > 0) {
        while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
            $total_price = $fetch_cart['price'] * $fetch_cart['quantity'];
            $grand_total += $total_price;
    ?>
        <p class="mb-2 text-gray-700"> 
            <span class="font-semibold"><?php echo $fetch_cart['name']; ?></span> 
            <span class="text-gray-500">(<?php echo 'DZD ' . $fetch_cart['price']  . ' x ' . $fetch_cart['quantity']; ?>)</span>
        </p>
    <?php
        }
    } else {
        echo '<p class="text-center text-gray-500">Your cart is empty</p>';
    }
    ?>
    <div class="grand-total text-lg font-semibold mt-4">
        Grand Total: <span class="text-green-600">DZD <?php echo $grand_total; ?></span>
    </div>
</section>

<section class="checkout container mx-auto mt-8 p-6 bg-white rounded-lg mb-4 shadow-md">
    <form action="" method="post" class="space-y-6">
        <h3 class="text-xl font-semibold text-gray-800">Place Your Order</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="inputBox">
                <label class="block text-gray-700">Your Name:</label>
                <input type="text" name="name" required placeholder="Enter your name" class="w-full p-2 border border-gray-300 rounded">
            </div>
            <div class="inputBox">
                <label class="block text-gray-700">Your Number:</label>
                <input type="number" name="number" required placeholder="Enter your number" class="w-full p-2 border border-gray-300 rounded">
            </div>
            <div class="inputBox">
                <label class="block text-gray-700">Your Email:</label>
                <input type="email" name="email" required placeholder="Enter your email" class="w-full p-2 border border-gray-300 rounded">
            </div>
            <div class="inputBox">
                <label class="block text-gray-700">Payment Method:</label>
                <select name="method" class="w-full p-2 border border-gray-300 rounded">
                    <option value="cash on delivery">Cash on Delivery</option>
                    <option value="credit card">Credit Card</option>
                    <option value="paypal">Paypal</option>
                    <option value="paytm">Paytm</option>
                </select>
            </div>
            <div class="inputBox">
                <label class="block text-gray-700">Flat No.:</label>
                <input type="number" name="flat" required placeholder="e.g. Flat No." class="w-full p-2 border border-gray-300 rounded">
            </div>
            <div class="inputBox">
                <label class="block text-gray-700">Street Name:</label>
                <input type="text" name="street" required placeholder="e.g. Street Name" class="w-full p-2 border border-gray-300 rounded">
            </div>
            <div class="inputBox">
                <label class="block text-gray-700">City:</label>
                <input type="text" name="city" required placeholder="e.g. Algiers" class="w-full p-2 border border-gray-300 rounded">
            </div>
            <div class="inputBox">
                <label class="block text-gray-700">State:</label>
                <input type="text" name="state" required placeholder="e.g. Wilaya of Algiers" class="w-full p-2 border border-gray-300 rounded">
            </div>
            <div class="inputBox">
                <label class="block text-gray-700">Country:</label>
                <input type="text" name="country" required placeholder="e.g. Algeria" class="w-full p-2 border border-gray-300 rounded">
            </div>
            <div class="inputBox">
                <label class="block text-gray-700">Pin Code:</label>
                <input type="number" name="pin_code" required placeholder="e.g. 16000" class="w-full p-2 border border-gray-300 rounded">
            </div>
        </div>
        <input type="submit" value="Order Now" name="order_btn" class="bg-blue-500 text-white px-6 py-2 rounded shadow hover:bg-blue-600">
    </form>
</section>

<script src="js/script.js"></script>

</body>
</html>
