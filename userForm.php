<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['user_id'])) {
    header("Location: signupForm.php");
    exit();
}

// Disable error reporting
mysqli_report(MYSQLI_REPORT_OFF);
$message = "";

// DB connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "user_db";
$conn = @mysqli_connect($servername, $username, $password, $database);

// Initialize variables
$task_name = $task_details = $due_date = $priority = "";

if (!$conn) {
    $message = "<p style='color:red;'>Unable to store data in database due to connection error.</p>";
} else {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (
            isset($_POST['task_name']) && isset($_POST['task_details']) &&
            isset($_POST['due_date']) && isset($_POST['priority'])
        ) {
            $task_name = trim($_POST['task_name']);
            $task_details = trim($_POST['task_details']);
            $due_date = $_POST['due_date'];
            $priority = $_POST['priority'];
            $user_id = $_SESSION['user_id'];

            if (empty($task_name) || empty($task_details) || empty($due_date) || empty($priority)) {
                $message = "<p style='color:red;'>Please fill all the fields.</p>";
            } else {
                $sql = "INSERT INTO tasks (task_name, task_details, due_date, priority, user_id) 
                        VALUES ('$task_name', '$task_details', '$due_date', '$priority', '$user_id')";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    $message = "<p style='color:green;'>Task added successfully!</p>";
                } else {
                    $message = "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
                }
            }
        }
    }

    // Fetch data specific to logged-in user
    $user_id = $_SESSION['user_id'];
    $userdata = mysqli_query($conn, "SELECT * FROM tasks WHERE user_id = $user_id ORDER BY id ASC");
    $rows = mysqli_num_rows($userdata);
    $countMessage = $userdata && $rows > 0
        ? "<p style='color:darkblue;'>Total number of tasks: $rows</p>"
        : "<p style='color:red;'>No tasks found or error fetching data: " . mysqli_error($conn) . "</p>";
}

$accountName = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Trackify</title>
    <link rel="stylesheet" href="userForm.css">
    <script src="userForm.js"></script> 
</head>
<body>

<nav class="navbar">
    <div class="logoName">
        <p>Trackify</p>
    </div>

    <div class="nav-links">
        <a class="nav-btn" href="#homePage">Home</a>
        <a class="nav-btn" href="#dataPage">Tasks</a>
    </div>

    <div class="user-section">
        <div class="dropdown">
            <div class="user-icon" onclick="toggleDropdown()">
                <span class="accountName"><?php echo htmlspecialchars($accountName); ?></span>
                <img src="https://img.freepik.com/free-vector/user-circles-set_78370-4704.jpg?semt=ais_hybrid&w=740" alt="User Icon">
            </div>
            <div id="dropdownMenu" class="dropdown-menu">
                <a href="logout.php" class="dropdown-link" onclick="return confirm('Are you sure you want to Log-Out your account?')">Logout</a>
                <a href="deleteAccount.php" class="dropdown-link" onclick="return confirm('Are you sure you want to delete your account?')">Delete Account</a>
            </div>
        </div>
    </div>
</nav>

<section id="homePage">
    <h2 id="pageHeading">Welcome to Your Personal To-Do Task Manager</h2>

    <form action="userForm.php" method="post">
        <h2 id="formHeading">Add New Task</h2>

        <label for="task_name">Task Name</label>
        <input type="text" id="task_name" name="task_name" placeholder="Enter task name" required>

        <label for="task_details">Task Details</label>
        <textarea id="task_details" name="task_details" rows="5" cols="30" placeholder="Enter task details" required></textarea>

        <label for="due_date">Due Date</label>
        <input type="date" id="due_date" name="due_date" required>

        <label for="priority">Priority</label>
        <select id="priority" name="priority" required>
            <option value="">--Select Priority--</option>
            <option value="High">High</option>
            <option value="Medium">Medium</option>
            <option value="Low">Low</option>
        </select>

        <div class="message"><?php echo $message; ?></div>
        

        <button class="form-btn" type="submit">Submit</button>
    </form>
</section>

<section id="dataPage">
    <h2 id="dataHeading">Task Management Dashboard</h2>
    <div><?php echo $countMessage; ?></div>
    <div>
        <?php 
        if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
        } 
        ?>
    </div>

    <table>
        <thead>
            <tr>
                <th >Id</th>
                <th>Task Name</th>
                <th>Task Details</th>
                <th>Due Date</th>
                <th>Priority</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $Counter = 1;
            while ($row = mysqli_fetch_assoc($userdata)) {
                $isCompleted = strtolower($row['status']) === 'completed';
                $disabled = $isCompleted ? "disabled" : "";
                $rowClass = $isCompleted ? "completed-row" : "";

                echo "<tr class='$rowClass'>";
                echo "<td>$Counter</td>";
                echo "<td>{$row['task_name']}</td>";
                echo "<td>{$row['task_details']}</td>";
                echo "<td>{$row['due_date']}</td>";
                echo "<td>{$row['priority']}</td>";
                echo "<td>";
                echo "<button class='btn update' data-id='{$row['id']}' data-task='{$row['task_name']}' data-details='{$row['task_details']}' data-date='{$row['due_date']}' data-priority='{$row['priority']}' $disabled onclick='openUpdateModal(this)'>Update</button>";
                echo "<a class='btn delete' href='delete.php?id={$row['id']}' onclick=\"return confirm('Are you sure?')\" $disabled>Delete</a>";
                if (!$isCompleted) {
                    echo "<a class='btn complete' href='complete.php?id={$row['id']}'>Completed</a>";
                }
                echo "</td></tr>";
                $Counter++;
            }
            ?>
        </tbody>
    </table>
</section>

<footer> 
    <p>&copy; <?php echo date("Y"); ?> To-Do Task Manager. All rights reserved. Made with ❤️ by <?php echo htmlspecialchars($accountName); ?> </p> 
</footer>


<!-- Modal for Update Task -->
<div id="updateModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Update Task</h2>
                <button class="close-btn" onclick="closeModal()" type="button">&times;</button>
            </div>
            
            <div class="modal-body">
                <form method="POST" action="Update.php">
                    <input type="hidden" name="update_id" id="update_id">

                    <div class="form-group">
                        <label for="update_task_name">Task Name</label>
                        <input type="text" name="update_task_name" id="update_task_name" placeholder="Enter task name" required>
                    </div>

                    <div class="form-group">
                        <label for="update_task_details">Task Details</label>
                        <textarea name="update_task_details" id="update_task_details" placeholder="Describe your task in detail..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="update_due_date">Due Date</label>
                        <input type="date" name="update_due_date" id="update_due_date" required>
                    </div>

                    <div class="form-group">
                        <label for="update_priority">Priority</label>
                        <select name="update_priority" id="update_priority" required>
                            <option value="">--Select Priority-- </option>
                            <option value="High">High </option>
                            <option value="Medium">Medium </option>
                            <option value="Low">Low </option>
                        </select>
                    </div>

                    <div class="btn-container">
                        <button type="button" class="btn cancel" onclick="closeModal()">Cancel</button>
                        <button type="submit" class="btn btn-update" name="update_task">Update Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
