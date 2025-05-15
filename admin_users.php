<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `users` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_users.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>users</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="users py-12">

   <h1 class="title text-4xl font-bold text-center mb-8">User Accounts</h1>

   <div class="box-container grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

      <?php
         $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type != 'admin' ") or die('query failed');
         while($fetch_users = mysqli_fetch_assoc($select_users)){
      ?>
      <div class="box bg-white rounded-lg shadow-lg p-6">
         <p class="text-gray-800">User ID: <span class="font-bold"><?php echo $fetch_users['id']; ?></span></p>
         <p class="text-gray-800">Username: <span class="font-bold"><?php echo $fetch_users['name']; ?></span></p>
         <p class="text-gray-800">Email: <span class="font-bold"><?php echo $fetch_users['email']; ?></span></p>
         <p class="text-gray-800">User Type: <span class="<?php if($fetch_users['user_type'] == 'admin'){ echo 'text-orange-500'; } ?>"><?php echo $fetch_users['user_type']; ?></span></p>
         <a href="admin_users.php?delete=<?php echo $fetch_users['id']; ?>" onclick="return confirm('Delete this user?');" class="delete-btn bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg mt-4 block text-center">Delete User</a>
      </div>
      <?php
         };
      ?>

   </div>

</section>

<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>