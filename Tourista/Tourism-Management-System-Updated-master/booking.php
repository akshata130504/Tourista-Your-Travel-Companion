<?php
session_start(); // Start the session

// Database connection
$con = mysqli_connect('localhost', 'root', '', 'travel');

// Check if the connection is successful
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the user is logged in (email is set in session)
if (!isset($_SESSION['user_email'])) {
    // If the user is not logged in, redirect to the login page
    header("Location: index.html");
    exit();
}

$email = $_SESSION['user_email']; // Fetch the email from the session

// If the form is submitted
if (isset($_POST['submit'])) {
    // Sanitize input data to prevent SQL injection
    $firstname = mysqli_real_escape_string($con, $_POST['ffirst']);
    $lastname = mysqli_real_escape_string($con, $_POST['flast']);
    $city = mysqli_real_escape_string($con, $_POST['city']);
    $phone = mysqli_real_escape_string($con, $_POST['fphone']);
    $destination = mysqli_real_escape_string($con, $_POST['fdesti']);
    $date = mysqli_real_escape_string($con, $_POST['fdate']);

    // Insert booking data into the 'booking' table
    $sql = "INSERT INTO `booking` (`ffirst`, `flast`, `femail`, `city`, `fphone`, `fdesti`, `fdate`) 
            VALUES ('$firstname', '$lastname', '$email', '$city', '$phone', '$destination', '$date')";

    if (mysqli_query($con, $sql)) {
        // Redirect to the booking details page after successful insertion
        header('Location: booked_details.php');
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
    }
}
?>