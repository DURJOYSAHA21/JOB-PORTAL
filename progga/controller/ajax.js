function toggleFeatured(jobId) {
    const xhr = new XMLHttpRequest();

    xhr.open("POST", "../controller/api/toggle-featured-api.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        try {
            const response = JSON.parse(xhr.responseText);

            if (response.success) {
                const statusBox = document.getElementById("featured-status-" + jobId);

                if (response.is_featured == 1) {
                    statusBox.innerText = "Featured";
                } else {
                    statusBox.innerText = "Normal";
                }

                alert("Job featured status updated successfully.");
            } else {
                alert(response.message || "Could not update featured status.");
            }
        } catch (e) {
            alert("Invalid server response.");
        }
    };

    xhr.send("job_id=" + encodeURIComponent(jobId));
}