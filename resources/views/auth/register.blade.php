<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List - Register</title>
    <link rel="stylesheet" href="css/login.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="soft-background">
        <div class="floating-shapes">
            <div class="soft-blob blob-1"></div>
            <div class="soft-blob blob-2"></div>
            <div class="soft-blob blob-3"></div>
            <div class="soft-blob blob-4"></div>
        </div>
    </div>

    <div class="login-container">
        <div class="soft-card">
            <div class="comfort-header">
                <div class="gentle-logo">
                    <div class="logo-circle">
                        <div class="comfort-icon">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                                <circle cx="16" cy="16" r="14" stroke="currentColor" stroke-width="1.5" />
                                <path d="M10 14a6 6 0 1112 0" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" />
                            </svg>
                        </div>
                        <div class="gentle-glow"></div>
                    </div>
                </div>
                <h1 class="comfort-title">Tạo tài khoản mới</h1>
                <p class="gentle-subtitle">Tham gia cùng chúng tôi ngay hôm nay</p>
            </div>

            <form class="comfort-form" id="registerForm" action="{{ route('register.post') }}" method="POST">
                @csrf
                <!-- Username -->
                <div class="soft-field">
                    <div class="field-container">
                        <input type="text" id="username" name="username" required autocomplete="username"
                            placeholder=" ">
                        <label for="username">Tên người dùng</label>
                        <div class="field-accent"></div>
                    </div>
                    <span class="gentle-error" id="usernameError"></span>
                </div>

                <!-- Email -->
                <div class="soft-field">
                    <div class="field-container">
                        <input type="email" id="email" name="email" required autocomplete="email"
                            placeholder=" ">
                        <label for="email">Địa chỉ email</label>
                        <div class="field-accent"></div>
                    </div>
                    <span class="gentle-error" id="emailError"></span>
                </div>

                <!-- Password -->
                <div class="soft-field">
                    <div class="field-container">
                        <input type="password" id="password" name="password" required autocomplete="new-password"
                            placeholder=" ">
                        <label for="password">Mật khẩu</label>
                        <button tabindex="-1" type="button" class="gentle-toggle" id="passwordToggle"
                            aria-label="Hiển thị/Ẩn mật khẩu">
                            <div class="toggle-icon">
                                <svg class="eye-open" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M10 3c-4.5 0-8.3 3.8-9 7 .7 3.2 4.5 7 9 7s8.3-3.8 9-7c-.7-3.2-4.5-7-9-7z"
                                        stroke="currentColor" stroke-width="1.5" fill="none" />
                                    <circle cx="10" cy="10" r="3" stroke="currentColor" stroke-width="1.5"
                                        fill="none" />
                                </svg>
                                <svg class="eye-closed" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none">
                                    <path
                                        d="M3 3l14 14M8.5 8.5a3 3 0 004 4m2.5-2.5C15 10 12.5 7 10 7c-.5 0-1 .1-1.5.3M10 13c-2.5 0-4.5-2-5-3 .3-.6.7-1.2 1.2-1.7"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                        </button>
                        <div class="field-accent"></div>
                    </div>
                    <span class="gentle-error" id="passwordError"></span>
                </div>

                <!-- Confirm Password -->
                <div class="soft-field">
                    <div class="field-container">
                        <input type="password" id="confirmPassword" name="confirmPassword" required
                            autocomplete="new-password" placeholder=" ">
                        <label for="confirmPassword">Xác nhận mật khẩu</label>
                        <button tabindex="-1" type="button" class="gentle-toggle" id="confirmPasswordToggle"
                            aria-label="Hiển thị/Ẩn mật khẩu">
                            <div class="toggle-icon">
                                <svg class="eye-open" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none">
                                    <path d="M10 3c-4.5 0-8.3 3.8-9 7 .7 3.2 4.5 7 9 7s8.3-3.8 9-7c-.7-3.2-4.5-7-9-7z"
                                        stroke="currentColor" stroke-width="1.5" fill="none" />
                                    <circle cx="10" cy="10" r="3" stroke="currentColor"
                                        stroke-width="1.5" fill="none" />
                                </svg>
                                <svg class="eye-closed" width="20" height="20" viewBox="0 0 20 20"
                                    fill="none">
                                    <path
                                        d="M3 3l14 14M8.5 8.5a3 3 0 004 4m2.5-2.5C15 10 12.5 7 10 7c-.5 0-1 .1-1.5.3M10 13c-2.5 0-4.5-2-5-3 .3-.6.7-1.2 1.2-1.7"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                        </button>
                        <div class="field-accent"></div>
                    </div>
                    <span class="gentle-error" id="confirmPasswordError"></span>
                </div>


                <button type="submit" class="comfort-button">
                    <div class="button-background"></div>
                    <span class="button-text">Đăng ký</span>
                    <div class="button-loader">
                        <div class="gentle-spinner">
                            <div class="spinner-circle"></div>
                        </div>
                    </div>
                    <div class="button-glow"></div>
                </button>
            </form>

            <div class="comfort-signup">
                <span class="signup-text">Đã có tài khoản?</span>
                <a href="{{ URL::to('login') }}" class="comfort-link signup-link">Đăng nhập</a>
            </div>

            <div class="gentle-success" id="successMessage">
                <div class="success-bloom">
                    <div class="bloom-rings">
                        <div class="bloom-ring ring-1"></div>
                        <div class="bloom-ring ring-2"></div>
                        <div class="bloom-ring ring-3"></div>
                    </div>
                    <div class="success-icon">
                        <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                            <path d="M8 14l5 5 11-11" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
                <h3 class="success-title">Đăng ký thành công!</h3>
                <p class="success-desc">Đang chuyển bạn đến trang đăng nhập...</p>
            </div>
        </div>
    </div>

    <script src="js/register.js"></script>
</body>

</html>
