function toggleJobStatus(jobId, checkbox) {
    const xhttp = new XMLHttpRequest();
    const statusText = document.querySelector('.toggle-status-text-' + jobId);
    const messageEl = document.getElementById('toggle-msg-' + jobId);

    checkbox.disabled = true;
    messageEl.innerHTML = 'Updating...';
    messageEl.style.color = '#757575';

    xhttp.onreadystatechange = function() {
        if(this.readyState === 4 && this.status === 200) {
            try {
                const response = JSON.parse(this.responseText);
                if(response.success) {
                    checkbox.checked = (response.new_status === 'active');
                    statusText.textContent = response.new_status.charAt(0).toUpperCase() + response.new_status.slice(1);
                    const row = checkbox.closest('tr');
                    const badge = row.querySelector('.status-badge');
                    badge.textContent = response.new_status.charAt(0).toUpperCase() + response.new_status.slice(1);
                    badge.className = 'status-badge status-' + response.new_status;
                    messageEl.innerHTML = response.new_status === 'active' ? 'Job is now active!' : 'Job closed!';
                    messageEl.style.color = '#2e7d32';
                    setTimeout(() => { window.location.reload(); }, 1000);
                } else {
                    checkbox.checked = !checkbox.checked;
                    messageEl.innerHTML = response.message || 'Failed to update';
                    messageEl.style.color = '#e53935';
                }
            } catch(e) {
                checkbox.checked = !checkbox.checked;
                messageEl.innerHTML = 'Error processing response';
                messageEl.style.color = '#e53935';
            }
            checkbox.disabled = false;
        }
        if(this.readyState === 4 && this.status !== 200) {
            checkbox.checked = !checkbox.checked;
            messageEl.innerHTML = 'Server error';
            messageEl.style.color = '#e53935';
            checkbox.disabled = false;
        }
    };

    xhttp.open("POST", "../../controller/job-toggle-controller.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("job_id=" + encodeURIComponent(jobId));
}