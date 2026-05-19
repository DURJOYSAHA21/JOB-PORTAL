<?php
require_once("../db.php");

function getUserByEmail($email) {
    global $conn;

    $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        return null;
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        return mysqli_fetch_assoc($result);
    }

    return null;
}

function getUserById($id) {
    global $conn;

    $sql = "SELECT * FROM users WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        return null;
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        return mysqli_fetch_assoc($result);
    }

    return null;
}

function isEmailTaken($email)
{
    global $conn;

    $sql = "SELECT id FROM users WHERE email = ? LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    return $result->num_rows > 0;
}
function createSeekerUser($name, $email, $phone, $password)
{
    global $conn;

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $role = "seeker";

    $sql = "INSERT INTO users
            (name, email, password_hash, phone, role, is_active, is_verified, created_at)
            VALUES
            (?, ?, ?, ?, ?, 1, 1, NOW())";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "sssss",
        $name,
        $email,
        $password_hash,
        $phone,
        $role
    );

    return $stmt->execute();
}


