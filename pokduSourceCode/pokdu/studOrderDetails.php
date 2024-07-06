<?php
session_start();

include 'dbconn.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['reservation_id'])) {
    die("Reservation ID not provided.");
}

$reservation_id = $_GET['reservation_id'];
$user_id = $_SESSION['user_id'];

// Fetch order details from ORDER_DETAILS and related payment details
$order_query = "
    SELECT 
        od.reservation_id, 
        od.pickup_point, 
        od.drop_point, 
        od.order_date, 
        od.order_time, 
        od.num_of_people,
        od.confirmed,
        sd.student_id, 
        sd.student_name, 
        sd.student_phoneNum,
        pd.payment_method,
        pd.payment_date,
        '012-3456789' AS pok_du_phoneNum -- Placeholder for Pok Du's phone number
    FROM order_details od
    JOIN student_details sd ON od.student_id = sd.student_id
    LEFT JOIN payment_details pd ON od.reservation_id = pd.reservation_id
    WHERE od.reservation_id = '$reservation_id' AND od.student_id = '$user_id'
";
$order_result = mysqli_query($dbconn, $order_query);

// Check for errors in query execution
if (!$order_result) {
    die('Error in SQL query: ' . mysqli_error($dbconn));
}

// Check if any rows were returned
if (mysqli_num_rows($order_result) > 0) {
    $order_details = mysqli_fetch_assoc($order_result);
} else {
    die("Order not found or you do not have permission to view this order.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Details - Pok Du's Van Reservation System</title>
    <link rel="stylesheet" type="text/css" href="style4.css">
    <style>
        /* Additional inline styles for logo placement */
        .logo {
            display: block;
            width: 150px; /* Adjust as necessary */
            margin: 0 auto;
            margin-top: 20px; /* Adjust vertical spacing */
        }

        /* Center-align the "Back to Dashboard" link */
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px; /* Adjust spacing from table */
        }
    </style>
</head>
<body>
    <img class="logo" src="images/logo.png" alt="Pok Du's Van Reservation System Logo">

    <h2>Order Details</h2>
    <table border="1">
        <tr>
            <th>Reservation ID</th>
            <td><?php echo $order_details['reservation_id']; ?></td>
        </tr>
        <tr>
            <th>Pickup Point</th>
            <td><?php echo $order_details['pickup_point']; ?></td>
        </tr>
        <tr>
            <th>Drop Point</th>
            <td><?php echo $order_details['drop_point']; ?></td>
        </tr>
        <tr>
            <th>Order Date</th>
            <td><?php echo $order_details['order_date']; ?></td>
        </tr>
        <tr>
            <th>Order Time</th>
            <td><?php echo $order_details['order_time']; ?></td>
        </tr>
        <tr>
            <th>Number of People</th>
            <td><?php echo $order_details['num_of_people']; ?></td>
        </tr>
        <tr>
            <th>Confirmed</th>
            <td><?php echo $order_details['confirmed'] ? 'Yes' : 'No'; ?></td>
        </tr>
        <tr>
            <th>Student ID</th>
            <td><?php echo $order_details['student_id']; ?></td>
        </tr>
        <tr>
            <th>Student Name</th>
            <td><?php echo $order_details['student_name']; ?></td>
        </tr>
        <tr>
            <th>Student Phone Number</th>
            <td><?php echo $order_details['student_phoneNum']; ?></td>
        </tr>
        <tr>
            <th>Pok Du's Phone Number</th>
            <td><?php echo $order_details['pok_du_phoneNum']; ?></td>
        </tr>
        <tr>
            <th>Payment Amount</th>
            <td>Refer to Pok Du</td>
        </tr>
        <tr>
            <th>Payment Method</th>
            <td><?php echo $order_details['payment_method']; ?></td>
        </tr>
        <tr>
            <th>Payment Date</th>
            <td><?php echo $order_details['payment_date']; ?></td>
        </tr>
    </table>

    <!-- Back to Dashboard link -->
    <a class="back-link" href="studDashboard.php">Back to Dashboard</a>
</body>
</html>
