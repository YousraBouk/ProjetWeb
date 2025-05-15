<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `message` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_contacts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>messages</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

  

</head>
<body>
   
<?php include 'admin_header.php'; ?>
<section class="messages py-12">

   <h1 class="title text-4xl font-bold text-center mb-8">Messages</h1>

   <div class="box-container grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

      <?php
         $select_message = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
         if(mysqli_num_rows($select_message) > 0){
            while($fetch_message = mysqli_fetch_assoc($select_message)){
      ?>
      <div class="box bg-white rounded-lg shadow-lg p-6">
         <p class="text-gray-800">User ID: <span class="font-bold"><?php echo $fetch_message['user_id']; ?></span></p>
         <p class="text-gray-800">Name: <span class="font-bold"><?php echo $fetch_message['name']; ?></span></p>
         <p class="text-gray-800">Number: <span class="font-bold"><?php echo $fetch_message['number']; ?></span></p>
         <p class="text-gray-800">Email: <span class="font-bold"><?php echo $fetch_message['email']; ?></span></p>
         <p class="text-gray-800">Message: <span class="font-bold"><?php echo $fetch_message['message']; ?></span></p>
         <a href="admin_contacts.php?delete=<?php echo $fetch_message['id']; ?>" onclick="return confirm('Delete this message?');" class="delete-btn bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg mt-4 block text-center">Delete Message</a>
      </div>
      <?php
         };
      }else{
         echo '<p class="empty text-center text-gray-500">You have no messages!</p>';
      }
      ?>

   </div>

</section>
<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>