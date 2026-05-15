<?php
 if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once "../model/personal-info-model.php";
function redirectWithError($errors, $oldInput)
{
    $_SESSION['errors'] = $errors;
    $_SESSION['old_input'] = $oldInput;
    header("Location: ../view/personal-info-view.php");
    exit();
}
if($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $fullname=trim($_POST["fullname"]);
    $email=trim($_POST["email"]);
    $phone=trim($_POST["phone"]);
    $password=trim($_POST["password"]);
    $cpassword=trim($_POST["confirm-password"]);

    $oldInput = ["fullname" => $fullname, "email" => $email, "phone" => $phone, "password" => $password, "confirm-password" => $cpassword];
    $errors = [];

    if(empty($fullname) || empty($email) || empty($phone) || empty($password) || empty($cpassword))
    {
        $errors["register"] = "All fields are required";
   
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $errors["email"] = "Please provide a valid email address.";
   
    }

    if($password !== $cpassword)
    {
        $errors["password"] = "Passwords do not match.";
   
    }

    if(strlen($password) < 8)
    {
        $errors["password"] = "Password must be at least 8 characters.";
    
    }
    else if(!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password))
    {
        $errors["password"] = "Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
  
    }


    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    if(!empty($errors))
        {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $oldInput;
            header("Location: ../view/register/personal-info-view.php");
            exit();

        }

    if(registerUser($fullname, $email, $phone, $hashedPassword))
    {
        $_SESSION['success']["register"] = "Registration successful.";
        $_SESSION['user'] = ["fullname" => $fullname, "email" => $email, "phone" => $phone];
        unset($_SESSION['old_input'], $_SESSION['errors']);
        $_SESSION['success'] = "Registration successful.";
        header("Location: ../view/login-view.php");
        exit();
    }
    else
    {
        $_SESSION['error']["register"] = "Registration failed.";
        redirectWithError($errors, $oldInput);

    }

}

header("Location: ../view/register/personal-info-view.php");
exit();


?>