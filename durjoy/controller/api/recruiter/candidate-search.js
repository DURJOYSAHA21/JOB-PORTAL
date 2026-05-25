let searchTimeout;

function debounceSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(searchCandidates, 400);
}

function searchCandidates() {
    const keyword = document.getElementById('search-keyword').value.trim();
    const location = document.getElementById('search-location').value.trim();
    const experience = document.getElementById('search-experience').value;
    const salary = document.getElementById('search-salary').value;
    const resultsDiv = document.getElementById('candidates-results');
    const countEl = document.getElementById('result-count');

    resultsDiv.innerHTML = '<p style="text-align:center;padding:40px;color:#718096;">Searching candidates...</p>';

    const xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if(this.readyState === 4 && this.status === 200) {
            try {
                const response = JSON.parse(this.responseText);
                if(response.success) {
                    resultsDiv.innerHTML = response.html;
                    countEl.textContent = response.count + ' found';
                } else {
                    resultsDiv.innerHTML = '<p class="empty-state">Error loading results.</p>';
                    countEl.textContent = '0 found';
                }
            } catch(e) {
                resultsDiv.innerHTML = '<p class="empty-state">Error processing response.</p>';
                countEl.textContent = '0 found';
            }
        }
        if(this.readyState === 4 && this.status !== 200) {
            resultsDiv.innerHTML = '<p class="empty-state">Server error. Please try again.</p>';
            countEl.textContent = '0 found';
        }
    };

    let params = 'keyword=' + encodeURIComponent(keyword);
    params += '&location=' + encodeURIComponent(location);
    params += '&experience=' + encodeURIComponent(experience);
    params += '&salary=' + encodeURIComponent(salary);

    xhttp.open("POST", "../../../controller/recruiter/recruiter-candidate-search-controller.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(params);
}

function resetSearch() {
    document.getElementById('search-keyword').value = '';
    document.getElementById('search-location').value = '';
    document.getElementById('search-experience').value = '';
    document.getElementById('search-salary').value = '';
    searchCandidates();
}