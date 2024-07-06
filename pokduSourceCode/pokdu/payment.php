<?php
session_start();

include 'dbconn.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Check if reservation_details is set in session
if (!isset($_SESSION['reservation_details'])) {
    header('Location: makeReservation.php');
    exit();
}

$reservation_details = $_SESSION['reservation_details'];

// Get the current date
$current_date = date('Y-m-d'); // Format: YYYY-MM-DD

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reservation_id'], $_POST['payment_method'], $_POST['payment_date'])) {
        $reservation_id = $_POST['reservation_id'];
        $payment_method = $_POST['payment_method'];
        $payment_date = $_POST['payment_date'];

        // Validate reservation_id existence in order_details
        $check_query = "SELECT * FROM order_details WHERE reservation_id = ?";
        $stmt = mysqli_prepare($dbconn, $check_query);
        mysqli_stmt_bind_param($stmt, "i", $reservation_id);
        mysqli_stmt_execute($stmt);
        $check_result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($check_result) > 0) {
            // Insert payment details (note: amount is not included)
            $payment_id = uniqid('p'); // Generating a unique payment_id
            $insert_query = "INSERT INTO payment_details (payment_id, reservation_id, payment_method, payment_date)
                             VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($dbconn, $insert_query);
            mysqli_stmt_bind_param($stmt, "siss", $payment_id, $reservation_id, $payment_method, $payment_date);

            if (mysqli_stmt_execute($stmt)) {
                echo "Payment details successfully added.";

                // Optionally, update order_details table to mark reservation as confirmed or update other status
                $update_reservation_query = "UPDATE order_details SET confirmed = 1 WHERE reservation_id = ?";
                $stmt = mysqli_prepare($dbconn, $update_reservation_query);
                mysqli_stmt_bind_param($stmt, "i", $reservation_id);
                mysqli_stmt_execute($stmt);

                // Clear session reservation details
                unset($_SESSION['reservation_details']);

                header("Location: studDashboard.php");
                exit();
            } else {
                echo "Error adding payment details: " . mysqli_error($dbconn);
            }
        } else {
            echo "Error: Reservation ID does not exist in order_details.";
        }
    } else {
        echo "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Details - Pok Du's Van Reservation System</title>
    <link rel="stylesheet" type="text/css" href="style7.css">
    <style>
        /* Additional styles for payment method selection */
        .payment-method {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .payment-method label {
            position: relative;
            display: inline-block;
            width: 120px;
            height: 60px;
            margin: 0 10px;
            background-color: #f0f0f0; /* Light gray background */
            border: 1px solid #ccc; /* Light gray border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer;
            text-align: center;
            line-height: 60px; /* Center text vertically */
            font-size: 16px;
            transition: background-color 0.3s ease, border-color 0.3s ease; /* Smooth transition */
        }

        .payment-method label:hover {
            background-color: #e0e0e0; /* Darker gray on hover */
        }

        .payment-method input[type="radio"] {
            display: none; /* Hide the default radio buttons */
        }

        .payment-method input[type="radio"]:checked + label {
            background-color: #3a7bd5; /* Dark blue background when checked */
            color: white; /* White text when checked */
            border-color: #3a7bd5; /* Dark blue border when checked */
        }
    </style>
</head>
<body>
    <div class="content-container">
        <h2>Payment Details</h2>
        
        <div class="order-details">
            <h3>Reservation Details</h3>
            <table>
                <tr>
                    <th>Reservation ID</th>
                    <th>Pickup Point</th>
                    <th>Drop Point</th>
                    <th>Order Date</th>
                    <th>Order Time</th>
                    <th>Number of People</th>
                </tr>
                <tr>
                    <td><?php echo htmlspecialchars($reservation_details['reservation_id'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($reservation_details['pickup_point'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($reservation_details['drop_point'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($reservation_details['order_date'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($reservation_details['order_time'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($reservation_details['num_of_people'] ?? 'N/A'); ?></td>
                </tr>
            </table>
        </div>

        <div class="payment-form">
            <h3>Enter Payment Details</h3>
            <form method="post" action="payment.php">
                <input type="hidden" name="reservation_id" value="<?php echo htmlspecialchars($reservation_details['reservation_id'] ?? ''); ?>">
                
                <label for="payment_method">Payment Method:</label>
                <div class="payment-method">
                    <input type="radio" name="payment_method" id="cash" value="Cash" checked>
                    <label for="cash">Cash</label>
                    
                    <input type="radio" name="payment_method" id="qr" value="QR">
                    <label for="qr">QR</label>
                </div>
                <br>
                
                <label for="amount">Amount:</label>
                <input type="text" name="amount" id="amount" value="Refer to Pok Du" readonly>
                <br>
                
                <label for="payment_date">Payment Date:</label>
                <input type="date" name="payment_date" id="payment_date" value="<?php echo htmlspecialchars($current_date); ?>" required>
                <br>
                
                <input type="submit" value="Submit Payment">
            </form>
        </div>

        <br>
        <a href="studDashboard.php" class="btn-link">Back to Dashboard</a>
        <br>
        <a href="logout.php" class="btn-link">Logout</a>
    </div>
</body>
</html>
