// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.querySelector('.mobile-menu');
    const nav = document.querySelector('nav ul');
    
    if (mobileMenuBtn && nav) {
        mobileMenuBtn.addEventListener('click', function() {
            nav.classList.toggle('show');
        });
    }

    // Close mobile menu when clicking on a link
    const navLinks = document.querySelectorAll('nav ul li a');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            nav.classList.remove('show');
        });
    });

    // Form validation for all forms
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = 'red';
                } else {
                    field.style.borderColor = '#ddd';
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    });

    // Password confirmation validation
    const passwordForms = document.querySelectorAll('form[data-password-confirm="true"]');
    passwordForms.forEach(form => {
        const password = form.querySelector('#password');
        const confirmPassword = form.querySelector('#confirm_password');
        
        if (password && confirmPassword) {
            form.addEventListener('submit', function(e) {
                if (password.value !== confirmPassword.value) {
                    e.preventDefault();
                    alert('Passwords do not match!');
                    confirmPassword.focus();
                }
            });
        }
    });

    // Date validation for donor registration
    const donorForm = document.getElementById('donorForm');
    if (donorForm) {
        donorForm.addEventListener('submit', function(e) {
            const dob = new Date(document.getElementById('dob').value);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            
            if (age < 18 || age > 65) {
                e.preventDefault();
                alert('Donors must be between 18 and 65 years old.');
            }
        });
    }

    // Date validation for blood request
    const requestForm = document.getElementById('requestForm');
    if (requestForm) {
        requestForm.addEventListener('submit', function(e) {
            const requestDate = new Date(document.getElementById('request_date').value);
            const requiredDate = new Date(document.getElementById('required_date').value);
            
            if (requiredDate < requestDate) {
                e.preventDefault();
                alert('Required date cannot be before request date!');
            }
            
            const units = parseInt(document.getElementById('units_required').value);
            if (units < 1) {
                e.preventDefault();
                alert('Units required must be at least 1!');
            }
        });
    }

    // Initialize date fields
    const dateFields = document.querySelectorAll('input[type="date"]');
    dateFields.forEach(field => {
        if (!field.value) {
            field.valueAsDate = new Date();
        }
    });
});

// Format date for display
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    return new Date(dateString).toLocaleDateString(undefined, options);
}