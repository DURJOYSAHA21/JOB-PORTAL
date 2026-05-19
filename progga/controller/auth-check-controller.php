<?php

function checkLogin()
{
    if (!isset($_SESSION["user_id"])) {
        header("Location: ../view/login-view.php");
        exit;
    }
}

function checkRole($role)
{
    checkLogin();

    if ($_SESSION["role"] !== $role) {
        header("Location: ../view/login-view.php");
        exit;
    }
}