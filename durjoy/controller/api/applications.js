function applyFilters() {
    const jobId = document.getElementById('filter-job').value;
    const status = document.getElementById('filter-status').value;
    const experience = document.getElementById('filter-experience').value;
    const dateFrom = document.getElementById('filter-date-from').value;
    const dateTo = document.getElementById('filter-date-to').value;

    const tbody = document.getElementById('applications-table-body');
    const countEl = document.getElementById('result-count');

    tbody.innerHTML = '<tr class="loading-row"><td colspan="8">Loading applications...</td></tr>';

    const xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
        if(this.readyState === 4 && this.status === 200) {
            try {
                const response = JSON.parse(this.responseText);
                if(response.success) {
                    tbody.innerHTML = response.html;
                    countEl.textContent = response.count + ' results';
                } else {
                    tbody.innerHTML = '<tr class="empty-row"><td colspan="8">Error loading applications.</td></tr>';
                    countEl.textContent = '0 results';
                }
            } catch(e) {
                tbody.innerHTML = '<tr class="empty-row"><td colspan="8">Error processing response.</td></tr>';
                countEl.textContent = '0 results';
            }
        }
        if(this.readyState === 4 && this.status !== 200) {
            tbody.innerHTML = '<tr class="empty-row"><td colspan="8">Server error. Please try again.</td></tr>';
            countEl.textContent = '0 results';
        }
    };

    let params = 'job_id=' + encodeURIComponent(jobId);
    params += '&status=' + encodeURIComponent(status);
    params += '&experience_level=' + encodeURIComponent(experience);
    params += '&date_from=' + encodeURIComponent(dateFrom);
    params += '&date_to=' + encodeURIComponent(dateTo);

    xhttp.open("POST", "../../controller/application-filter-controller.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(params);
}

function resetFilters() {
    document.getElementById('filter-job').value = '';
    document.getElementById('filter-status').value = '';
    document.getElementById('filter-experience').value = '';
    document.getElementById('filter-date-from').value = '';
    document.getElementById('filter-date-to').value = '';
    applyFilters();
}