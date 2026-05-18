<?php
require_once "../model/personal-info-model.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);

    if (empty($email)) {
        echo "Email required";
        exit;
    }

    if (emailExists($email)) {
        echo "Email already taken";
    } else {
        echo "Email available";
    }
}