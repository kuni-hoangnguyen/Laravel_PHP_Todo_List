<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Todo List - Quản lý công việc cá nhân</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="{{ URL::asset('css/style.css') }}" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body style="background-color: #f8fafc;">
    <div class="flex h-screen">
        <aside class="sidebar bg-white border-r border-slate-100" id="sidebar">
            <div class="sidebar-content">
                <div class="flex items-center justify-between h-20 border-b px-4">
                    <div class="flex items-center logo-container">
                        <span class="material-icons text-emerald-600 text-3xl">check_circle</span>
                        <h1 class="text-xl font-bold ml-2">Todo</h1>
                    </div>
                    <button
                        class="w-10 h-10 flex items-center justify-center text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors"
                        id="sidebar-toggle">
                        <span class="material-icons">menu</span>
                    </button>
                </div>
                <nav class="mt-6">
                    <a class="nav-item flex items-center px-6 py-3
        {{ Request::is('dashboard') ? 'text-gray-700 bg-gray-100 font-semibold' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }} relative"
                        href="{{ URL::to('dashboard') }}">
                        <span class="material-icons">dashboard</span>
                        <span class="ml-3 nav-text">Trang Chủ</span>
                        <div class="nav-tooltip">Trang Chủ</div>
                    </a>

                    <!-- Thống kê chi tiết -->
                    <a class="nav-item flex items-center px-6 py-3
        {{ Request::is('reports') ? 'text-gray-700 bg-gray-100 font-semibold' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }} relative"
                        href="{{ URL::to('reports') }}">
                        <span class="material-icons">analytics</span>
                        <span class="ml-3 nav-text">Thống Kê Chi Tiết</span>
                        <div class="nav-tooltip">Thống Kê Chi Tiết</div>
                    </a>
                </nav>


            </div>
        </aside>

        @yield('content')

        <!-- Toast thông báo -->
        <div class="fixed bottom-4 right-4 z-50 hidden" id="toast">
            <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-4">
                <div class="flex items-center">
                    <span class="material-icons text-emerald-500 mr-2">check_circle</span>
                    <p class="text-gray-600" id="toast-message"></p>
                </div>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div class="mobile-menu">
            <div class="mobile-menu-nav">

                <!-- Trang chủ -->
                <a href="{{ URL::to('dashboard') }}"
                    class="mobile-menu-item {{ Request::is('dashboard') ? 'active' : '' }}">
                    <span class="material-icons">dashboard</span>
                    <span class="label">Trang Chủ</span>
                </a>

                <!-- Thống kê -->
                <a href="{{ URL::to('reports') }}"
                    class="mobile-menu-item {{ Request::is('reports') ? 'active' : '' }}">
                    <span class="material-icons">analytics</span>
                    <span class="label">Thống kê</span>
                </a>

                <!-- Hồ sơ -->
                <a href="{{ URL::to('profile') }}"
                    class="mobile-menu-item {{ Request::is('profile') ? 'active' : '' }}">
                    <span class="material-icons">account_circle</span>
                    <span class="label">Tài khoản</span>
                </a>
            </div>
        </div>


        <script src="{{ URL::asset('js/app.js') }}"></script>
</body>

</html>
