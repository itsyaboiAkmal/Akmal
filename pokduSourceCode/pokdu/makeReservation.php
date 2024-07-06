<?php
session_start();

include 'dbconn.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pickup_point = mysqli_real_escape_string($dbconn, $_POST['pickup_point']);
    $drop_point = mysqli_real_escape_string($dbconn, $_POST['drop_point']);
    $order_date = mysqli_real_escape_string($dbconn, $_POST['order_date']);
    $order_time = mysqli_real_escape_string($dbconn, $_POST['order_time']);
    $num_of_people = (int)$_POST['num_of_people'];

    // Calculate the start and end time for the 10-minute window
    $order_datetime = "$order_date $order_time";
    $order_timestamp = strtotime($order_datetime);
    $start_time = date('Y-m-d H:i:s', $order_timestamp);
    $end_time = date('Y-m-d H:i:s', $order_timestamp + 600); // 600 seconds = 10 minutes

    // Check if the total number of people within the 10-minute window exceeds 13
    $check_query = "
        SELECT SUM(num_of_people) AS total_people
        FROM order_details
        WHERE order_date = '$order_date'
        AND order_time BETWEEN '$start_time' AND '$end_time'
    ";

    $check_result = mysqli_query($dbconn, $check_query);
    $row = mysqli_fetch_assoc($check_result);
    $total_people = $row['total_people'] + $num_of_people;

    if ($total_people > 13) {
        echo "<script>alert('FULL RESERVATION'); window.location.href='makeReservation.php';</script>";
    } else {
        // For simplicity, I'll use a static worker_id 'v01' (as in your example)
        $worker_id = 'v01';

        // Insert reservation into order_details table
        $insert_query = "
            INSERT INTO order_details 
            (pickup_point, drop_point, order_date, order_time, num_of_people, student_id, worker_id, confirmed)
            VALUES 
            ('$pickup_point', '$drop_point', '$order_date', '$order_time', $num_of_people, '{$_SESSION['user_id']}', '$worker_id', 0)
        ";

        if (mysqli_query($dbconn, $insert_query)) {
            $reservation_id = mysqli_insert_id($dbconn); // Get the last inserted reservation_id

            // Store reservation_id in session
            $_SESSION['reservation_details'] = [
                'reservation_id' => $reservation_id,
                'pickup_point' => $pickup_point,
                'drop_point' => $drop_point,
                'order_date' => $order_date,
                'order_time' => $order_time,
                'num_of_people' => $num_of_people,
                'worker_id' => $worker_id
            ];

            // Redirect to payment.php for entering payment details
            header("Location: payment.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($dbconn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Reservation - Pok Du's Van Reservation System</title>
    <link rel="stylesheet" type="text/css" href="style5.css">
</head>
<body>
    <div class="content-container">
        <h2>New Reservation</h2>
        <form method="post" action="makeReservation.php">
            <label for="pickup_point">Pickup Point:</label>
            <input type="text" name="pickup_point" id="pickup_point" required>
            <br>
            <label for="drop_point">Drop Point:</label>
            <input type="text" name="drop_point" id="drop_point" required>
            <br>
            <label for="order_date">Order Date:</label>
            <input type="date" name="order_date" id="order_date" required>
            <br>
            <label for="order_time">Order Time:</label>
            <input type="time" name="order_time" id="order_time" required>
            <br>
            <label for="num_of_people">Number of People:</label>
            <input type="number" name="num_of_people" id="num_of_people" required>
            <br>
            <input type="submit" value="Submit Reservation">
        </form>
        <button onclick="window.location.href='studDashboard.php'">Back To Dashboard</button>
    </div>
</body>
</html>
