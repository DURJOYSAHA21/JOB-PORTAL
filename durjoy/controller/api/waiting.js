function checkVerification() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            var result = JSON.parse(this.responseText);
            if(result.is_verified == 1) {
                document.getElementById("checkMsg").innerHTML = "Account verified! Redirecting...";
                setTimeout(function() {
                    window.location.href = "../../view/dashboard-view.php";
                }, 1500);
            } else if(result.is_active == 0) {
                document.getElementById("checkMsg").innerHTML = "Account suspended. Logging out...";
                setTimeout(function() {
                    window.location.href = "../../controller/logout-controller.php";
                }, 1500);
            } else {
                document.getElementById("checkMsg").innerHTML = "Still pending. Checking again in 10 seconds...";
            }
        }
    };
    xhttp.open("POST", "../../controller/waiting-controller.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("user_id=" + userId);
}

checkVerification();
setInterval(checkVerification, 10000);