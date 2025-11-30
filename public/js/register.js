class SoftMinimalismRegisterForm {
    constructor() {
        this.form = document.getElementById('registerForm');
        this.usernameInput = document.getElementById('username');
        this.emailInput = document.getElementById('email');
        this.passwordInput = document.getElementById('password');
        this.confirmPasswordInput = document.getElementById('confirmPassword');
        this.passwordToggle = document.getElementById('passwordToggle');
        this.confirmPasswordToggle = document.getElementById('confirmPasswordToggle');
        this.submitButton = this.form.querySelector('.comfort-button');
        this.successMessage = document.getElementById('successMessage');

        this.init();
    }

    init() {
        this.bindEvents();
        this.setupPasswordToggle();
    }

    bindEvents() {
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        this.usernameInput.addEventListener('blur', () => this.validateUsername());
        this.emailInput.addEventListener('blur', () => this.validateEmail());
        this.passwordInput.addEventListener('blur', () => this.validatePassword());
        this.confirmPasswordInput.addEventListener('blur', () => this.validateConfirmPassword());
    }

    setupPasswordToggle() {
        // Toggle mật khẩu
        this.passwordToggle.addEventListener('click', () => {
            const type = this.passwordInput.type === 'password' ? 'text' : 'password';
            this.passwordInput.type = type;
            this.passwordToggle.classList.toggle('toggle-active', type === 'text');
        });

        // Toggle xác nhận mật khẩu
        this.confirmPasswordToggle.addEventListener('click', () => {
            const type = this.confirmPasswordInput.type === 'password' ? 'text' : 'password';
            this.confirmPasswordInput.type = type;
            this.confirmPasswordToggle.classList.toggle('toggle-active', type === 'text');
        });
    }

    validateUsername() {
        const username = this.usernameInput.value.trim();
        if (!username) {
            this.showError('username', 'Vui lòng nhập tên người dùng');
            return false;
        }
        this.clearError('username');
        return true;
    }

    validateEmail() {
        const email = this.emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email) {
            this.showError('email', 'Vui lòng nhập email');
            return false;
        }
        if (!emailRegex.test(email)) {
            this.showError('email', 'Email không hợp lệ');
            return false;
        }
        this.clearError('email');
        return true;
    }

    validatePassword() {
        const password = this.passwordInput.value;
        if (!password) {
            this.showError('password', 'Vui lòng nhập mật khẩu');
            return false;
        }
        if (password.length < 6) {
            this.showError('password', 'Mật khẩu ít nhất 6 ký tự');
            return false;
        }
        this.clearError('password');
        return true;
    }

    validateConfirmPassword() {
        if (this.confirmPasswordInput.value !== this.passwordInput.value) {
            this.showError('confirmPassword', 'Mật khẩu không khớp');
            return false;
        }
        this.clearError('confirmPassword');
        return true;
    }

    showError(field, message) {
        const softField = document.getElementById(field).closest('.soft-field');
        const errorElement = document.getElementById(`${field}Error`);
        softField.classList.add('error');
        errorElement.textContent = message;
        errorElement.classList.add('show');
    }

    clearError(field) {
        const softField = document.getElementById(field).closest('.soft-field');
        const errorElement = document.getElementById(`${field}Error`);
        softField.classList.remove('error');
        errorElement.classList.remove('show');
        setTimeout(() => { errorElement.textContent = ''; }, 300);
    }

    async handleSubmit(e) {
        e.preventDefault();

        if (!this.validateUsername() || !this.validateEmail() ||
            !this.validatePassword() || !this.validateConfirmPassword()) {
            return;
        }

        this.submitButton.classList.add('loading');

        try {
            const formData = {
                username: this.usernameInput.value.trim(),
                email: this.emailInput.value.trim(),
                password: this.passwordInput.value,
                confirmPassword: this.confirmPasswordInput.value,
            };

            const response = await fetch(this.form.action, {
                method: this.form.method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            });

            if (!response.ok) {
                throw new Error('Network error');
            }

            const result = await response.json();

            if (result.success) {
                this.form.style.display = 'none';
                this.successMessage.classList.add('show');

                setTimeout(() => {
                    window.location.href = 'login';
                }, 2000);
            } else {
                this.showError('email', result.message || 'Đăng ký thất bại');
            }

        } catch (err) {
            console.error(err);
            this.showError('email', 'Lỗi hệ thống, thử lại sau.');
        } finally {
            this.submitButton.classList.remove('loading');
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new SoftMinimalismRegisterForm();
});
