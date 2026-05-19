<?php
// Data from Controller only
$user = $viewData['user'] ?? null;
$profile = $viewData['profile'] ?? null;
$errors = $viewData['errors'] ?? [];
$successMessage = $viewData['success'] ?? null;
$successPic = $viewData['successPic'] ?? null;
$successResume = $viewData['successResume'] ?? null;
$oldInput = $viewData['oldInput'] ?? null;

function getValue($oldInput, $profile, $field, $default = '') {
    if ($oldInput && isset($oldInput[$field])) return htmlspecialchars($oldInput[$field]);
    if ($profile && isset($profile[$field])) return htmlspecialchars($profile[$field]);
    return $default;
}

$headline = getValue($oldInput, $profile, 'headline');
$summary = getValue($oldInput, $profile, 'summary');
$skills = getValue($oldInput, $profile, 'skills');
$yearsExperience = getValue($oldInput, $profile, 'years_experience');
$educationLevel = getValue($oldInput, $profile, 'education_level');
$currentSalary = getValue($oldInput, $profile, 'current_salary');
$expectedSalary = getValue($oldInput, $profile, 'expected_salary');
$preferredLocation = getValue($oldInput, $profile, 'preferred_location');

$profilePicPath = $user['profile_pic'] ?? null;
$resumePath = $profile['resume_path'] ?? null;
$resumeName = $resumePath ? basename($resumePath) : null;

