<?php
$job = $viewData['job'] ?? null;
$profile = $viewData['profile'] ?? null;
$errors = $viewData['errors'] ?? [];
$oldInput = $viewData['oldInput'] ?? null;

$hasExistingResume = !empty($profile['resume_path']) && file_exists($profile['resume_path']);
$coverLetter = $oldInput ? htmlspecialchars($oldInput['cover_letter']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply - <?php echo htmlspecialchars($job['title']); ?></title>
    <link rel="stylesheet" href="../View/css/apply_job.css">
</head>
<body>
    <div class="container">
        
        <a href="../Controller/job_details_controller.php?id=<?php echo $job['id']; ?>" class="back-link">← Back to Job Details</a>
        
        <div class="card">
            <h1><?php echo htmlspecialchars($job['title']); ?></h1>
            <div class="company"><?php echo htmlspecialchars($job['company_name'] ?? $job['employer_name']); ?></div>
            
            <?php if (!empty($errors)): ?>
                <div class="alert-warning">Please fix the errors below.</div>
            <?php endif; ?>
            
            <form method="post" enctype="multipart/form-data">
                
                <!-- Cover Letter -->
                <div class="form-group">
                    <label>Cover Letter <span class="required">*</span></label>
                    <textarea name="cover_letter" placeholder="Write why you are a good fit for this position..." class="<?php echo isset($errors['cover_letter']) ? 'input-error' : ''; ?>"><?php echo $coverLetter; ?></textarea>
                    <?php if (isset($errors['cover_letter'])): ?>
                        <span class="error-message"><?php echo $errors['cover_letter']; ?></span>
                    <?php endif; ?>
                </div>
                
                <!-- Resume Options -->
                <div class="form-group">
                    <label>Resume <span class="required">*</span></label>
                    
                    <?php if ($hasExistingResume): ?>
                        <div class="resume-option">
                            <label>
                                <input type="radio" name="use_existing_resume" value="1" checked>
                                Use resume from my profile
                            </label>
                            <div class="existing-resume">✅ <?php echo basename($profile['resume_path']); ?></div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="resume-option">
                        <label>
                            <input type="radio" name="use_existing_resume" value="0" <?php echo !$hasExistingResume ? 'checked' : ''; ?>>
                            Upload new resume (PDF, max 5MB)
                        </label>
                        <div class="upload-box">
                            <input type="file" name="resume" accept=".pdf">
                        </div>
                    </div>
                    
                    <?php if (isset($errors['resume'])): ?>
                        <span class="error-message"><?php echo $errors['resume']; ?></span>
                    <?php endif; ?>
                </div>
                
                <!-- Buttons -->
                <div class="btn-row">
                    <a href="../Controller/job_details_controller.php?id=<?php echo $job['id']; ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Submit Application</button>
                </div>
                
            </form>
        </div>
        
    </div>
</body>
</html>