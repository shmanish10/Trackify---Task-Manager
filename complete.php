<?php
// complete.php - SECURE VERSION
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: signupForm.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "user_db";
$conn = @mysqli_connect($servername, $username, $password, $database);

if (isset($_GET['id']) && $conn) {
    $taskId = intval($_GET['id']); // Sanitize input
    $userId = intval($_SESSION['user_id']);

    // Check if the task belongs to the logged-in user
    $checkQuery = "SELECT * FROM tasks WHERE id = $taskId AND user_id = $userId";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        // Safe to update status
        $updateQuery = "UPDATE tasks SET status = 'Completed' WHERE id = $taskId AND user_id = $userId";
        if (mysqli_query($conn, $updateQuery)) {
            $_SESSION['message'] = "<p style='color:green;'>Task marked as completed successfully!</p>";
        } else {
            $_SESSION['message'] = "<p style='color:red;'>Error updating task: " . mysqli_error($conn) . "</p>";
        }
    } else {
        // Task not found or doesn't belong to user
        $_SESSION['message'] = "<p style='color:red;'>Unauthorized or invalid task ID.</p>";
    }

    header("Location: userForm.php#dataPage");
    exit();
}
?>
