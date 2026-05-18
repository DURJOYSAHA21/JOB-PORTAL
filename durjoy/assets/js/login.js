function validateLogin() {
    var valid = true;
    document.getElementById("email_error").innerHTML = "";
    document.getElementById("password_error").innerHTML = "";

    var email = document.getElementById("email").value.trim();
    var password = document.getElementById("password").value.trim();

    if(email == "") {
        document.getElementById("email_error").innerHTML = "Email is required";
        valid = false;
    }

    if(password == "") {
        document.getElementById("password_error").innerHTML = "Password is required";
        valid = false;
    }

    return valid;
}