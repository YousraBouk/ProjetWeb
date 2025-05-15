<?php

include 'config.php';
session_start();

if(isset($_POST['submit'])){

   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));

   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('query failed');

   if(mysqli_num_rows($select_users) > 0){

      $row = mysqli_fetch_assoc($select_users);

      if($row['user_type'] == 'admin'){

         $_SESSION['admin_name'] = $row['name'];
         $_SESSION['admin_email'] = $row['email'];
         $_SESSION['admin_id'] = $row['id'];
         header('location:admin_page.php');

      }elseif($row['user_type'] == 'user'){

         $_SESSION['user_name'] = $row['name'];
         $_SESSION['user_email'] = $row['email'];
         $_SESSION['user_id'] = $row['id'];
         header('location:home.php');

      }

   }else{
      $message[] = 'incorrect email or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
   <!-- Tailwind CSS -->
   <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-4 py-2 rounded shadow">
         <span>'.$message.'</span>
         <button class="ml-4 text-white font-bold" onclick="this.parentElement.remove();">&times;</button>
      </div>
      ';
   }
}
?>

<div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
   <form action="" method="post" class="space-y-6">
      <h3 class="text-2xl font-bold text-center text-gray-800">Login Now</h3>
      
      <div>
         <label for="email" class="block text-gray-600">Email</label>
         <input type="email" name="email" id="email" 
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-300"
                placeholder="Enter your email" required>
      </div>
      
      <div>
         <label for="password" class="block text-gray-600">Password</label>
         <input type="password" name="password" id="password" 
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-300"
                placeholder="Enter your password" required>
      </div>

      <input type="submit" name="submit" value="Login Now" 
             class="w-full bg-blue-500 text-white py-3 rounded-lg font-semibold hover:bg-blue-600 cursor-pointer">
      
      <p class="text-center text-gray-600">
         Don't have an account? 
         <a href="register.php" class="text-blue-500 hover:underline">Register Now</a>
      </p>
   </form>
</div>

</body>
</html>
