<?php
session_start();

// Connect to database
$servername = "localhost";
$username = "root";
$password = "";
$database = "user_db";

$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    $_SESSION['message'] = "<p class='message'>Database connection failed.</p>";
    header("Location: signupForm.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Sanitize input
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['message'] = "<p class='message'>All fields are required.</p>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "<p class='message'>Invalid email format.</p>";
    } elseif ($password !== $confirm_password) {
        $_SESSION['message'] = "<p class='message'>Passwords do not match.</p>";
    } else {
        // Check if email already exists
        $check_query = "SELECT id FROM accounts WHERE email = '$email'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $_SESSION['message'] = "<p class='message'>Email already registered.Please click on Login button!</p>";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $insert_query = "INSERT INTO accounts (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
            $insert_result = mysqli_query($conn, $insert_query);

            if ($insert_result) {
               header("Location: loginForm.php");
                $_SESSION['message'] = "<p class='message' style='color:green;'>Signup successful! Please log in.</p>";
            } else {
                $_SESSION['message'] = "<p class='message'>Signup failed. Try again.</p>";
            }
        }
    }
}

// Redirect back to form to show message
header("Location: signupForm.php");
exit();
