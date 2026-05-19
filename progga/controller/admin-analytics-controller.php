<?php
session_start();
require_once __DIR__ . "/auth-check-controller.php";
require_once __DIR__ . "/../model/admin-model.php";
checkRole("admin");
$model = new AdminModel();
$analytics = $model->analytics();
require_once __DIR__ . "/../view/admin-analytics-view.php";
