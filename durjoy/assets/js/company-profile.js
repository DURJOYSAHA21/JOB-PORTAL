function previewLogo(input) {
    const preview = document.getElementById('logoPreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            preview.style.width = '80px';
            preview.style.height = '80px';
            preview.style.borderRadius = '8px';
            preview.style.objectFit = 'cover';
        };
        reader.readAsDataURL(input.files[0]);
    }
}