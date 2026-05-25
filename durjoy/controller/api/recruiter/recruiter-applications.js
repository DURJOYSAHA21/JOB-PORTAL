function applyFilters() {
    const jobId = document.getElementById('filter-job').value;
    const status = document.getElementById('filter-status').value;
    const tbody = document.getElementById('applications-table-body');
    const countEl = document.getElementById('result-count');

    tbody.innerHTML = '<tr class="empty-row"><td colspan="6">Loading...</td></tr>';

    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if(this.readyState === 4 && this.status === 200) {
            const response = JSON.parse(this.responseText);
            if(response.success) {
                tbody.innerHTML = response.html;
                countEl.textContent = response.count + ' applications';
            }
        }
    };

    xhttp.open("POST", "../../../controller/recruiter/recruiter-application-filter-controller.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("job_id=" + encodeURIComponent(jobId) + "&status=" + encodeURIComponent(status));
}

function resetFilters() {
    document.getElementById('filter-job').value = '';
    document.getElementById('filter-status').value = '';
    applyFilters();
}

function updateStatus(applicationId, dropdown) {
    const newStatus = dropdown.value;
    const msgEl = document.getElementById('msg-' + applicationId);
    const previousStatus = dropdown.getAttribute('data-previous');

    dropdown.setAttribute('data-previous', dropdown.value);
    dropdown.disabled = true;
    msgEl.textContent = 'Updating...';
    msgEl.style.color = '#718096';

    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if(this.readyState === 4 && this.status === 200) {
            const response = JSON.parse(this.responseText);
            if(response.success) {
                const badge = dropdown.parentElement.querySelector('.status-badge');
                badge.className = 'status-badge status-' + response.new_status;
                badge.textContent = response.new_status.charAt(0).toUpperCase() + response.new_status.slice(1);
                msgEl.textContent = 'Updated!';
                msgEl.style.color = '#38a169';
                setTimeout(() => { msgEl.textContent = ''; }, 3000);
            } else {
                dropdown.value = previousStatus;
                msgEl.textContent = 'Failed';
                msgEl.style.color = '#e53e3e';
            }
            dropdown.disabled = false;
        }
    };

    xhttp.open("POST", "../../../controller/recruiter/recruiter-application-status-controller.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("application_id=" + encodeURIComponent(applicationId) + "&status=" + encodeURIComponent(newStatus));
}