<?php
session_start(); // Start the session
$con = mysqli_connect('localhost', 'root', '', 'travel');

// Check if the form is submitted
if (isset($_POST['submit'])) {
    $username = $_POST['user'];
    $password = $_POST['pass'];

    // Admin credentials check
    if ($username == 'admin' && $password == 'ad123') {
        $_SESSION['user_email'] = 'admin@admin.com';
        header('Location: admin.php');
        exit;
    }

    // Check user credentials
    $sql = "SELECT fname, email, password FROM customer WHERE fname='$username' AND password='$password'";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Save the user's email in the session
        $_SESSION['user_email'] = $user['email'];

        header("Location: mainPage.html");
        exit;
    } else {
        echo "<script>alert('Invalid username or password');</script>";
    }
}
?>