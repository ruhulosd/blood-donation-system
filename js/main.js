// JavaScript for Blood Donation Management System
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let valid = true;
            const inputs = this.querySelectorAll('input[required], select[required], textarea[required]');
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    valid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });
            
            if (!valid) {
                e.preventDefault();
                alert('Please fill all required fields.');
            }
        });
    });
    
    // Remove validation styles when user starts typing
    const requiredFields = document.querySelectorAll('input[required], select[required], textarea[required]');
    
    requiredFields.forEach(field => {
        field.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('is-invalid');
            }
        });
    });
    
    // Emergency request urgency level color coding
    const urgencySelect = document.getElementById('urgency_level');
    if (urgencySelect) {
        urgencySelect.addEventListener('change', function() {
            this.className = 'form-select';
            if (this.value === 'Critical') {
                this.classList.add('bg-danger', 'text-white');
            } else if (this.value === 'High') {
                this.classList.add('bg-warning', 'text-dark');
            } else if (this.value === 'Medium') {
                this.classList.add('bg-info', 'text-white');
            } else if (this.value === 'Low') {
                this.classList.add('bg-success', 'text-white');
            }
        });
    }
    
    // Blood group selection enhancement
    const bloodGroupSelects = document.querySelectorAll('select[name*="blood_group"]');
    
    bloodGroupSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.className = 'form-select';
            this.classList.add('bg-danger', 'text-white');
        });
    });
    
    // Dashboard charts (placeholder for real charts)
    console.log('Blood Donation System loaded successfully');
});