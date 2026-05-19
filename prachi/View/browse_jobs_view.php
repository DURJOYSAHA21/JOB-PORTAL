<?php
$categories = $viewData['categories'] ?? [];
$locations = $viewData['locations'] ?? [];
$totalJobs = $viewData['totalJobs'] ?? 0;
$keyword = $viewData['keyword'] ?? '';
$filters = $viewData['filters'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Jobs - Job Portal</title>
    <link rel="stylesheet" href="../View/css/browse_jobs.css">
</head>
<body>
    <div class="container">
        
        <!-- Header -->
        <div class="page-header">
            <h1>🔍 Browse Jobs</h1>
            <p>Find your dream job from <span id="totalCount"><?php echo $totalJobs; ?></span> active listings</p>
        </div>
        
        <div class="content-wrapper">
            
            <!-- ====== SIDEBAR FILTERS ====== -->
            <aside class="sidebar">
                <div class="filter-section">
                    <h3>Filters</h3>
                    
                    <!-- Keyword Search -->
                    <div class="filter-group">
                        <label for="keywordSearch">Keyword Search</label>
                        <input type="text" id="keywordSearch" 
                               value="<?php echo htmlspecialchars($keyword); ?>"
                               placeholder="Job title, description, company...">
                    </div>
                    
                    <!-- Category Filter -->
                    <div class="filter-group">
                        <label for="categoryFilter">Category</label>
                        <select id="categoryFilter">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>"
                                    <?php echo ($filters['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Location Filter -->
                    <div class="filter-group">
                        <label for="locationFilter">Location</label>
                        <select id="locationFilter">
                            <option value="">All Locations</option>
                            <?php foreach ($locations as $loc): ?>
                                <option value="<?php echo htmlspecialchars($loc); ?>"
                                    <?php echo ($filters['location'] == $loc) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($loc); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Job Type Filter -->
                    <div class="filter-group">
                        <label for="jobTypeFilter">Job Type</label>
                        <select id="jobTypeFilter">
                            <option value="">All Types</option>
                            <option value="full-time" <?php echo ($filters['job_type'] == 'full-time') ? 'selected' : ''; ?>>Full Time</option>
                            <option value="part-time" <?php echo ($filters['job_type'] == 'part-time') ? 'selected' : ''; ?>>Part Time</option>
                            <option value="remote" <?php echo ($filters['job_type'] == 'remote') ? 'selected' : ''; ?>>Remote</option>
                            <option value="contract" <?php echo ($filters['job_type'] == 'contract') ? 'selected' : ''; ?>>Contract</option>
                        </select>
                    </div>
                    
                    <!-- Experience Level Filter -->
                    <div class="filter-group">
                        <label for="experienceFilter">Experience Level</label>
                        <select id="experienceFilter">
                            <option value="">All Levels</option>
                            <option value="entry" <?php echo ($filters['experience_level'] == 'entry') ? 'selected' : ''; ?>>Entry Level</option>
                            <option value="mid" <?php echo ($filters['experience_level'] == 'mid') ? 'selected' : ''; ?>>Mid Level</option>
                            <option value="senior" <?php echo ($filters['experience_level'] == 'senior') ? 'selected' : ''; ?>>Senior Level</option>
                        </select>
                    </div>
                    
                    <!-- Salary Range Filter -->
                    <div class="filter-group">
                        <label>Salary Range</label>
                        <div class="salary-inputs">
                            <input type="number" id="salaryMin" placeholder="Min" 
                                   value="<?php echo htmlspecialchars($filters['salary_min']); ?>" min="0">
                            <span>to</span>
                            <input type="number" id="salaryMax" placeholder="Max" 
                                   value="<?php echo htmlspecialchars($filters['salary_max']); ?>" min="0">
                        </div>
                    </div>
                    
                    <button id="clearFilters" class="btn btn-outline">Clear All Filters</button>
                </div>
            </aside>
            
            <!-- ====== JOB LISTINGS ====== -->
            <main class="job-listings">
                
                <!-- Results Info -->
                <div class="results-info">
                    <span id="resultsCount">Showing <?php echo $totalJobs; ?> jobs</span>
                    <div class="loading-spinner" id="loadingSpinner"></div>
                </div>
                
                <!-- Job Cards Container -->
                <div id="jobCards">
                    <!-- Jobs loaded via AJAX -->
                </div>
                
                <!-- Load More Button -->
                <div class="load-more" id="loadMoreContainer" style="display:none;">
                    <button id="loadMoreBtn" class="btn btn-primary">Load More Jobs</button>
                </div>
                
                <!-- No Results -->
                <div class="no-results" id="noResults" style="display:none;">
                    <p>😕 No jobs found matching your criteria.</p>
                    <p>Try adjusting your filters or search keyword.</p>
                </div>
                
            </main>
            
        </div>
    </div>
    
    <script src="../Controller/js/browse_jobs.js"></script>
</body>
</html>