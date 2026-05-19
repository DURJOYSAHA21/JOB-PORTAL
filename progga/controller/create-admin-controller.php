<?php

require_once __DIR__ . "/../model/user-model.php";

$userModel = new UserModel();
$email = "admin@gmail.com";

if ($userModel->emailExists($email)) {
    echo "Admin already exists.";
    exit;
}

$created = $userModel->createAdmin("Admin", $email, "01700000000", "123456");

echo $created ? "Admin created successfully." : "Failed to create admin.";
