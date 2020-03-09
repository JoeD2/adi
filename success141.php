<?php
require_once "config.php";
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Set paid status
// Prepare an update statement
$sql = "UPDATE users SET paid = ? WHERE id = ?";

if($stmt = mysqli_prepare($link, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "si", $param_paid, $param_id);

    // Set parameters
    $param_paid = "Yes";
    $param_id = $_SESSION["id"];

    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){
        // Paid status updated successfully. Destroy the session, and redirect to login page
        session_destroy();
        header("location: login.php");
        exit();
    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }
}

// Close statement
mysqli_stmt_close($stmt);

// Close connection
mysqli_close($link);
?>
