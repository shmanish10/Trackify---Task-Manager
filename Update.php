<?php
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signupForm.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "user_db";
$conn = mysqli_connect($servername, $username, $password, $database);

$response = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_task'])) {
    $id = intval($_POST['update_id']); // Sanitize task ID
    $task_name = trim($_POST['update_task_name']);
    $task_details = trim($_POST['update_task_details']);
    $due_date = $_POST['update_due_date'];
    $priority = $_POST['update_priority'];
    $user_id = intval($_SESSION['user_id']);

    if (empty($task_name) || empty($task_details) || empty($due_date) || empty($priority)) {
        $response = "<p style='color:red;'>Please fill all the update fields.</p>";
    } else {
        // Check if task belongs to the logged-in user
        $check_sql = "SELECT * FROM tasks WHERE id = $id AND user_id = $user_id";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            // Proceed with update
            $update_sql = "UPDATE tasks SET 
                task_name = '$task_name',
                task_details = '$task_details',
                due_date = '$due_date',
                priority = '$priority'
                WHERE id = $id AND user_id = $user_id";

            if (mysqli_query($conn, $update_sql)) {
                $response = "<p style='color:green;'>Task updated successfully!</p>";
            } else {
                $response = "<p style='color:red;'>Error updating task: " . mysqli_error($conn) . "</p>";
            }
        } else {
            $response = "<p style='color:red;'>Unauthorized action or task not found.</p>";
        }
    }
}

$_SESSION['message'] = $response;
header("Location: userForm.php#dataPage");
exit();
?>
