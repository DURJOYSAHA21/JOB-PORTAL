/**
 * Browse Jobs - AJAX Search & Filter
 */
document.addEventListener('DOMContentLoaded', function() {
    
    // DOM elements
    const keywordSearch = document.getElementById('keywordSearch');
    const categoryFilter = document.getElementById('categoryFilter');
    const locationFilter = document.getElementById('locationFilter');
    const jobTypeFilter = document.getElementById('jobTypeFilter');
    const experienceFilter = document.getElementById('experienceFilter');
    const salaryMin = document.getElementById('salaryMin');
    const salaryMax = document.getElementById('salaryMax');
    const clearFilters = document.getElementById('clearFilters');
    const jobCards = document.getElementById('jobCards');
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    const loadMoreContainer = document.getElementById('loadMoreContainer');
    const noResults = document.getElementById('noResults');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const resultsCount = document.getElementById('resultsCount');
    const totalCount = document.getElementById('totalCount');
    
    let currentPage = 1;
    let debounceTimer;
    
    // Load jobs on page load
    loadJobs();
    
    // ============ EVENT LISTENERS ============
    
    // Keyword search with debounce
    keywordSearch.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            currentPage = 1;
            loadJobs();
        }, 500);
    });
    
    // Filter changes
    [categoryFilter, locationFilter, jobTypeFilter, experienceFilter].forEach(filter => {
        filter.addEventListener('change', function() {
            currentPage = 1;
            loadJobs();
        });
    });
    
    // Salary filter with debounce
    [salaryMin, salaryMax].forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                currentPage = 1;
                loadJobs();
            }, 800);
        });
    });
    
    // Clear filters
    clearFilters.addEventListener('click', function() {
        keywordSearch.value = '';
        categoryFilter.value = '';
        locationFilter.value = '';
        jobTypeFilter.value = '';
        experienceFilter.value = '';
        salaryMin.value = '';
        salaryMax.value = '';
        currentPage = 1;
        loadJobs();
    });
    
    // Load more
    loadMoreBtn.addEventListener('click', function() {
        currentPage++;
        loadJobs(true);
    });
    
    // ============ AJAX LOAD JOBS ============
    function loadJobs(append = false) {
        if (!append) {
            jobCards.innerHTML = '';
            loadMoreContainer.style.display = 'none';
            noResults.style.display = 'none';
        }
        
        loadingSpinner.classList.add('active');
        
        const params = new URLSearchParams({
            keyword: keywordSearch.value.trim(),
            category_id: categoryFilter.value,
            location: locationFilter.value,
            job_type: jobTypeFilter.value,
            experience_level: experienceFilter.value,
            salary_min: salaryMin.value,
            salary_max: salaryMax.value,
            page: currentPage
        });
        
        const xhr = new XMLHttpRequest();
        xhr.open('GET', '../Controller/browse_jobs_controller.php?' + params.toString(), true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                loadingSpinner.classList.remove('active');
                
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        
                        if (response.success) {
                            if (!append) {
                                jobCards.innerHTML = '';
                            }
                            
                            if (response.jobs.length === 0 && !append) {
                                noResults.style.display = 'block';
                                loadMoreContainer.style.display = 'none';
                            } else {
                                noResults.style.display = 'none';
                                renderJobs(response.jobs);
                                
                                if (response.has_more) {
                                    loadMoreContainer.style.display = 'block';
                                } else {
                                    loadMoreContainer.style.display = 'none';
                                }
                            }
                            
                            updateCounts(response.total);
                            totalCount.textContent = response.total;
                        }
                    } catch (e) {
                        console.error('Parse error:', e);
                        noResults.style.display = 'block';
                        noResults.innerHTML = '<p>Error loading jobs. Please try again.</p>';
                    }
                } else {
                    noResults.style.display = 'block';
                    noResults.innerHTML = '<p>Server error. Please try again later.</p>';
                }
            }
        };
        
        xhr.send();
    }
    
    // ============ RENDER JOB CARDS ============
    function renderJobs(jobs) {
        jobs.forEach(job => {
            const card = document.createElement('div');
            card.className = 'job-card' + (job.is_featured ? ' featured' : '');
            card.onclick = function() {
                window.location.href = '../Controller/job_details_controller.php?id=' + job.id;
            };
            
            card.innerHTML = `
                <div class="job-card-header">
                    <div>
                        <h3><a href="../Controller/job_details_controller.php?id=${job.id}">${job.title}</a></h3>
                        <div class="company-name">🏢 ${job.company_name}</div>
                    </div>
                </div>
                <div class="job-meta">
                    ${job.is_featured ? '<span class="meta-tag featured-tag">⭐ Featured</span>' : ''}
                    <span class="meta-tag">📁 ${job.category_name}</span>
                    <span class="meta-tag">📍 ${job.location}</span>
                    <span class="meta-tag">💼 ${capitalizeFirst(job.job_type)}</span>
                    <span class="meta-tag">🎯 ${capitalizeFirst(job.experience_level)} Level</span>
                </div>
                <div class="job-card-footer">
                    <span class="salary">${job.salary_display}</span>
                    <span>⏰ ${job.time_ago}</span>
                    ${job.deadline !== 'N/A' ? '<span class="deadline">📅 Deadline: ' + job.deadline + '</span>' : ''}
                </div>
            `;
            
            jobCards.appendChild(card);
        });
    }
    
    // ============ HELPERS ============
    function updateCounts(total) {
        if (total === 0) {
            resultsCount.textContent = 'No jobs found';
        } else if (total === 1) {
            resultsCount.textContent = 'Showing 1 job';
        } else {
            resultsCount.textContent = 'Showing ' + total + ' jobs';
        }
    }
    
    function capitalizeFirst(str) {
        if (!str) return '';
        return str.charAt(0).toUpperCase() + str.slice(1).replace('-', ' ');
    }
});