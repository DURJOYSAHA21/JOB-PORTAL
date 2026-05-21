<?php
session_start();
session_destroy();
header("Location: ../../view/recruiter/recruiter-login-view.php");
exit();