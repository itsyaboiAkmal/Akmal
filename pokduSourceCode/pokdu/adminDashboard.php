<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Include database connection file
include 'dbconn.php';

// Fetch orders from ORDER_DETAILS
$order_query = "
    SELECT 
        reservation_id, 
        pickup_point, 
        drop_point, 
        order_date, 
        order_time, 
        confirmed
    FROM order_details
";
$order_result = mysqli_query($dbconn, $order_query);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Pok Du's Van Reservation System</title>
    <link rel="stylesheet" type="text/css" href="style2.css">
    <style>
        /* Reset default margin and padding */
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif; /* Use a common sans-serif font */
            background: url('images/bg8.jpeg') no-repeat center center fixed; /* Fixed background image */
            background-size: cover; /* Cover the entire background */
            color: #333; /* Dark gray text */
            height: 100%; /* Ensure the body takes the full height */
        }

        /* Page header */
        h2 {
            text-align: center;
            color: #fff; /* White text */
            margin-top: 20px;
        }

        /* Table styling */
        table {
            width: 60%;
            margin: 20px auto;
            border-collapse: separate; /* Use separate borders for rounded corners to work */
            border-spacing: 0; /* Remove any spacing between cells */
            background-color: #ffffff; /* White background */
            border: 1px solid #ddd; /* Light gray border */
            box-shadow: 0 0 10px rgba(0,0,0,0.1); /* Subtle shadow */
            border-radius: 10px; /* Rounded corners */
            opacity: 0.8;
            overflow: hidden; /* Ensure the corners are rounded */
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd; /* Light gray border bottom */
        }

        th {
            background-color: #3a7bd5; /* Dark blue header */
            color: white;
        }

        /* Alternating row colors */
        tr:nth-child(even) {
            background-color: #edf5fc; /* Light blue */
        }

        tr:nth-child(odd) {
            background-color: #ffff; /* White */
        }

        /* Link styles */
        a {
            color: darkblue; /* Dark blue */
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Logout link */
        .logout-container {
            text-align: center; /* Center the container */
            margin: 20px 0;
        }

        .logout-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #fff; /* White background */
            color: #333; /* Dark gray text */
            text-align: center;
            text-decoration: none;
            border-radius: 5px; /* Rounded corners */
            box-shadow: 0 0 5px rgba(0,0,0,0.1); /* Subtle shadow */
            transition: background-color 0.3s ease; /* Smooth transition */
            width: 100px;
        }

        .logout-link:hover {
            background-color: #f0f0f0; /* Light gray background on hover */
            text-decoration: none; /* Remove underline on hover */
        }

        /* Additional inline styles for logo placement */
        .logo {
            display: block;
            width: 150px; /* Adjust as necessary */
            margin: 0 auto;
            margin-top: 20px; /* Adjust vertical spacing */
        }

        /* Responsive design adjustments */
        @media (max-width: 768px) {
            table {
                width: 95%;
            }
        }

        /* Centered compact modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 60%; /* Adjust width as needed */
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
            text-align: center;
            border-radius: 10px;
        }

        .modal-content p {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .modal-btns {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        .modal-btns button {
            padding: 10px 20px;
            margin: 0 10px;
            cursor: pointer;
            background-color: #3a7bd5;
            color: white;
            border: none;
            border-radius: 5px;
        }

        .modal-btns button:hover {
            background-color: #235592;
        }
    </style>
    <script>
        // JavaScript function to show modal and handle done confirmation
        function confirmDone(reservation_id) {
            var modal = document.getElementById('doneModal');
            var confirmBtn = document.getElementById('confirmDoneBtn');
            var cancelBtn = document.getElementById('cancelDoneBtn');

            modal.style.display = 'block';

            confirmBtn.onclick = function() {
                // Redirect to done.php with reservation_id
                window.location.href = "done.php?reservation_id=" + reservation_id;
            }

            cancelBtn.onclick = function() {
                modal.style.display = 'none';
            }
        }
    </script>
</head>
<body>

    <img class="logo" src="images/logo.png" alt="Pok Du's Van Reservation System Logo">

    <h2>Admin Dashboard</h2>
    <table border="1">
        <tr>
            <th>Pickup Point</th>
            <th>Drop Point</th>
            <th>Order Date</th>
            <th>Order Time</th>
            <th>Confirmed</th>
            <th>Details</th>
            <th>Action</th>
        </tr>
        <?php
        if (mysqli_num_rows($order_result) > 0) {
            // Output data of each row
            while ($row = mysqli_fetch_assoc($order_result)) {
                echo "<tr>";
                echo "<td>" . $row['pickup_point'] . "</td>";
                echo "<td>" . $row['drop_point'] . "</td>";
                echo "<td>" . $row['order_date'] . "</td>";
                echo "<td>" . $row['order_time'] . "</td>";
                echo "<td>" . ($row['confirmed'] ? 'Yes' : 'No') . "</td>";
                echo "<td><a href='orderDetails.php?reservation_id=" . $row['reservation_id'] . "'>Details</a></td>";
                echo "<td><a href='#' onclick=\"confirmDone('" . $row['reservation_id'] . "');\">Done</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No orders found</td></tr>";
        }
        ?>
    </table>
    <div class="logout-container">
        <a href="logout.php" class="logout-link">Logout</a>
    </div>

    <!-- Centered compact modal -->
    <div id="doneModal" class="modal">
        <div class="modal-content">
            <p>DONE?</p>
            <div class="modal-btns">
                <button id="confirmDoneBtn">Yes</button>
                <button id="cancelDoneBtn">No</button>
            </div>
        </div>
    </div>

</body>
</html>
