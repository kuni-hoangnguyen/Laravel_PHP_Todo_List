// ========================================
// PROFILE PAGE FUNCTIONALITY
// ========================================

// =====================================
// 1. UTILITY FUNCTIONS
// =====================================

/**
 * Show toast notification
 * @param {string} message - Message to display
 * @param {string} type - Type of toast (success, error, info)
 * @param {number} duration - Display duration in ms
 */
function showToast(message, type = 'success', duration = 3000) {
    console.log('Showing toast:', message, type);

    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toast-message');

    if (toast && toastMessage) {
        // Remove existing classes
        toast.className = toast.className.replace(/bg-\w+-500/g, '');

        // Add appropriate background color
        switch (type) {
            case 'error':
                toast.classList.add('bg-red-500');
                break;
            case 'info':
                toast.classList.add('bg-blue-500');
                break;
            default:
                toast.classList.add('bg-emerald-500');
        }

        toastMessage.textContent = message;
        toast.classList.remove('hidden', 'translate-x-full');

        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 300);
        }, duration);
    } else {
        console.warn('Toast elements not found, using fallback alert');
        alert(message);
    }
}

/**
 * Get CSRF token from meta tag
 * @returns {string} CSRF token
 */
function getCSRFToken() {
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
    if (!tokenMeta) {
        console.error('CSRF token not found');
        return '';
    }
    return tokenMeta.content;
}

/**
 * Toggle password visibility
 * @param {string} inputId - ID of the password input
 */
function togglePassword(inputId) {
    const passwordInput = document.getElementById(inputId);
    const toggleButton = document.querySelector(`[data-target="${inputId}"]`);
    const icon = toggleButton.querySelector('.material-icons');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        passwordInput.type = 'password';
        icon.textContent = 'visibility';
    }
}

/**
 * Validate password match
 */
function validatePasswordMatch() {
    const newPassword = document.getElementById('new-password');
    const confirmPassword = document.getElementById('new-password-confirmation');
    const statusElement = document.getElementById('password-match-status');

    if (!newPassword.value || !confirmPassword.value) {
        statusElement.textContent = '';
        statusElement.className = 'mt-1 text-sm';
        return true;
    }

    if (newPassword.value === confirmPassword.value) {
        statusElement.textContent = '✓ Mật khẩu khớp';
        statusElement.className = 'mt-1 text-sm text-green-600';
        return true;
    } else {
        statusElement.textContent = '✗ Mật khẩu không khớp';
        statusElement.className = 'mt-1 text-sm text-red-600';
        return false;
    }
}

// =====================================
// 2. API FUNCTIONS
// =====================================

/**
 * Update user profile
 * @param {Object} profileData - Profile data to update
 */
async function updateProfile(profileData) {
    const updateBtn = document.querySelector('.profile-update-btn');
    const btnText = updateBtn.querySelector('.btn-text');
    const originalText = btnText.textContent;

    // Show loading state
    updateBtn.disabled = true;
    btnText.textContent = 'Đang cập nhật...';
    updateBtn.classList.add('opacity-75');

    try {
        // FIXED: Use the correct endpoint
        const response = await fetch('/profile/info', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCSRFToken(),
                'Accept': 'application/json'
            },
            body: JSON.stringify(profileData)
        });

        const result = await response.json();

        if (response.ok && result.success) {
            showToast(result.message);

            // Update display elements
            document.getElementById('display-name').textContent = profileData.user_name;
            document.getElementById('display-email').textContent = profileData.user_email;

            // Update profile button in header
            const headerProfileName = document.querySelector('#profile-button .text-sm');
            const headerProfileEmail = document.querySelector('#profile-menu .text-xs');
            if (headerProfileName) headerProfileName.textContent = profileData.user_name;
            if (headerProfileEmail) headerProfileEmail.textContent = profileData.user_email;

        } else {
            showToast(result.message || 'Lỗi khi cập nhật thông tin!', 'error');
        }
    } catch (error) {
        console.error('Request failed:', error);
        showToast('Không thể kết nối server!', 'error');
    } finally {
        // Reset button state
        updateBtn.disabled = false;
        btnText.textContent = originalText;
        updateBtn.classList.remove('opacity-75');
    }
}

/**
 * Update user password
 * @param {Object} passwordData - Password data to update
 */
async function updatePassword(passwordData) {
    const updateBtn = document.querySelector('.password-update-btn');
    const btnText = updateBtn.querySelector('.btn-text');
    const originalText = btnText.textContent;

    // Show loading state
    updateBtn.disabled = true;
    btnText.textContent = 'Đang đổi mật khẩu...';
    updateBtn.classList.add('opacity-75');

    try {
        // This endpoint remains the same
        const response = await fetch('/profile/password', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCSRFToken(),
                'Accept': 'application/json'
            },
            body: JSON.stringify(passwordData)
        });

        const result = await response.json();

        if (response.ok && result.success) {
            showToast(result.message);

            // Reset form
            document.getElementById('password-form').reset();
            document.getElementById('password-match-status').textContent = '';
            document.getElementById('password-match-status').className = 'mt-1 text-sm';

        } else {
            showToast(result.message || 'Lỗi khi đổi mật khẩu!', 'error');
        }
    } catch (error) {
        console.error('Request failed:', error);
        showToast('Không thể kết nối server!', 'error');
    } finally {
        // Reset button state
        updateBtn.disabled = false;
        btnText.textContent = originalText;
        updateBtn.classList.remove('opacity-75');
    }
}

