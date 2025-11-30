@extends('layout')
@section('content')
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
        <main class="flex-1 p-8 overflow-y-auto">
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Báo cáo & Thống kê</h2>
                    <p class="text-gray-600 mt-1">Tổng quan về hiệu suất công việc và dự án</p>
                </div>
                <div class="flex items-center gap-3">
                    <button
                        class="time-filter-btn px-4 py-2 rounded-lg text-sm font-medium bg-white text-gray-700 hover:bg-gray-50 transition-colors"
                        data-period="week">
                        7 ngày
                    </button>
                    <button
                        class="time-filter-btn px-4 py-2 rounded-lg text-sm font-medium bg-white text-gray-700 hover:bg-gray-50 transition-colors"
                        data-period="month">
                        30 ngày
                    </button>
                    <button
                        class="time-filter-btn px-4 py-2 rounded-lg text-sm font-medium bg-white text-gray-700 hover:bg-gray-50 transition-colors"
                        data-period="year">
                        1 năm
                    </button>
                </div>
            </div>

            <!-- Metrics Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                {{-- Tổng công việc --}}
                <div class="metric-card bg-white p-6 rounded-xl shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Tổng công việc đã tạo</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $totalTasks }}</p>
                        </div>
                        <span class="material-icons text-sky-500 text-3xl">assignment</span>
                    </div>
                </div>

                {{-- Đã hoàn thành --}}
                <div class="metric-card success bg-white p-6 rounded-xl shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Đã hoàn thành</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $completedTasks }}</p>
                            <p class="text-sm text-green-600 mt-1">
                                Tỷ lệ: {{ $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0 }}%
                            </p>
                        </div>
                        <span class="material-icons text-green-500 text-3xl">check_circle</span>
                    </div>
                </div>

                {{-- Đang thực hiện --}}
                <div class="metric-card warning bg-white p-6 rounded-xl shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Đang thực hiện</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $doingTasks }}</p>
                            <p class="text-sm text-amber-600 mt-1">
                                {{ $priorityStats['high'] ?? 0 }} ưu tiên cao
                            </p>

                        </div>
                        <span class="material-icons text-amber-500 text-3xl">schedule</span>
                    </div>
                </div>

                {{-- Quá hạn --}}
                <div class="metric-card danger bg-white p-6 rounded-xl shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Quá hạn</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $overdueTasks }}</p>
                            <p class="text-sm text-red-600 mt-1">Cần xử lý gấp</p>
                        </div>
                        <span class="material-icons text-red-500 text-3xl">warning</span>
                    </div>
                </div>

            </div>


            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Task Completion Chart -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-emerald-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Xu hướng hoàn thành công việc</h3>
                    <div class="chart-container">
                        <canvas id="completionChart"></canvas>
                    </div>
                </div>

                <!-- Priority Distribution -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-emerald-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Phân bố độ ưu tiên</h3>
                    <div class="chart-container">
                        <canvas id="priorityChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Productivity & Urgent Tasks -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Daily Productivity -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-emerald-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Năng suất hàng ngày</h3>
                    <div class="space-y-3">
                        @forelse($dailyProductivity as $day)
                            <div
                                class="flex justify-between items-center p-3 rounded-lg
            {{ $day['is_today'] ? 'bg-blue-50 border border-blue-200' : 'bg-gray-50' }}">
                                <div>
                                    <span class="font-medium text-gray-800">{{ $day['day_name'] }}</span>
                                    <p class="text-sm text-gray-600">{{ $day['completed'] }} công việc</p>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-12 h-2 bg-gray-200 rounded-full mr-2">
                                        <div class="h-2 rounded-full {{ $day['progress_color'] }}"
                                            style="width: {{ $day['percentage'] }}%">
                                        </div>
                                    </div>
                                    <span class="text-sm font-medium {{ $day['text_color'] }}">
                                        {{ $day['percentage'] }}%
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <span class="material-icons text-gray-400 text-3xl">calendar_today</span>
                                <p class="text-gray-500 mt-2">Không có dữ liệu năng suất</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Urgent Tasks -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-emerald-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Công việc cần ưu tiên</h3>
                    <div class="space-y-3">
                        @forelse($urgentTasks as $task)
                            <div
                                class="flex items-start p-4 rounded-lg border
                                {{ $task['urgency_class'] }}">
                                <span
                                    class="material-icons {{ $task['icon_color'] }} mr-3 mt-1">{{ $task['icon'] }}</span>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800">{{ $task['task_title'] }}</p>
                                    <p class="text-sm text-gray-600 mt-1">Hạn: {{ $task['deadline_text'] }}</p>
                                    <div class="flex items-center mt-2">
                                        <span
                                            class="text-xs font-medium px-2 py-1 rounded-full {{ $task['priority_class'] }}">
                                            {{ $task['priority_text'] }}
                                        </span>
                                        @if ($task['task_description'])
                                            <span class="text-xs text-gray-500 ml-2 truncate"
                                                title="{{ $task['task_description'] }}">
                                                {{ Str::limit($task['task_description'], 20) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6">
                                <span class="material-icons text-green-500 text-4xl">check_circle</span>
                                <p class="text-gray-600 mt-2 font-medium">Tuyệt vời!</p>
                                <p class="text-gray-500 text-sm">Không có công việc cấp bách nào</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Completion trend chart
            const cCtx = document.getElementById('completionChart')?.getContext('2d');
            if (cCtx) {
                new Chart(cCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode(array_keys($completionTrend)) !!},
                        datasets: [{
                            label: 'Công việc hoàn thành',
                            data: {!! json_encode(array_values($completionTrend)) !!},
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16,185,129,0.2)',
                            fill: true,
                            tension: 0.3,
                            pointRadius: 4,
                            pointBackgroundColor: '#10b981'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Số công việc'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Ngày'
                                }
                            }
                        }
                    }
                });
            }

            // Priority chart
            const pCtx = document.getElementById('priorityChart')?.getContext('2d');
            if (pCtx) {
                new Chart(pCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Cao', 'Trung bình', 'Thấp'],
                        datasets: [{
                            data: [
                                {{ $priorityStats['high'] }},
                                {{ $priorityStats['medium'] }},
                                {{ $priorityStats['low'] }}
                            ],
                            backgroundColor: ['#ef4444', '#f59e0b', '#22c55e'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const buttons = document.querySelectorAll(".time-filter-btn");

            // Bắt sự kiện click để thay đổi active
            buttons.forEach(btn => {
                btn.addEventListener("click", () => {
                    buttons.forEach(b => {
                        b.classList.remove("time-filter-active");
                        b.classList.add("bg-white", "text-gray-700", "hover:bg-gray-50");
                    });

                    btn.classList.add("time-filter-active");
                    btn.classList.remove("bg-white", "text-gray-700", "hover:bg-gray-50");

                    // Chuyển trang theo filter
                    const period = btn.dataset.period;
                    const url = new URL(window.location.href);
                    url.searchParams.set("period", period);
                    window.location.href = url.toString();
                });
            });

            // Khi load trang -> set active theo period (hoặc mặc định = week)
            const urlParams = new URLSearchParams(window.location.search);
            const currentPeriod = urlParams.get("period") || "week";
            const activeBtn = document.querySelector(`.time-filter-btn[data-period="${currentPeriod}"]`);
            if (activeBtn) {
                activeBtn.classList.add("time-filter-active");
                activeBtn.classList.remove("bg-white", "text-gray-700", "hover:bg-gray-50");
            }
        });
    </script>
@endsection
