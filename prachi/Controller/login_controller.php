<?php
session_start();
require_once("../db.php");
require_once("../Model/user_model.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_POST["email"]) && !empty($_POST["password"])) {
        $email = trim($_POST["email"]);
        $password = $_POST["password"];

        $user = getUserByEmail($email);

        if ($user && $user["role"] === "seeker" && $user["is_active"] == 1) {
            if (password_verify($password, $user["password_hash"])) {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_name"] = $user["name"];
                $_SESSION["user_email"] = $user["email"];

                header("Location: ../Controller/seeker_dashboard_controller.php");
                exit();
            }
        }

        $_SESSION["error"]["login"] = "Invalid email or password";
        header("Location: ../View/login_view.php");
        exit();
    }

    $_SESSION["error"]["login"] = "Please enter both email and password";
    header("Location: ../View/login_view.php");
    exit();
}
