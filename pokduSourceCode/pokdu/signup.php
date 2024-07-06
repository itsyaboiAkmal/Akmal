<?php
// Include database connection file
include 'dbconn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $student_id = $_POST['student_id'];
    $student_name = $_POST['student_name'];
    $student_password = $_POST['student_password'];
    $student_phoneNum = $_POST['student_phoneNum'];

    // Check if the user already exists
    $check_query = "SELECT * FROM student_details WHERE student_id = '$student_id'";
    $check_result = mysqli_query($dbconn, $check_query);

    if (mysqli_num_rows($check_result) == 0) {
        // User doesn't exist, insert new record
        $insert_query = "INSERT INTO student_details (student_id, student_name, student_password, student_phoneNum) VALUES ('$student_id', '$student_name', '$student_password', '$student_phoneNum')";
        if (mysqli_query($dbconn, $insert_query)) {
            // Sign up successful
            echo "<script>alert('Sign Up successful!'); window.location.href='login.php';</script>";
            exit();
        } else {
            // Sign up failed
            echo "<script>alert('Error: " . mysqli_error($dbconn) . "'); window.location.href='signup.php';</script>";
        }
    } else {
        // User already exists
        echo "<script>alert('Student ID already exists!'); window.location.href='signup.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up - Pok Du's Van Reservation System</title>
    <link rel="stylesheet" type="text/css" href="style8.css">
</head>
<body>
<div class="container">
        <div class="signup-box">
            <div class="logo">
                <img src="logo.png" alt="Logo">
            </div>
            <h2>Sign Up</h2>
            <form action="signup.php" method="post">
                <label for="student_id">Student ID:</label>
                <input type="text" id="student_id" name="student_id" required>
                <label for="student_name">Student Name:</label>
                <input type="text" id="student_name" name="student_name" required>
                <label for="student_password">Password:</label>
                <input type="password" id="student_password" name="student_password" required>
                <label for="student_phoneNum">Phone Number:</label>
                <input type="tel" id="student_phoneNum" name="student_phoneNum" required>
                <button type="submit">Sign Up</button>
            </form>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
