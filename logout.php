<?php 
session_start();
$_SESSION['message'] = "<p style='color:green;'>You have been logged out successfully.</p>";
session_unset(); // optionally remove other session data
session_destroy();

header("Location: signupForm.php");
exit();
?>