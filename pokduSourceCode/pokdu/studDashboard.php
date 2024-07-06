<?php
session_start();

include 'dbconn.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch student's name
$name_query = "SELECT student_name FROM student_details WHERE student_id = '$user_id'";
$name_result = mysqli_query($dbconn, $name_query);

if ($name_result && mysqli_num_rows($name_result) > 0) {
    $name_row = mysqli_fetch_assoc($name_result);
    $student_name = $name_row['student_name'];
} else {
    $student_name = 'Student'; // Fallback if name is not found
}

// Fetch reservations
$reservations_query = "
    SELECT 
        reservation_id, 
        pickup_point, 
        drop_point, 
        order_date, 
        order_time, 
        num_of_people, 
        confirmed
    FROM order_details
    WHERE student_id = '$user_id'
";
$reservations_result = mysqli_query($dbconn, $reservations_query);

// Check if the query was successful
if (!$reservations_result) {
    die("Error fetching reservations: " . mysqli_error($dbconn));
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard - Pok Du's Van Reservation System</title>
    <link rel="stylesheet" type="text/css" href="style3.css">
    <style>
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
        // JavaScript function to show modal and handle cancellation confirmation
        function confirmCancellation(reservation_id) {
            var modal = document.getElementById('cancelModal');
            var confirmBtn = document.getElementById('confirmCancelBtn');
            var cancelBtn = document.getElementById('cancelCancelBtn');

            modal.style.display = 'block';

            confirmBtn.onclick = function() {
                // Redirect to cancelReservation.php with reservation_id
                window.location.href = "cancelReservation.php?reservation_id=" + reservation_id;
            }

            cancelBtn.onclick = function() {
                modal.style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <h2>Welcome, <span class="shiny-text"><?php echo htmlspecialchars($student_name); ?></span></h2>
    
    <h3 style="text-align: center;">Your Reservations</h3>

    <table border="1">
        <tr>
            <th>Reservation ID</th>
            <th>Pickup Point</th>
            <th>Drop Point</th>
            <th>Order Date</th>
            <th>Order Time</th>
            <th>Number of People</th>
            <th>Confirmed</th>
            <th>Actions</th>
        </tr>
        <?php
        if (mysqli_num_rows($reservations_result) > 0) {
            while ($row = mysqli_fetch_assoc($reservations_result)) {
                echo "<tr>";
                echo "<td>" . $row['reservation_id'] . "</td>";
                echo "<td>" . $row['pickup_point'] . "</td>";
                echo "<td>" . $row['drop_point'] . "</td>";
                echo "<td>" . $row['order_date'] . "</td>";
                echo "<td>" . $row['order_time'] . "</td>";
                echo "<td>" . $row['num_of_people'] . "</td>";
                echo "<td>" . ($row['confirmed'] ? 'Yes' : 'No') . "</td>";
                echo "<td>
                        <a href='studOrderDetails.php?reservation_id=" . $row['reservation_id'] . "'>Details</a> |
                        <a href='#' onclick=\"confirmCancellation('" . $row['reservation_id'] . "');\">Cancel</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No reservations found</td></tr>";
        }
        ?>
    </table>

    <!-- Centered compact modal -->
    <div id="cancelModal" class="modal">
        <div class="modal-content">
            <p>Are you sure you want to cancel this reservation?</p>
            <div class="modal-btns">
                <button id="confirmCancelBtn">Yes</button>
                <button id="cancelCancelBtn">No</button>
            </div>
        </div>
    </div>

    <br>

    <h3 style="text-align: center; font-size: 24px; color: #ffffff; margin-bottom: 10px;">Would like to make a new reservation?</h3>
    <a href="makeReservation.php" style="display: block; text-align: center; font-size: 20px; color: #ffffff; text-decoration: none; margin-bottom: 20px; width: 15%; margin-left: auto; margin-right: auto;">Click here</a>
    <a href="logout.php" style="display: block; text-align: center; font-size: 20px; color: #ffffff; text-decoration: none; width: 15%; margin-left: auto; margin-right: auto;">Logout</a>

</body>
</html>