// =====================================
// 3. FORM HANDLING
// =====================================

/**
 * Initialize profile form
 */
function initializeProfileForm() {
    const profileForm = document.getElementById('profile-form');

    if (!profileForm) {
        console.error('Profile form not found');
        return;
    }

    profileForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(profileForm);
        const profileData = {
            user_name: formData.get('user_name'),
            user_email: formData.get('user_email')
        };

        // Basic validation
        if (!profileData.user_name.trim()) {
            showToast('Tên không được để trống!', 'error');
            return;
        }

        if (!profileData.user_email.trim()) {
            showToast('Email không được để trống!', 'error');
            return;
        }

        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(profileData.user_email)) {
            showToast('Email không hợp lệ!', 'error');
            return;
        }

        await updateProfile(profileData);
    });

    console.log('Profile form initialized');
}

/**
 * Initialize password form
 */
function initializePasswordForm() {
    const passwordForm = document.getElementById('password-form');
    const newPassword = document.getElementById('new-password');
    const confirmPassword = document.getElementById('new-password-confirmation');

    if (!passwordForm || !newPassword || !confirmPassword) {
        console.error('Password form elements not found');
        return;
    }

    // Real-time password match validation
    [newPassword, confirmPassword].forEach(input => {
        input.addEventListener('input', validatePasswordMatch);
    });

    passwordForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(passwordForm);
        const passwordData = {
            current_password: formData.get('current_password'),
            new_password: formData.get('new_password'),
            new_password_confirmation: formData.get('new_password_confirmation')
        };

        // Basic validation
        if (!passwordData.current_password) {
            showToast('Vui lòng nhập mật khẩu hiện tại!', 'error');
            return;
        }

        if (!passwordData.new_password) {
            showToast('Vui lòng nhập mật khẩu mới!', 'error');
            return;
        }

        if (passwordData.new_password.length < 6) {
            showToast('Mật khẩu mới phải có ít nhất 6 ký tự!', 'error');
            return;
        }

        if (!validatePasswordMatch()) {
            showToast('Mật khẩu xác nhận không khớp!', 'error');
            return;
        }

        await updatePassword(passwordData);
    });

    console.log('Password form initialized');
}

// =====================================
// 4. PROFILE MENU FUNCTIONALITY
// =====================================

/**
 * Initialize profile menu with better error handling
 */
function initializeProfileMenu() {
    const profileButton = document.getElementById('profile-button');
    const profileMenu = document.getElementById('profile-menu');

    if (!profileButton || !profileMenu) {
        console.error('Profile menu elements not found');
        return false;
    }

    console.log('Profile menu elements found, initializing...');

    // Remove any existing event listeners
    const newProfileButton = profileButton.cloneNode(true);
    profileButton.parentNode.replaceChild(newProfileButton, profileButton);

    // Toggle menu when profile button is clicked
    newProfileButton.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        profileMenu.classList.toggle('hidden');
    });

    // Close menu when clicking outside
    document.addEventListener('click', function (e) {
        if (!newProfileButton.contains(e.target) && !profileMenu.contains(e.target)) {
            if (!profileMenu.classList.contains('hidden')) {
                profileMenu.classList.add('hidden');
            }
        }
    });

    // Close menu when pressing Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !profileMenu.classList.contains('hidden')) {
            profileMenu.classList.add('hidden');
        }
    });

    // Ensure menu is hidden on load
    profileMenu.classList.add('hidden');

    console.log('Profile menu initialized successfully');
    return true;
}

// =====================================
// 5. PASSWORD VISIBILITY TOGGLE
// =====================================

/**
 * Initialize password visibility toggles
 */
function initializePasswordToggle() {
    const toggleButtons = document.querySelectorAll('.toggle-password');

    toggleButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.dataset.target;
            togglePassword(targetId);
        });
    });

    console.log('Password visibility toggles initialized');
}

// =====================================
// 6. MAIN INITIALIZATION
// =====================================

/**
 * Initialize the profile page
 */
function initializeProfilePage() {
    console.log('Initializing Profile Page...');

    const initFunctions = [
        { name: 'Profile Menu', fn: initializeProfileMenu },
        { name: 'Profile Form', fn: initializeProfileForm },
        { name: 'Password Form', fn: initializePasswordForm },
        { name: 'Password Toggle', fn: initializePasswordToggle }
    ];

    initFunctions.forEach(({ name, fn }) => {
        try {
            console.log(`Initializing ${name}...`);
            fn();
        } catch (error) {
            console.error(`Error initializing ${name}:`, error);
        }
    });

    console.log('Profile page initialization complete!');
}

// =====================================
// 7. EVENT LISTENERS
// =====================================

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', initializeProfilePage);

// Handle page visibility changes
document.addEventListener('visibilitychange', () => {
    if (!document.hidden) {
        console.log('Profile page became visible');
    }
});
