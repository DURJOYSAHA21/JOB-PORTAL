<?php
session_start();
session_destroy();
header("Location: ../View/login_view.php");
exit();
?>