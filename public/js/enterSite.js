async function hashString(str) {
    const buffer = new TextEncoder().encode(str);
    const hashBuffer = await crypto.subtle.digest('SHA-256', buffer);
    return Array.from(new Uint8Array(hashBuffer))
        .map(b => b.toString(16).padStart(2, '0'))
        .join('');
}

function createHiddenInput(form, name, value) {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = name;
    input.value = value;
    form.appendChild(input);
}

document.addEventListener('DOMContentLoaded', function() {
    const registrationForm = document.getElementById('registrationForm');
    if (registrationForm) {
        registrationForm.addEventListener('submit', async function(event) {
            event.preventDefault();
            
            const usernameInput = document.getElementById('username');
            if (usernameInput && usernameInput.value.trim() === '') {
                document.getElementById('usernameError').textContent = 'Имя пользователя обязательно.';
                return;
            } else if (document.getElementById('usernameError')) {
                document.getElementById('usernameError').textContent = '';
            }

            const emailInput = document.getElementById('email');
            if (emailInput && emailInput.value.trim() === '') {
                document.getElementById('emailError').textContent = 'Почта обязательна';
                return;
            } else if (document.getElementById('emailError')) {
                document.getElementById('emailError').textContent = '';
            }

            const captchaResponse = grecaptcha.getResponse();
            if (!captchaResponse) {
                document.getElementById('captchaError').textContent = 'Подтвердите, что вы не робот.';
                return;
            } else if (document.getElementById('captchaError')) {
                document.getElementById('captchaError').textContent = '';
            }

            const tempForm = document.createElement('form');
            tempForm.method = 'POST';
            tempForm.action = registrationForm.action;
            tempForm.style.display = 'none';

            const formData = new FormData(registrationForm);
            for (const [name, value] of formData.entries()) {
                if (name === 'password' || name === 'confirmPassword') {
                    createHiddenInput(tempForm, name, await hashString(value));
                } else {
                    createHiddenInput(tempForm, name, value);
                }
            }

            createHiddenInput(tempForm, 'g-recaptcha-response', captchaResponse);

            document.body.appendChild(tempForm);
            tempForm.submit();
        });
    }

    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', async function(event) {
            event.preventDefault();
            
            const passwordInput = document.getElementById('password');
            if (passwordInput && passwordInput.value.trim() === '') {
                document.getElementById('passwordError').textContent = 'Пароль обязателен.';
                return;
            } else if (document.getElementById('passwordError')) {
                document.getElementById('passwordError').textContent = '';
            }

            const tempForm = document.createElement('form');
            tempForm.method = 'POST';
            tempForm.action = loginForm.action;
            tempForm.style.display = 'none';

            const formData = new FormData(loginForm);
            for (const [name, value] of formData.entries()) {
                if (name === 'password') {
                    createHiddenInput(tempForm, name, await hashString(value));
                } else {
                    createHiddenInput(tempForm, name, value);
                }
            }

            document.body.appendChild(tempForm);
            tempForm.submit();
        });
    }
});