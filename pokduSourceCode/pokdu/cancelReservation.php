<?php
session_start();

include 'dbconn.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['reservation_id'])) {
    $reservation_id = $_GET['reservation_id'];

    // Start transaction
    mysqli_begin_transaction($dbconn);

    try {
        // First delete from payment_details
        $delete_payment_details_query = "DELETE FROM payment_details WHERE reservation_id = ?";
        $stmt = mysqli_prepare($dbconn, $delete_payment_details_query);
        mysqli_stmt_bind_param($stmt, 'i', $reservation_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Then delete from order_details
        $delete_order_query = "DELETE FROM order_details WHERE reservation_id = ?";
        $stmt = mysqli_prepare($dbconn, $delete_order_query);
        mysqli_stmt_bind_param($stmt, 'i', $reservation_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Commit transaction
        mysqli_commit($dbconn);

        header('Location: studDashboard.php');
        exit();
    } catch (mysqli_sql_exception $exception) {
        // Rollback transaction on error
        mysqli_rollback($dbconn);
        die("Error cancelling reservation: " . $exception->getMessage());
    }
} else {
    header('Location: studDashboard.php');
    exit();
}
?>
