<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['send'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $number = $_POST['number'];
   $msg = mysqli_real_escape_string($conn, $_POST['message']);

   $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('query failed');

   if(mysqli_num_rows($select_message) > 0){
      $message[] = 'message sent already!';
   }else{
      mysqli_query($conn, "INSERT INTO `message`(user_id, name, email, number, message) VALUES('$user_id', '$name', '$email', '$number', '$msg')") or die('query failed');
      $message[] = 'message sent successfully!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>contact</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="contact bg-gray-50 py-16">

   <form action="" method="post" class="max-w-lg mx-auto p-8 bg-white shadow-lg rounded-lg">
      <h3 class="text-3xl font-bold text-center mb-6">Say Something!</h3>
      
      <input type="text" name="name" required placeholder="Enter your name" class="w-full p-3 mb-4 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
      <input type="email" name="email" required placeholder="Enter your email" class="w-full p-3 mb-4 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
      <input type="number" name="number" required placeholder="Enter your number" class="w-full p-3 mb-4 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
      
      <textarea name="message" class="w-full p-3 mb-4 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500" placeholder="Enter your message" rows="5"></textarea>
      
      <input type="submit" value="Send Message" name="send" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded cursor-pointer">
   </form>

</section>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>