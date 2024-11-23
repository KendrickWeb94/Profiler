<?php
session_start(); // Start the session

// Destroy all session variables
$_SESSION = array(); // Clear the session array

// Destroy the session cookies, if any
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}

// Destroy the session
session_destroy(); // Destroy the session

// Redirect to the login page
header("Location: ./Login.php");
exit();
?>