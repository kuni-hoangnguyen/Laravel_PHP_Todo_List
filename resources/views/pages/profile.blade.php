@extends('layout')
@section('content')
    <div class="flex-1 flex flex-col">
        <!-- Toast Notification -->
        <div id="toast"
            class="fixed top-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 hidden transform transition-transform duration-300 translate-x-full">
            <div class="flex items-center space-x-2">
                <span class="material-icons">check_circle</span>
                <span id="toast-message">Thông báo</span>
            </div>
        </div>

        <div class="flex-1 flex flex-col">
            <header class="flex items-center justify-between h-16 bg-white border-b border-emerald-100 px-8">
                <div></div>
                <!-- Profile Menu Section - Fixed Version -->
                <div class="flex items-center space-x-6">
                    <div class="relative">
                        <!-- Profile Button -->
                        <button type="button"
                            class="flex items-center space-x-3 border rounded-lg px-4 py-2 hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            id="profile-button" aria-expanded="false" aria-haspopup="true">
                            <span class="material-icons text-gray-500">account_circle</span>
                            <div class="text-left mx-2">
                                <p class="text-sm font-medium text-gray-700">{{ $user_name }}</p>
                            </div>
                            <span class="material-icons text-gray-400 text-lg">expand_more</span>
                        </button>

                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-200 hidden"
                            id="profile-menu" role="menu" aria-orientation="vertical" aria-labelledby="profile-button">

                            <!-- User Info Section -->
                            <div class="px-4 py-3 border-b border-gray-200">
                                <div class="flex items-center space-x-3">
                                    <span class="material-icons text-gray-500 text-xl">account_circle</span>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">{{ $user_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user_email }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Menu Items -->
                            <div class="py-1">
                                <a class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors"
                                    href="{{ URL::to('profile') }}" role="menuitem">
                                    <span class="material-icons text-gray-400 mr-3 text-lg">person</span>
                                    Hồ sơ của tôi
                                </a>
                                <a class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-gray-100 transition-colors"
                                    href="{{ URL::to('logout') }}" role="menuitem">
                                    <span class="material-icons text-red-500 mr-3 text-lg">logout</span>
                                    Đăng xuất
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 p-8 overflow-y-auto">
                <div class="max-w-4xl mx-auto">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                        <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Hồ sơ cá nhân</h2>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Profile Info Card -->
                        <div class="lg:col-span-1">
                            <div class="bg-white rounded-xl shadow-md border border-emerald-100 p-6">
                                <div class="text-center">
                                    <div
                                        class="w-24 h-24 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span class="material-icons text-emerald-600 text-4xl">account_circle</span>
                                    </div>
                                    <h3 class="text-xl font-semibold text-gray-800 mb-2" id="display-name">
                                        {{ $user_name }}
                                    </h3>
                                    <p class="text-gray-600 mb-4" id="display-email">{{ $user_email }}</p>
                                    <div class="bg-emerald-50 rounded-lg p-4">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-sm text-gray-600">Tổng số công việc</span>
                                            <span class="font-semibold text-emerald-600">{{ $totalTasks }}</span>
                                        </div>
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-sm text-gray-600">Đã hoàn thành</span>
                                            <span class="font-semibold text-emerald-600">{{ $completedTasks }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Tỷ lệ hoàn thành</span>
                                            <span class="font-semibold text-emerald-600">{{ $completionRate }}%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Forms -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Edit Profile Form -->
                            <div class="bg-white rounded-xl shadow-md border border-emerald-100 p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                                    <span class="material-icons mr-2 text-emerald-600">person</span>
                                    Thông tin cá nhân
                                </h3>
                                <form id="profile-form">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2" for="user-name">
                                                Họ và tên
                                            </label>
                                            <input
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                                id="user-name" name="user_name" type="text" value="{{ $user_name }}"
                                                required />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2" for="user-email">
                                                Email
                                            </label>
                                            <input
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                                id="user-email" name="user_email" type="email"
                                                value="{{ $user_email }}" required />
                                        </div>
                                    </div>
                                    <div class="flex justify-end mt-6">
                                        <button
                                            class="flex items-center bg-emerald-500 text-white px-6 py-2.5 rounded-lg shadow-lg hover:bg-emerald-600 hover:shadow-emerald-100 transition-all duration-200 profile-update-btn"
                                            type="submit">
                                            <span class="material-icons mr-2 text-lg">save</span>
                                            <span class="btn-text">Cập nhật thông tin</span>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Change Password Form -->
                            <div class="bg-white rounded-xl shadow-md border border-emerald-100 p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                                    <span class="material-icons mr-2 text-emerald-600">lock</span>
                                    Đổi mật khẩu
                                </h3>
                                <form id="password-form">
                                    @csrf
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2"
                                                for="current-password">
                                                Mật khẩu hiện tại
                                            </label>
                                            <div class="relative">
                                                <input
                                                    class="w-full px-4 py-2.5 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                                    id="current-password" name="current_password" type="password"
                                                    required />
                                                <button type="button"
                                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 toggle-password"
                                                    data-target="current-password">
                                                    <span class="material-icons text-lg">visibility</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2"
                                                for="new-password">
                                                Mật khẩu mới
                                            </label>
                                            <div class="relative">
                                                <input
                                                    class="w-full px-4 py-2.5 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                                    id="new-password" name="new_password" type="password" required
                                                    minlength="6" />
                                                <button type="button"
                                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 toggle-password"
                                                    data-target="new-password">
                                                    <span class="material-icons text-lg">visibility</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2"
                                                for="new-password-confirmation">
                                                Xác nhận mật khẩu mới
                                            </label>
                                            <div class="relative">
                                                <input
                                                    class="w-full px-4 py-2.5 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                                    id="new-password-confirmation" name="new_password_confirmation"
                                                    type="password" required />
                                                <button type="button"
                                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 toggle-password"
                                                    data-target="new-password-confirmation">
                                                    <span class="material-icons text-lg">visibility</span>
                                                </button>
                                            </div>
                                            <div class="mt-1 text-sm" id="password-match-status"></div>
                                        </div>
                                    </div>
                                    <div class="flex justify-end mt-6">
                                        <button
                                            class="flex items-center bg-emerald-500 text-white px-6 py-2.5 rounded-lg shadow-lg hover:bg-emerald-600 hover:shadow-emerald-100 transition-all duration-200 password-update-btn"
                                            type="submit">
                                            <span class="material-icons mr-2 text-lg">security</span>
                                            <span class="btn-text">Đổi mật khẩu</span>
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="js/profile.js"></script>
@endsection
