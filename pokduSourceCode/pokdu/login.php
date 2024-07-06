<?php
// Start session
session_start();

// Include database connection file
include 'dbconn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $user_id = $_POST['user_id'];
    $user_password = $_POST['user_password'];

    // Check if the user is a student
    $student_query = "SELECT * FROM STUDENT_DETAILS WHERE student_id = '$user_id' AND student_password = '$user_password'";
    $student_result = mysqli_query($dbconn, $student_query);

    // Check if the user is a worker
    $worker_query = "SELECT * FROM WORKER_DETAILS WHERE worker_id = '$user_id' AND worker_password = '$user_password'";
    $worker_result = mysqli_query($dbconn, $worker_query);

    if (mysqli_num_rows($student_result) == 1) {
        // User found in STUDENT_DETAILS, set session variables
        $_SESSION['user_id'] = $user_id;
        $_SESSION['logged_in'] = true;

        // Redirect to student dashboard
        header('Location: studDashboard.php');
        exit();
    } elseif (mysqli_num_rows($worker_result) == 1) {
        // User found in WORKER_DETAILS, set session variables
        $_SESSION['user_id'] = $user_id;
        $_SESSION['logged_in'] = true;

        // Redirect to admin dashboard
        header('Location: adminDashboard.php');
        exit();
    } else {
        // User not found, show error
        $error = "Invalid User ID or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Signup</title>
    <link rel="stylesheet" href="style1.css"> <!-- Ensure this path is correct -->
</head>
<body>
    <div id="login-page">
        <form action="" method="POST">
            <img src="logo.png" alt="Logo" class="logo"> <!-- Ensure this path is correct -->
            <h2>Login</h2>
            <label for="user_id">User ID:</label>
            <input type="text" id="user_id" name="user_id">
            <label for="user_password">Password:</label>
            <input type="password" id="user_password" name="user_password">
            <input type="submit" value="Login">
            <p>Don't have an account? <a href="signup.php">Sign up here</a></p> <!-- Updated link -->
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?> <!-- Display error message if set -->
        </form>
    </div>
    
    <!-- The signup form should be in a separate file called signup.php -->
</body>
</html>
