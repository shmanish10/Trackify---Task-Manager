<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "user_db";

$conn = mysqli_connect($servername, $username, $password, $database);


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_SESSION['email'])) {
    $email = trim($_SESSION['email']);

    // ✅ Delete the user
    $sql = "DELETE FROM accounts WHERE email = '$email'";
    if (mysqli_query($conn, $sql)) {
        session_unset();
        session_destroy();
        header("Location: signupForm.php");
        exit();
    } else {
        echo "Error deleting account: " . mysqli_error($conn);
    }
} else {
    header("Location: signupForm.php");
    exit();
}
