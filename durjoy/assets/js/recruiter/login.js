function validateLogin() {
    var valid = true;
    document.getElementById("email_error").innerHTML = "";
    document.getElementById("password_error").innerHTML = "";
    if(document.getElementById("email").value.trim() == "") { document.getElementById("email_error").innerHTML = "Email is required"; valid = false; }
    if(document.getElementById("password").value.trim() == "") { document.getElementById("password_error").innerHTML = "Password is required"; valid = false; }
    return valid;
}