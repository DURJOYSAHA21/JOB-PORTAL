function applyFilters() {
    const clientId = document.getElementById('filter-client').value;
    const status = document.getElementById('filter-status').value;
    const category = document.getElementById('filter-category').value;
    const tbody = document.getElementById('jobs-table-body');
    const countEl = document.getElementById('result-count');

    tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:40px;">Loading...</td></tr>';

    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if(this.readyState === 4 && this.status === 200) {
            const response = JSON.parse(this.responseText);
            if(response.success) {
                tbody.innerHTML = response.html;
                countEl.textContent = response.count + ' results';
            }
        }
    };

    let params = 'client_id=' + encodeURIComponent(clientId);
    params += '&status=' + encodeURIComponent(status);
    params += '&category_id=' + encodeURIComponent(category);

    xhttp.open("POST", "../../../controller/recruiter/recruiter-job-filter-controller.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(params);
}

function resetFilters() {
    document.getElementById('filter-client').value = '';
    document.getElementById('filter-status').value = '';
    document.getElementById('filter-category').value = '';
    applyFilters();
}