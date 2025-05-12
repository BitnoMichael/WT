document.addEventListener('DOMContentLoaded', function() {
    // Registration Form Validation (example)
    const registrationForm = document.getElementById('registrationForm');
    if (registrationForm) {
        registrationForm.addEventListener('submit', function(event) {
            let isValid = true;

            // Example validation (add more as needed)
            const usernameInput = document.getElementById('username');
            if (usernameInput && usernameInput.value.trim() === '') {
                document.getElementById('usernameError').textContent = 'Имя пользователя обязательно.';
                isValid = false;
            } else if (document.getElementById('usernameError')) {
                document.getElementById('usernameError').textContent = '';
            }

            // reCAPTCHA validation (client-side - basic check)
            const captchaResponse = grecaptcha.getResponse();
            if (!captchaResponse) {
                document.getElementById('captchaError').textContent = 'Подтвердите, что вы не робот.';
                isValid = false;
            } else if (document.getElementById('captchaError')) {
                document.getElementById('captchaError').textContent = '';
            }

            if (!isValid) {
                event.preventDefault(); // Prevent form submission if validation fails
            }
        });
    }

    // Login Form Validation (example)
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            let isValid = true;

            const passwordInput = document.getElementById('password');

            if (passwordInput && passwordInput.value.trim() === '') {
                document.getElementById('passwordError').textContent = 'Пароль обязателен.';
                isValid = false;
            } else if (document.getElementById('passwordError')) {
                document.getElementById('passwordError').textContent = '';
            }

            if (!isValid) {
                event.preventDefault(); // Prevent form submission if validation fails
            }
        });
    }
});