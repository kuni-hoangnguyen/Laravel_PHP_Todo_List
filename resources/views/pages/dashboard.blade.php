@extends('layout')
@section('content')
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
        <main class="flex-1 p-8 overflow-y-auto">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Quản lý công việc cá nhân</h2>
                <button
                    class="flex items-center bg-emerald-500 text-white px-6 py-2.5 rounded-lg shadow-lg hover:bg-emerald-600 hover:shadow-emerald-100 transition-all duration-200"
                    id="add-task-btn">
                    <span class="material-icons mr-2">add</span>
                    Thêm Công Việc
                </button>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md border border-emerald-100">
                <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4 mb-6">
                    <div class="relative w-full sm:w-1/3">
                        <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input id="task-search"
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200"
                            placeholder="Tìm kiếm công việc..." type="text" />
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 filter-container">
                        <select id="deadline-filter"
                            class="border border-gray-200 rounded-lg px-4 py-2.5 text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[220px]">
                            <option value="all">Tất cả thời hạn</option>
                            <option value="overdue">Quá hạn</option>
                            <option value="today">Hôm nay</option>
                            <option value="tomorrow">Ngày mai</option>
                            <option value="week" selected>Tuần này</option>
                            <option value="later">Sau đó</option>
                        </select>
                        <button id="sort-button"
                            class="flex items-center justify-center text-gray-600 border border-gray-200 rounded-lg px-4 py-2.5 hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 shadow-sm hover:shadow min-w-[160px]"
                            data-order="asc">
                            <span class="material-icons mr-2">sort</span>
                            Sắp xếp theo hạn
                        </button>
                    </div>
                </div>
                <div class="flex border-b mb-6" id="status-tabs">
                    <button
                        class="status-tab py-2.5 px-6 text-emerald-600 border-b-2 border-emerald-600 font-semibold transition-colors duration-200"
                        data-status="all">Tất cả</button>
                    <button
                        class="status-tab py-2.5 px-6 text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300 transition-colors duration-200"
                        data-status="doing">Đang làm</button>
                    <button
                        class="status-tab py-2.5 px-6 text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-gray-300 transition-colors duration-200"
                        data-status="done">Hoàn thành</button>
                </div>
                <div class="flex flex-col lg:flex-row gap-6 mt-8">
                    <!-- Danh sách công việc chính - có thể cuộn -->
                    <div class="flex-1">
                        <div class="bg-white p-6 rounded-xl shadow-md border border-emerald-100">
                            <h3 class="font-semibold text-lg mb-4 text-emerald-800">Danh sách công việc</h3>
                            <div class="space-y-4 max-h-96 overflow-y-auto pr-2" id="task-list-container">
                                @foreach ($tasks as $task)
                                    <div class="task-item group flex items-start p-4 border rounded-lg hover:bg-gray-50 transition-colors cursor-pointer {{ $task->is_completed ? 'completed' : '' }}"
                                        data-task-id="{{ $task->task_id }}" data-task-title="{{ $task->task_title }}"
                                        data-task-description="{{ $task->task_description }}"
                                        data-task-priority="{{ $task->priority }}"
                                        data-task-deadline="{{ $task->task_deadline }}"
                                        data-task-completed="{{ $task->is_completed }}"
                                        onclick="this.classList.toggle('expanded')" tabindex="0">

                                        <input
                                            class="h-5 w-5 rounded text-blue-600 focus:ring-blue-500 border-gray-300 mr-4 mt-1 complete-task-checkbox"
                                            type="checkbox" data-task-id="{{ $task->task_id }}"
                                            {{ $task->is_completed ? 'checked' : '' }} />

                                        <div class="flex-1">
                                            <p class="font-medium text-gray-800 task-title flex items-center">
                                                {{ $task->task_title }}
                                                <span
                                                    class="inline-block px-2 py-1 rounded-full text-xs font-semibold ml-2
                @if ($task->priority === 'high') bg-red-200 text-red-800
                @elseif($task->priority === 'medium') bg-yellow-200 text-yellow-800
                @else bg-green-200 text-green-800 @endif">
                                                    @if ($task->priority === 'high')
                                                        Cao
                                                    @elseif($task->priority === 'medium')
                                                        Trung bình
                                                    @else
                                                        Thấp
                                                    @endif
                                                </span>
                                            </p>

                                            @if ($task->task_description)
                                                <p class="text-sm text-gray-500 mt-1 task-description">
                                                    {{ $task->task_description }}
                                                </p>
                                            @endif
                                        </div>

                                        <div class="flex items-center ml-4 space-x-4">
                                            <div
                                                class="opacity-0 group-hover:opacity-100 transition-opacity flex space-x-2">
                                                <!-- Nút edit -->
                                                <span
                                                    class="material-icons text-gray-400 cursor-pointer hover:text-blue-600 edit-task-btn"
                                                    data-task-id="{{ $task->task_id }}">
                                                    edit
                                                </span>

                                                <!-- Nút delete -->
                                                <span
                                                    class="material-icons text-gray-400 cursor-pointer hover:text-red-600 delete-task-btn"
                                                    data-task-id="{{ $task->task_id }}">
                                                    delete
                                                </span>
                                            </div>

                                            @if ($task->task_deadline)
                                                <span class="text-sm text-gray-600 task-deadline">
                                                    {{ \Carbon\Carbon::parse($task->task_deadline)->format('d/m/Y') }}
                                                </span>
                                                <span class="material-icons text-gray-400">event</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Các mục bên phải -->
                    <div class="lg:w-80 space-y-6">
                        <!-- Ưu tiên hôm nay -->
                        <div class="bg-white p-6 rounded-xl shadow-md border border-emerald-100">
                            <h3 class="font-semibold text-lg mb-4 text-emerald-800">Ưu Tiên Hôm Nay</h3>
                            <div class="space-y-3">
                                @forelse ($todayTasks as $task)
                                    <div class="flex justify-between items-center">
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-800 truncate"
                                                title="{{ $task->task_title }}">
                                                {{ Str::limit($task->task_title, 30) }}
                                            </p>
                                        </div>
                                        <span
                                            class="ml-2 text-sm font-medium whitespace-nowrap
                    @if ($task->priority === 'high') text-red-500
                    @elseif($task->priority === 'medium') text-yellow-600
                    @else text-green-600 @endif">
                                            @if ($task->priority === 'high')
                                                Cao
                                            @elseif($task->priority === 'medium')
                                                Trung bình
                                            @else
                                                Thấp
                                            @endif
                                        </span>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-sm">Không có công việc nào hôm nay 🎉</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Tiến độ tổng quan -->
                        <div class="bg-white p-6 rounded-xl shadow-md border border-emerald-100">
                            <h3 class="font-semibold text-lg mb-4">Tiến Độ Tổng Quan</h3>
                            <div class="flex flex-col items-center">
                                <!-- Vòng tròn progress -->
                                <div class="relative w-28 h-28">
                                    <svg class="w-full h-full transform -rotate-90">
                                        <circle cx="50%" cy="50%" r="40" stroke="#e5e7eb" stroke-width="10"
                                            fill="transparent" />
                                        <circle cx="50%" cy="50%" r="40" stroke="#10b981" stroke-width="10"
                                            fill="transparent" stroke-dasharray="251"
                                            stroke-dashoffset="{{ 251 - (251 * $progress) / 100 }}"
                                            stroke-linecap="round" />
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="text-xl font-bold text-emerald-600">{{ $progress }}%</span>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500 mt-3">Tỉ lệ hoàn thành công việc</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal thêm/sửa công việc -->
    <div class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden" id="task-modal">
        <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-lg">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800" id="modal-title">Tạo công việc mới</h3>
                <button class="text-gray-400 hover:text-gray-600" id="close-modal-btn">
                    <span class="material-icons">close</span>
                </button>
            </div>
            <form action="{{ route('tasks.store') }}" method="POST" id="task-form">
                @csrf
                <input type="hidden" id="task-id" name="task_id">
                <input type="hidden" id="form-method" name="_method" value="POST">

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2" for="task-title">
                        Tên công việc
                    </label>
                    <input
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        id="task-title" name="task_title" placeholder="Ví dụ: Mua đồ ăn" type="text" required />
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2" for="task-description">
                        Mô tả (không bắt buộc)
                    </label>
                    <textarea
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        id="task-description" name="task_description" placeholder="Thêm chi tiết về công việc..." rows="3"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2" for="task-priority">
                        Độ ưu tiên
                    </label>
                    <select
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        id="task-priority" name="priority">
                        <option value="low">Thấp</option>
                        <option value="medium" selected>Trung bình</option>
                        <option value="high">Cao</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-medium mb-2" for="task-deadline">
                        Thời hạn
                    </label>
                    <div class="relative">
                        <input
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            id="task-deadline" name="task_deadline" type="date" />
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
                    <button class="text-gray-600 hover:text-gray-800" id="cancel-btn" type="button">Hủy</button>
                    <button
                        class="flex items-center bg-emerald-500 text-white px-6 py-2 rounded-lg shadow hover:bg-emerald-600 transition-colors"
                        type="submit" id="submit-btn">
                        <span id="submit-text">Tạo công việc</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal xác nhận xóa -->
    <div class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden" id="confirm-modal">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-auto mt-28">
            <div class="text-center">
                <span class="material-icons text-red-500 text-5xl">warning</span>
                <h3 class="text-xl font-bold text-gray-800 mt-4 mb-2">Xác nhận xóa?</h3>
                <p class="text-gray-600 mb-6">Bạn có chắc chắn muốn xóa công việc này? Hành động này không thể hoàn tác.
                </p>
                <div class="flex justify-center space-x-4">
                    <button class="px-4 py-2 text-gray-600 hover:text-gray-800" id="cancel-delete-btn">
                        Hủy
                    </button>
                    <button class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600" id="confirm-delete-btn">
                        Xóa
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
