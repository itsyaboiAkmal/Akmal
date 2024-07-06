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

// Check if reservation_id is set in the URL
if (isset($_GET['reservation_id'])) {
    $reservation_id = intval($_GET['reservation_id']);

    // Begin transaction
    mysqli_begin_transaction($dbconn);

    try {
        // Delete related entries in confirmed_reservations
        $delete_confirmed_query = "DELETE FROM confirmed_reservations WHERE reservation_id = ?";
        if ($stmt = mysqli_prepare($dbconn, $delete_confirmed_query)) {
            mysqli_stmt_bind_param($stmt, 'i', $reservation_id);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception(mysqli_error($dbconn));
            }
            mysqli_stmt_close($stmt);
        } else {
            throw new Exception(mysqli_error($dbconn));
        }

        // Delete related entries in edited_reservations
        $delete_edited_query = "DELETE FROM edited_reservations WHERE reservation_id = ?";
        if ($stmt = mysqli_prepare($dbconn, $delete_edited_query)) {
            mysqli_stmt_bind_param($stmt, 'i', $reservation_id);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception(mysqli_error($dbconn));
            }
            mysqli_stmt_close($stmt);
        } else {
            throw new Exception(mysqli_error($dbconn));
        }

        // Delete related entries in payment_details
        $delete_payment_query = "DELETE FROM payment_details WHERE reservation_id = ?";
        if ($stmt = mysqli_prepare($dbconn, $delete_payment_query)) {
            mysqli_stmt_bind_param($stmt, 'i', $reservation_id);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception(mysqli_error($dbconn));
            }
            mysqli_stmt_close($stmt);
        } else {
            throw new Exception(mysqli_error($dbconn));
        }

        // Delete the order from order_details
        $delete_order_query = "DELETE FROM order_details WHERE reservation_id = ?";
        if ($stmt = mysqli_prepare($dbconn, $delete_order_query)) {
            mysqli_stmt_bind_param($stmt, 'i', $reservation_id);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception(mysqli_error($dbconn));
            }
            mysqli_stmt_close($stmt);
        } else {
            throw new Exception(mysqli_error($dbconn));
        }

        // Commit transaction
        mysqli_commit($dbconn);

        // Close connection
        mysqli_close($dbconn);

        // Redirect to admin dashboard
        header('Location: adminDashboard.php');
        exit();
    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($dbconn);

        // Display error message
        echo "Error: " . $e->getMessage();
    }
} else {
    header('Location: adminDashboard.php');
    exit();
}
?>
