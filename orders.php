<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>

    <!-- Font Awesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
   
<?php include 'header.php'; ?>

<div class="heading bg-blue-500 py-4 text-white text-center">
    <h3 class="text-2xl font-semibold">Your Orders</h3>
    <p class="mt-2"><a href="home.php" class="text-white underline">Home</a> / Orders</p>
</div>

<section class="placed-orders py-12">
    <h1 class="text-4xl font-bold text-center text-gray-800 mb-8">Placed Orders</h1>

    <div class="container mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php
        $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id'") or die('query failed');
        if (mysqli_num_rows($order_query) > 0) {
            while ($fetch_orders = mysqli_fetch_assoc($order_query)) {
        ?>
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <p class="text-gray-600 mb-2"><span class="font-semibold">Placed On:</span> <?php echo $fetch_orders['placed_on']; ?></p>
            <p class="text-gray-600 mb-2"><span class="font-semibold">Name:</span> <?php echo $fetch_orders['name']; ?></p>
            <p class="text-gray-600 mb-2"><span class="font-semibold">Number:</span> <?php echo $fetch_orders['number']; ?></p>
            <p class="text-gray-600 mb-2"><span class="font-semibold">Email:</span> <?php echo $fetch_orders['email']; ?></p>
            <p class="text-gray-600 mb-2"><span class="font-semibold">Address:</span> <?php echo $fetch_orders['adress']; ?></p>
            <p class="text-gray-600 mb-2"><span class="font-semibold">Payment Method:</span> <?php echo $fetch_orders['method']; ?></p>
            <p class="text-gray-600 mb-2"><span class="font-semibold">Your Orders:</span> <?php echo $fetch_orders['total_products']; ?></p>
            <p class="text-gray-600 mb-2"><span class="font-semibold">Total Price:</span> <span class="text-green-600 font-bold">DZD <?php echo $fetch_orders['total_price']; ?></span></p>
            <p class="text-gray-600">
                <span class="font-semibold">Payment Status:</span> 
                <span class="<?php echo $fetch_orders['payment_status'] == 'pending' ? 'text-red-500' : 'text-green-500'; ?>">
                    <?php echo ucfirst($fetch_orders['payment_status']); ?>
                </span>
            </p>
        </div>
        <?php
            }
        } else {
            echo '<p class="text-center text-gray-600">No orders placed yet!</p>';
        }
        ?>
    </div>

    <!-- Bouton Continue Shopping -->
    <div class="text-center mt-8">
        <a href="shop.php" class="btn bg-blue-500 text-white px-6 py-3 rounded-lg">
            Continue Shopping
        </a>
    </div>
</section>

<!-- Custom JS File Link -->
<script src="js/script.js"></script>

</body>
</html>
