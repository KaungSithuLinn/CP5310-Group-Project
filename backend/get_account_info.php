<?php
session_start();
require_once "db_config.php";

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    echo json_encode([
        "success" => true,
        "name" => $_SESSION["name"],
        "email" => $_SESSION["email"]
    ]);
} else {
    echo json_encode(["success" => false]);
}
?>
