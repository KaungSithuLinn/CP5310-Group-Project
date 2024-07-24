<?php
session_start();
require_once "db_config.php";

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"];

    if (!$email || !$password) {
        echo json_encode(["success" => false, "message" => "Email and password are required."]);
        exit;
    }

    try {
        $sql = "SELECT id, name, email, password, last_login FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $id = $row["id"];
            $name = $row["name"];
            $hashed_password = $row["password"];
            $last_login = $row["last_login"];

            if (password_verify($password, $hashed_password)) {
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $id;
                $_SESSION["name"] = $name;
                $_SESSION["email"] = $email;

                $is_new_user = ($last_login === null);

                // Update last login time
                $update_login_sql = "UPDATE users SET last_login = NOW() WHERE id = :id";
                $update_login_stmt = $pdo->prepare($update_login_sql);
                $update_login_stmt->bindParam(":id", $id, PDO::PARAM_INT);
                $update_login_stmt->execute();

                echo json_encode(["success" => true, "name" => $name, "is_new_user" => $is_new_user]);
            } else {
                echo json_encode(["success" => false, "message" => "Invalid email or password."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid email or password."]);
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo json_encode(["success" => false, "message" => "An error occurred. Please try again later.", "debug" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
?>