<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
}

if (isset($_POST['update_order'])) {
    $order_update_id = $_POST['order_id'];
    $update_payment = $_POST['update_payment'];
    mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_update_id'") or die('query failed');
    $message[] = 'Payment status has been updated!';
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id'") or die('query failed');
    header('location:admin_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
   
<?php include 'admin_header.php'; ?>

<section class="orders py-12">
    <h1 class="text-4xl font-bold text-center text-gray-800 mb-8">Placed Orders</h1>

    <div class="container mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php
        $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
        if (mysqli_num_rows($select_orders) > 0) {
            while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
        ?>
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <p class="text-gray-600 mb-2"><span class="font-semibold">User ID:</span> <?php echo $fetch_orders['user_id']; ?></p>
            <p class="text-gray-600 mb-2"><span class="font-semibold">Placed On:</span> <?php echo $fetch_orders['placed_on']; ?></p>
            <p class="text-gray-600 mb-2"><span class="font-semibold">Name:</span> <?php echo $fetch_orders['name']; ?></p>
            <p class="text-gray-600 mb-2"><span class="font-semibold">Number:</span> <?php echo $fetch_orders['number']; ?></p>
            <p class="text-gray-600 mb-2"><span class="font-semibold">Email:</span> <?php echo $fetch_orders['email']; ?></p>
            <p class="text-gray-600 mb-2"><span class="font-semibold">Address:</span> <?php echo $fetch_orders['adress']; ?></p>
            <p class="text-gray-600 mb-2"><span class="font-semibold">Total Products:</span> <?php echo $fetch_orders['total_products']; ?></p>
            <p class="text-gray-600 mb-2"><span class="font-semibold">Total Price:</span> <span class="text-green-600 font-bold">DZD <?php echo $fetch_orders['total_price']; ?></span></p>
            <p class="text-gray-600 mb-4"><span class="font-semibold">Payment Method:</span> <?php echo $fetch_orders['method']; ?></p>

            <form action="" method="post" class="space-y-4">
                <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                <div class="flex items-center space-x-4">
                    <select name="update_payment" class="w-full bg-gray-100 border border-gray-300 py-2 px-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="" selected disabled><?php echo $fetch_orders['payment_status']; ?></option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                    </select>
                    <input type="submit" value="Update" name="update_order" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 cursor-pointer">
                </div>
                <a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>" onclick="return confirm('Delete this order?');" class="block text-center bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600">
                    Delete
                </a>
            </form>
        </div>
        <?php
            }
        } else {
            echo '<p class="  text-center text-gray-600">No orders placed yet!</p>';
        }
        ?>
    </div>
</section>

<!-- Custom JS File Link -->
<script src="js/admin_script.js"></script>

</body>
</html>

