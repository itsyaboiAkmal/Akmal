<?php
// Start session
session_start();

// Include database connection file
include 'dbconn.php';

// Get the reservation_id from the URL
$reservation_id = $_GET['reservation_id'];

// Fetch order details from ORDER_DETAILS and associated payment details
$order_query = "
    SELECT 
        order_details.reservation_id, 
        order_details.pickup_point, 
        order_details.drop_point, 
        order_details.order_date, 
        order_details.order_time, 
        order_details.confirmed,
        student_details.student_id, 
        student_details.student_name, 
        student_details.student_phoneNum,
        payment_details.payment_method,
        payment_details.payment_date
    FROM order_details
    JOIN student_details ON order_details.student_id = student_details.student_id
    LEFT JOIN payment_details ON order_details.reservation_id = payment_details.reservation_id
    WHERE order_details.reservation_id = '$reservation_id'
";
$order_result = mysqli_query($dbconn, $order_query);
$order_details = mysqli_fetch_assoc($order_result);

if (!$order_details) {
    die("Order not found.");
}

// Handle form submission for updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_order_date = mysqli_real_escape_string($dbconn, $_POST['order_date']);
    $new_order_time = mysqli_real_escape_string($dbconn, $_POST['order_time']);
    $new_confirmed = isset($_POST['confirmed']) ? 1 : 0;

    // Update order details
    $update_query = "
        UPDATE order_details
        SET order_date = '$new_order_date',
            order_time = '$new_order_time',
            confirmed = '$new_confirmed'
        WHERE reservation_id = '$reservation_id'
    ";
    if (mysqli_query($dbconn, $update_query)) {
        echo "Order details updated successfully.";
        // Refresh order details after update
        $order_details['order_date'] = $new_order_date;
        $order_details['order_time'] = $new_order_time;
        $order_details['confirmed'] = $new_confirmed;
    } else {
        echo "Error updating order details: " . mysqli_error($dbconn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Details - Pok Du's Van Reservation System</title>
    <link rel="stylesheet" type="text/css" href="style6.css">
</head>
<body>
    <h2>Order Details</h2>
    <table class="order-details-table">
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
            <th>Payment Method</th>
            <td><?php echo $order_details['payment_method']; ?></td>
        </tr>
        <tr>
            <th>Payment Amount</th>
            <td>Refer to Pok Du</td>
        </tr>
        <tr>
            <th>Payment Date</th>
            <td><?php echo $order_details['payment_date']; ?></td>
        </tr>
    </table>
    <form method="post" action="">
        <table class="update-form-table">
            <tr>
                <th>Order Date</th>
                <td><input type="date" name="order_date" value="<?php echo $order_details['order_date']; ?>" required></td>
            </tr>
            <tr>
                <th>Order Time</th>
                <td><input type="time" name="order_time" value="<?php echo $order_details['order_time']; ?>" required></td>
            </tr>
            <tr>
                <th>Confirmed</th>
                <td><input type="checkbox" name="confirmed" value="1" <?php echo $order_details['confirmed'] ? 'checked' : ''; ?>></td>
            </tr>
        </table>
        <br>
        <input type="submit" value="Update">
    </form>

    <br>
    <a href="adminDashboard.php">Back to Dashboard</a>
</body>
</html>