$educationOptions = [
    'High School', 'Diploma', 'Associate Degree',
    "Bachelor's Degree", "Master's Degree", 'Doctorate (PhD)',
    'Professional Certification', 'Other'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seeker Profile - Job Portal</title>
    <link rel="stylesheet" href="../View/css/seeker_profile.css">
</head>
<body>
    <div class="container">
        
        <div class="page-header">
            <h1>👤 Build Your Professional Profile</h1>
            <p><?php echo $profile ? 'Update your profile details' : 'Create your profile to get noticed by employers'; ?></p>
        </div>
        <div class="form-container">
            
            <?php if ($successMessage): ?>
                <div class="success-message"><?php echo htmlspecialchars($successMessage); ?></div>
            <?php endif; ?>
            <?php if ($successPic): ?>
                <div class="success-message"><?php echo htmlspecialchars($successPic); ?></div>
            <?php endif; ?>
            <?php if ($successResume): ?>
                <div class="success-message"><?php echo htmlspecialchars($successResume); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($errors)): ?>
                <div class="error-alert">Please fix the errors below before submitting.</div>
            <?php endif; ?>
            
            <form id="profileForm" method="post" action="../Controller/seeker_profile_controller.php" enctype="multipart/form-data">
                
                <!-- Profile Picture -->
                <div class="form-section">
                    <div class="section-title"><span class="icon">📷</span> Profile Picture</div>
                    
                    <div class="profile-pic-section">
                        <div class="profile-pic-preview">
                            <?php if ($profilePicPath && file_exists($profilePicPath)): ?>
                                <img src="<?php echo htmlspecialchars($profilePicPath); ?>" alt="Profile Picture" class="profile-pic-img">
                            <?php else: ?>
                                <div class="profile-pic-placeholder">
                                    <span><?php echo strtoupper(substr($user['name'] ?? 'U', 0, 1)); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="profile-pic-upload">
                            <label for="profile_pic">Select profile picture to upload:</label>
                            <input type="file" name="profile_pic" id="profile_pic" accept="image/*">
                            <span class="input-hint">JPG, JPEG, PNG, GIF only (Max 2MB)</span>
                            <?php if (isset($errors['profile_pic'])): ?>
                                <span class="error-message"><?php echo htmlspecialchars($errors['profile_pic']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Resume -->
                <div class="form-section">
                    <div class="section-title"><span class="icon">📄</span> Resume</div>
                    
                    <div class="form-group">
                        <?php if ($resumePath && file_exists($resumePath)): ?>
                            <div class="current-resume">
                                <p><strong>Current Resume:</strong> <?php echo htmlspecialchars($resumeName); ?></p>
                                <div class="resume-actions">
                                    <a href="../Controller/resume_controller.php?action=view" class="btn btn-secondary btn-sm" target="_blank">👁 View</a>
                                    <a href="../Controller/resume_controller.php?action=download" class="btn btn-secondary btn-sm">⬇ Download</a>
                                </div>
                            </div>
                        <?php else: ?>
                            <p class="no-resume">No resume uploaded yet.</p>
                        <?php endif; ?>
                        
                        <label for="resume">Upload/Replace Resume:</label>
                        <input type="file" name="resume" id="resume" accept=".pdf">
                        <span class="input-hint">PDF only (Max 5MB)</span>
                        <?php if (isset($errors['resume'])): ?>
                            <span class="error-message"><?php echo htmlspecialchars($errors['resume']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Basic Information -->
                <div class="form-section">
                    <div class="section-title"><span class="icon">📋</span> Basic Information</div>
                    
                    <div class="form-group">
                        <label for="headline">Headline <span class="required">*</span></label>
                        <input type="text" name="headline" id="headline" 
                               value="<?php echo $headline; ?>"
                               placeholder="e.g., Senior Software Developer | Full Stack Engineer"
                               maxlength="255" class="<?php echo isset($errors['headline']) ? 'input-error' : ''; ?>" required>
                        <span class="input-hint">A short, catchy headline describing your professional identity</span>
                        <?php if (isset($errors['headline'])): ?>
                            <span class="error-message"><?php echo htmlspecialchars($errors['headline']); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="summary">Professional Summary <span class="required">*</span></label>
                        <textarea name="summary" id="summary" 
                                  placeholder="Write a brief summary of your professional background..."
                                  class="<?php echo isset($errors['summary']) ? 'input-error' : ''; ?>" required><?php echo $summary; ?></textarea>
                        <span class="input-hint">Describe your professional background, key skills, and career objectives</span>
                        <?php if (isset($errors['summary'])): ?>
                            <span class="error-message"><?php echo htmlspecialchars($errors['summary']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Skills -->
                <div class="form-section">
                    <div class="section-title"><span class="icon">💡</span> Skills <span class="required">*</span></div>
                    
                    <div class="form-group">
                        <label>Add Your Skills</label>
                        <div class="skills-container <?php echo isset($errors['skills']) ? 'has-error' : ''; ?>" id="skillsContainer">
                            <input type="text" id="skillInput" placeholder="Type a skill and press Enter or comma to add" autocomplete="off">
                        </div>
                        <span class="input-hint">Press <strong>Enter</strong> or <strong>comma (,)</strong> after each skill</span>
                        <input type="hidden" name="skills" id="skillsHidden" value="<?php echo $skills; ?>">
                        <?php if (isset($errors['skills'])): ?>
                            <span class="error-message"><?php echo htmlspecialchars($errors['skills']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Experience & Education -->
                <div class="form-section">
                    <div class="section-title"><span class="icon">🎓</span> Experience & Education</div>
                    
                    <div class="form-group">
                        <label for="years_experience">Years of Experience <span class="required">*</span></label>
                        <input type="number" name="years_experience" id="years_experience" 
                               value="<?php echo $yearsExperience; ?>" placeholder="e.g., 5"
                               min="0" max="50" class="<?php echo isset($errors['years_experience']) ? 'input-error' : ''; ?>" required>
                        <?php if (isset($errors['years_experience'])): ?>
                            <span class="error-message"><?php echo htmlspecialchars($errors['years_experience']); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="education_level">Education Level <span class="required">*</span></label>
                        <select name="education_level" id="education_level" 
                                class="<?php echo isset($errors['education_level']) ? 'input-error' : ''; ?>" required>
                            <option value="">-- Select Education Level --</option>
                            <?php foreach ($educationOptions as $option): ?>
                                <option value="<?php echo htmlspecialchars($option); ?>"
                                    <?php echo ($educationLevel === $option) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($option); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['education_level'])): ?>
                            <span class="error-message"><?php echo htmlspecialchars($errors['education_level']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Salary -->
                <div class="form-section">
                    <div class="section-title"><span class="icon">💰</span> Salary Information</div>
                    
                    <div class="form-group">
                        <label for="current_salary">Current Salary (Annual)</label>
                        <input type="number" name="current_salary" id="current_salary" 
                               value="<?php echo $currentSalary; ?>" placeholder="e.g., 60000"
                               min="0" step="0.01" class="<?php echo isset($errors['current_salary']) ? 'input-error' : ''; ?>">
                        <span class="input-hint">Optional - Enter your current annual salary</span>
                        <?php if (isset($errors['current_salary'])): ?>
                            <span class="error-message"><?php echo htmlspecialchars($errors['current_salary']); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="expected_salary">Expected Salary (Annual) <span class="required">*</span></label>
                        <input type="number" name="expected_salary" id="expected_salary" 
                               value="<?php echo $expectedSalary; ?>" placeholder="e.g., 80000"
                               min="0" step="0.01" class="<?php echo isset($errors['expected_salary']) ? 'input-error' : ''; ?>" required>
                        <span class="input-hint">Enter your expected annual salary</span>
                        <?php if (isset($errors['expected_salary'])): ?>
                            <span class="error-message"><?php echo htmlspecialchars($errors['expected_salary']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Location -->
                <div class="form-section">
                    <div class="section-title"><span class="icon">📍</span> Preferred Location</div>
                    
                    <div class="form-group">
                        <label for="preferred_location">Preferred Job Location <span class="required">*</span></label>
                        <input type="text" name="preferred_location" id="preferred_location" 
                               value="<?php echo $preferredLocation; ?>" placeholder="e.g., Dhaka, Bangladesh or Remote"
                               class="<?php echo isset($errors['preferred_location']) ? 'input-error' : ''; ?>" required>
                        <span class="input-hint">City, country, or "Remote" for work from home</span>
                        <?php if (isset($errors['preferred_location'])): ?>
                            <span class="error-message"><?php echo htmlspecialchars($errors['preferred_location']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="btn-row">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='../Controller/dashboard_controller.php'">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn" name="submit">
                        💾 Save Profile
                    </button>
                </div>
                
            </form>
        </div>
    </div>
    
    <script>
    // ============ Skills Tag Input (No AJAX) ============
    document.addEventListener('DOMContentLoaded', function() {
        const skillsContainer = document.getElementById('skillsContainer');
        const skillInput = document.getElementById('skillInput');
        const skillsHidden = document.getElementById('skillsHidden');
        let skills = [];
        
        function loadExistingSkills() {
            const existing = skillsHidden.value.trim();
            if (existing) {
                skills = existing.split(',').map(s => s.trim()).filter(s => s !== '');
                renderSkills();
            }
        }
        
        function renderSkills() {
            document.querySelectorAll('.skill-tag').forEach(tag => tag.remove());
            skills.forEach((skill, index) => {
                const tag = document.createElement('span');
                tag.className = 'skill-tag';
                tag.innerHTML = escapeHtml(skill) + 
                    ' <span class="remove-skill" data-index="' + index + '">&times;</span>';
                skillsContainer.insertBefore(tag, skillInput);
            });
            skillsHidden.value = skills.join(', ');
            document.querySelectorAll('.remove-skill').forEach(btn => {
                btn.addEventListener('click', function() {
                    skills.splice(parseInt(this.getAttribute('data-index')), 1);
                    renderSkills();
                });
            });
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        function addSkill(name) {
            name = name.trim();
            if (name === '') return;
            if (skills.some(s => s.toLowerCase() === name.toLowerCase())) return;
            skills.push(name);
            renderSkills();
        }
        
        skillInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault();
                addSkill(skillInput.value.replace(',', ''));
                skillInput.value = '';
            }
            if (e.key === 'Backspace' && skillInput.value === '' && skills.length > 0) {
                skills.splice(skills.length - 1, 1);
                renderSkills();
            }
        });
        
        skillInput.addEventListener('paste', function(e) {
            e.preventDefault();
            const text = (e.clipboardData || window.clipboardData).getData('text');
            if (text.includes(',')) {
                text.split(',').map(s => s.trim()).filter(s => s).forEach(skill => {
                    if (!skills.some(s => s.toLowerCase() === skill.toLowerCase())) skills.push(skill);
                });
                renderSkills();
                skillInput.value = '';
            } else {
                skillInput.value = text;
            }
        });
        
        skillInput.addEventListener('focus', () => skillsContainer.classList.add('focused'));
        skillInput.addEventListener('blur', function() {
            skillsContainer.classList.remove('focused');
            if (skillInput.value.trim() !== '') {
                addSkill(skillInput.value);
                skillInput.value = '';
            }
        });
        
        skillsContainer.addEventListener('click', function(e) {
            if (e.target === skillsContainer) skillInput.focus();
        });
        
        loadExistingSkills();
    });
    </script>
</body>
</html>