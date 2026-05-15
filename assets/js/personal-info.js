function valideteRegisterForm() {
    var valid = true;
    var name = document.getElementById("fullname").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirmpassword").value;
    var phone = document.getElementById("phone").value;
   
    document.getElementById("fullname-error").innerHTML = "";
    document.getElementById("email-error").innerHTML = "";
    document.getElementById("password-error").innerHTML = "";
    document.getElementById("confirmpassword-error").innerHTML = "";
    document.getElementById("phone-error").innerHTML = "";

    if(name == "") {
        document.getElementById("fullname-error").innerHTML = "Name is required";
        valid = false;
    }
    else if((!name.match(/^[a-zA-Z ]+$/))) {
        document.getElementById("name-error").innerHTML = "Only letters and white space allowed";
        valid = false;
    }
    if(email == "") {
        document.getElementById("email-error").innerHTML = "Email is required";
        valid = false;
    }
    else if(!email.match(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)) {
        document.getElementById("email-error").innerHTML = "Invalid Email";
        valid = false;
    }
    if(password == "") {
        document.getElementById("password-error").innerHTML = "Password is required";
        valid = false;
    }
    else if(password.length < 8) {
        document.getElementById("password-error").innerHTML = "Password must be at least 6 characters long";
        valid = false;
    }
    else if(!password.match(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/)) {
        document.getElementById("password-error").innerHTML = "Password must contain at least one uppercase letter, one lowercase letter, one number and one special character";
        valid = false;
    }
    else if(password != confirmPassword) {
        document.getElementById("confirm-password-error").innerHTML = "Passwords do not match";
        valid = false;
    }
    if(confirmPassword == "") {
        document.getElementById("confirm-password-error").innerHTML = "Confirm Password is required";
        valid = false;
    }
    if(phone == "") {
        document.getElementById("phone-error").innerHTML = "Phone Number is required";
        valid = false;
    }
    else if(!phone.match(/^[0-9]{10}$/)) {
        document.getElementById("phone-error").innerHTML = "Invalid Phone Number";
        valid = false;
    }
    return valid;


}