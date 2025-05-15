<?php

include 'config.php';

if(isset($_POST['submit'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
   $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
   $user_type = $_POST['user_type'];

   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('query failed');

   if(mysqli_num_rows($select_users) > 0){
      $message[] = 'user already exist!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         mysqli_query($conn, "INSERT INTO `users`(name, email, password, user_type) VALUES('$name', '$email', '$cpass', '$user_type')") or die('query failed');
         $message[] = 'registered successfully!';
         header('location:login.php');
      }
   }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>
   <!-- Tailwind CSS -->
   <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center mt-5 mb-5">

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
      <h3 class="text-2xl font-bold text-center text-gray-800">Register Now</h3>
      
      <div>
         <label for="name" class="block text-gray-600">Name</label>
         <input type="text" name="name" id="name" 
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-300"
                placeholder="Enter your name" required>
      </div>

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
      
      <div>
         <label for="cpassword" class="block text-gray-600">Confirm Password</label>
         <input type="password" name="cpassword" id="cpassword" 
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-300"
                placeholder="Confirm your password" required>
      </div>
      
      <div>
         <label for="user_type" class="block text-gray-600">User Type</label>
         <select name="user_type" id="user_type" 
                 class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-300">
            <option value="user">User</option>
            <option value="admin">Admin</option>
         </select>
      </div>

      <input type="submit" name="submit" value="Register Now" 
             class="w-full bg-blue-500 text-white py-3 rounded-lg font-semibold hover:bg-blue-600 cursor-pointer">
      
      <p class="text-center text-gray-600">
         Already have an account? 
         <a href="login.php" class="text-blue-500 hover:underline">Login Now</a>
      </p>
   </form>
</div>

</body>
</html>
