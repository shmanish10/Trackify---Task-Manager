<?php
session_start();

// DB connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "user_db";
$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    $_SESSION['message'] = "Database connection failed.";
    header("Location: loginForm.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $_SESSION['message'] = "Please fill all the fields.";
        header("Location: loginForm.php");
        exit();
    }

    // Check if user exists
    $email = mysqli_real_escape_string($conn, $email);
    $sql = "SELECT * FROM accounts WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Successful login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            // Redirect to protected page or user dashboard
            header("Location: userForm.php");
            exit();
        } else {
            $_SESSION['message'] = "Incorrect password.";
            header("Location: loginForm.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "User with this email does not exist.";
        header("Location: loginForm.php");
        exit();
    }
} else {
    header("Location: loginForm.php");
    exit();
}
