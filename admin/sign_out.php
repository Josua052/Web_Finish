<?php
// Start session
session_start();

// Destroy the session to log the user out
session_unset(); // Remove all session variables
session_destroy(); // Destroy the session

// Redirect to the sign-in page
header("Location: sign_in.php");
exit();
?>
