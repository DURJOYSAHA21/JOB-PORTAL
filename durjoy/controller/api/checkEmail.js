function checkEmail(){
    let email = document.getElementById("email").value.trim();
    let responseEl = document.getElementById("emailresponse");

    if (email == "") {
        responseEl.innerHTML = "";
        responseEl.className = "";
        return;
    }

    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            responseEl.innerHTML = this.responseText;
            responseEl.className = "availability-msg " + (this.responseText === "Email available" ? "available" : this.responseText === "Email already taken" ? "taken" : "error");
        }
    };

    let url = "../../controller/checkEmail-controller.php";

    xhttp.open("POST", url, true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("email=" + encodeURIComponent(email));
}