<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

echo json_encode(["success" => true]);
?>