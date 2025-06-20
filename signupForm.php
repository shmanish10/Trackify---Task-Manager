<?php 
session_start(); 

if (isset($_SESSION['email'])) {
    header("Location: userForm.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Signup Form</title>
  <link rel="stylesheet" href="signupForm.css">
</head>
<body>
  <div class="welcome">Welcome to the TO-DO Management App</div>

  <form action="signupLogic.php" method="POST">
    <h2>Signup</h2>
    <input type="text" name="username" placeholder="Enter Username" required>
    <input type="email" name="email" placeholder="Enter Email" required>
    <input type="password" name="password" placeholder="Enter Password" required>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required>

    <div class="message">
      <?php 
       // Display session message if exists
        if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']); 
      } ?>
    </div>
      
    <button type="submit" id="submit">Sign Up</button>
    <p>Already have an account? Click on Login button - </p>
    <button type="button" id="login" onclick="window.location.href='loginForm.php'">Login</button>
  </form>

</body>
</html>