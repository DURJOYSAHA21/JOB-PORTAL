function updateStatus(applicationId) {
    const dropdown = document.getElementById('status-dropdown');
    const newStatus = dropdown.value;
    const badge = document.querySelector('.status-badge');
    const messageEl = document.getElementById('status-message');
    const previousStatus = dropdown.getAttribute('data-previous');

    if(newStatus === 'rejected') {
        if(!confirm('Are you sure you want to reject this applicant?')) {
            dropdown.value = previousStatus || dropdown.value;
            return;
        }
    }

    dropdown.disabled = true;
    dropdown.setAttribute('data-previous', dropdown.value);
    messageEl.textContent = 'Updating...';
    messageEl.style.color = '#718096';

    const xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
        if(this.readyState === 4 && this.status === 200) {
            try {
                const response = JSON.parse(this.responseText);
                if(response.success) {
                    badge.className = 'status-badge status-' + response.new_status;
                    badge.textContent = response.new_status.charAt(0).toUpperCase() + response.new_status.slice(1);
                    messageEl.textContent = 'Updated!';
                    messageEl.style.color = '#38a169';
                    setTimeout(() => { messageEl.textContent = ''; }, 3000);
                } else {
                    dropdown.value = previousStatus;
                    messageEl.textContent = response.message || 'Failed to update';
                    messageEl.style.color = '#e53e3e';
                }
            } catch(e) {
                dropdown.value = previousStatus;
                messageEl.textContent = 'Error processing response';
                messageEl.style.color = '#e53e3e';
            }
            dropdown.disabled = false;
        }
        if(this.readyState === 4 && this.status !== 200) {
            dropdown.value = previousStatus;
            messageEl.textContent = 'Server error';
            messageEl.style.color = '#e53e3e';
            dropdown.disabled = false;
        }
    };

    xhttp.open("POST", "../../controller/application-status-controller.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("application_id=" + encodeURIComponent(applicationId) + "&status=" + encodeURIComponent(newStatus));
}