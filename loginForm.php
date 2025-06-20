<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
  <link rel="stylesheet" href="loginForm.css">
</head>
<body>

  <form action="loginLogic.php" method="POST">
    <h2>Login</h2>
    <input type="email" name="email" placeholder="Enter Email" required>
    <input type="password" name="password" placeholder="Enter Password" required>

    <div class="message">
      <?php
      if (isset($_SESSION['message'])) {
          echo $_SESSION['message'];
          unset($_SESSION['message']);
      }
      ?>
    </div>

    <button type="submit">Login</button>
  </form>

</body>
</html>