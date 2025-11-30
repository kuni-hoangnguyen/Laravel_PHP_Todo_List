// ========================================
// TASK MANAGEMENT APPLICATION - MAIN FILE
// ========================================

// =====================================
// 1. CONSTANTS & GLOBAL VARIABLES
// =====================================
let selectedTaskId = null; // Task ID being edited or deleted
let isEditMode = false; // Flag to track if modal is in edit mode

// =====================================
// 2. UTILITY FUNCTIONS
// =====================================

/**
 * Hiển thị thông báo toast
 * @param {string} message - Nội dung thông báo
 * @param {string} type - Loại thông báo (success, error, info)
 * @param {number} duration - Thời gian hiển thị (ms)
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
 * Chuyển đổi chuỗi ngày từ định dạng DD/MM/YYYY sang Date object
 * @param {string} dateStr - Chuỗi ngày tháng
 * @returns {Date} - Date object
 */
function parseTaskDate(dateStr) {
    if (!dateStr) {
        console.warn('No date string provided to parseTaskDate');
        return new Date(0);
    }

    try {
        const [day, month, year] = dateStr.split('/');
        if (!day || !month || !year) {
            console.warn('Invalid date format in parseTaskDate:', dateStr);
            return new Date(0);
        }

        const date = new Date(parseInt(year), parseInt(month) - 1, parseInt(day));
        date.setHours(0, 0, 0, 0);

        console.log('Parsed date:', dateStr, '->', date.toDateString());
        return date;
    } catch (error) {
        console.error('Error parsing date:', dateStr, error);
        return new Date(0);
    }
}

/**
 * Format date for input[type="date"]
 * @param {string} dateString - Date string from server (YYYY-MM-DD)
 * @returns {string} - Formatted date string
 */
function formatDateForInput(dateString) {
    if (!dateString) return '';

    try {
        const date = new Date(dateString);
        return date.toISOString().split('T')[0];
    } catch (error) {
        console.error('Error formatting date:', error);
        return '';
    }
}

// =====================================
// 3. API FUNCTIONS
// =====================================

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
 * Create a new task
 * @param {Object} taskData - Task data to create
 */
async function createTask(taskData) {
    try {
        const response = await fetch('/tasks', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCSRFToken()
            },
            body: JSON.stringify(taskData)
        });

        if (response.ok) {
            const result = await response.json();
            showToast('Đã tạo công việc mới thành công!');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            const error = await response.json();
            console.error('Error creating task:', error);
            showToast('Lỗi khi tạo công việc!', 'error');
        }
    } catch (error) {
        console.error('Request failed:', error);
        showToast('Không thể kết nối server!', 'error');
    }
}

/**
 * Update an existing task
 * @param {string} taskId - Task ID to update
 * @param {Object} taskData - Task data to update
 */
async function updateTask(taskId, taskData) {
    try {
        const response = await fetch(`/tasks/${taskId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCSRFToken()
            },
            body: JSON.stringify(taskData)
        });

        if (response.ok) {
            showToast('Đã cập nhật công việc thành công!');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            const error = await response.json();
            console.error('Error updating task:', error);
            showToast('Lỗi khi cập nhật công việc!', 'error');
        }
    } catch (error) {
        console.error('Request failed:', error);
        showToast('Không thể kết nối server!', 'error');
    }
}

/**
 * Delete a task
 * @param {string} taskId - Task ID to delete
 */
async function deleteTask(taskId) {
    try {
        const response = await fetch(`/tasks/${taskId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': getCSRFToken()
            }
        });

        if (response.ok) {
            showToast('Đã xóa công việc thành công!');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            const error = await response.json();
            console.error('Error deleting task:', error);
            showToast('Lỗi khi xóa công việc!', 'error');
        }
    } catch (error) {
        console.error('Request failed:', error);
        showToast('Không thể kết nối server!', 'error');
    }
}

/**
 * Toggle task completion status
 * @param {string} taskId - Task ID
 * @param {boolean} isCompleted - Completion status
 */
async function toggleTaskCompletion(taskId, isCompleted) {
    try {
        const response = await fetch(`/tasks/${taskId}/complete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCSRFToken()
            },
            body: JSON.stringify({ is_completed: isCompleted ? 1 : 0 })
        });

        if (response.ok) {
            showToast(isCompleted ? 'Đã hoàn thành công việc' : 'Đã bỏ hoàn thành', 'info');
            // Update progress bar without reloading
            updateProgressDisplay();
        } else {
            showToast('Lỗi khi cập nhật trạng thái', 'error');
            // Revert checkbox state
            const checkbox = document.querySelector(`[data-task-id="${taskId}"]`);
            if (checkbox) {
                checkbox.checked = !isCompleted;
            }
        }
    } catch (error) {
        console.error('Request failed:', error);
        showToast('Không thể kết nối server!', 'error');
        // Revert checkbox state
        const checkbox = document.querySelector(`[data-task-id="${taskId}"]`);
        if (checkbox) {
            checkbox.checked = !isCompleted;
        }
    }
}

// =====================================
// 4. UI HELPER FUNCTIONS
// =====================================

/**
 * Update progress display without page reload
 */
function updateProgressDisplay() {
    const tasks = document.querySelectorAll('.task-item');
    const completedTasks = document.querySelectorAll('.task-item.completed');

    if (tasks.length === 0) return;

    const progress = Math.round((completedTasks.length / tasks.length) * 100);

    // Update progress circle
    const progressText = document.querySelector('.text-xl.font-bold.text-emerald-600');
    const progressCircle = document.querySelector('circle[stroke="#10b981"]');

    if (progressText) {
        progressText.textContent = `${progress}%`;
    }

    if (progressCircle) {
        const circumference = 251;
        const offset = circumference - (circumference * progress) / 100;
        progressCircle.style.strokeDashoffset = offset;
    }
}

/**
 * Reset modal form to default state
 */
function resetModalForm() {
    const form = document.getElementById('task-form');
    const modalTitle = document.getElementById('modal-title');
    const submitBtn = document.getElementById('submit-text');
    const taskIdInput = document.getElementById('task-id');
    const methodInput = document.getElementById('form-method');

    if (form) form.reset();
    if (modalTitle) modalTitle.textContent = 'Tạo công việc mới';
    if (submitBtn) submitBtn.textContent = 'Tạo công việc';
    if (taskIdInput) taskIdInput.value = '';
    if (methodInput) methodInput.value = 'POST';

    selectedTaskId = null;
    isEditMode = false;
}

/**
 * Setup modal for editing task
 * @param {HTMLElement} taskElement - Task element containing data
 */
function setupEditModal(taskElement) {
    const taskId = taskElement.dataset.taskId;
    const taskTitle = taskElement.dataset.taskTitle;
    const taskDescription = taskElement.dataset.taskDescription;
    const taskPriority = taskElement.dataset.taskPriority;
    const taskDeadline = taskElement.dataset.taskDeadline;

    // Update modal elements
    document.getElementById('modal-title').textContent = 'Chỉnh sửa công việc';
    document.getElementById('submit-text').textContent = 'Cập nhật';
    document.getElementById('task-id').value = taskId;
    document.getElementById('form-method').value = 'PUT';

    // Fill form with current data
    document.getElementById('task-title').value = taskTitle || '';
    document.getElementById('task-description').value = taskDescription || '';
    document.getElementById('task-priority').value = taskPriority || 'medium';
    document.getElementById('task-deadline').value = formatDateForInput(taskDeadline);

    selectedTaskId = taskId;
    isEditMode = true;
}

// =====================================
// 5. PROFILE MENU FUNCTIONALITY
// =====================================

/**
 * Initialize profile menu with better error handling
 */
function initializeProfileMenu() {
    const profileButton = document.getElementById('profile-button');
    const profileMenu = document.getElementById('profile-menu');

    // Enhanced error checking
    if (!profileButton) {
        console.error('Profile button element not found (id="profile-button")');
        return false;
    }

    if (!profileMenu) {
        console.error('Profile menu element not found (id="profile-menu")');
        return false;
    }

    console.log('Profile menu elements found, initializing...');

    // Remove any existing event listeners to prevent duplicates
    const newProfileButton = profileButton.cloneNode(true);
    profileButton.parentNode.replaceChild(newProfileButton, profileButton);

    // Toggle menu when profile button is clicked
    newProfileButton.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        console.log('Profile button clicked');

        // Toggle the hidden class
        profileMenu.classList.toggle('hidden');

        // Log current state for debugging
        console.log('Profile menu hidden class:', profileMenu.classList.contains('hidden'));
    });

    // Close menu when clicking outside
    document.addEventListener('click', function (e) {
        // Check if click is outside both button and menu
        if (!newProfileButton.contains(e.target) && !profileMenu.contains(e.target)) {
            if (!profileMenu.classList.contains('hidden')) {
                profileMenu.classList.add('hidden');
                console.log('Profile menu closed by outside click');
            }
        }
    });

    // Close menu when pressing Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !profileMenu.classList.contains('hidden')) {
            profileMenu.classList.add('hidden');
            console.log('Profile menu closed by Escape key');
        }
    });

    // Ensure menu is hidden on page load
    profileMenu.classList.add('hidden');

    console.log('Profile menu initialized successfully');
    return true;
}

// =====================================
// 6. TASK COMPLETION FUNCTIONALITY
// =====================================

/**
 * Initialize task completion handling with single event listener
 */
function initializeTaskCompletion() {
    const taskContainer = document.getElementById('task-list-container');

    if (!taskContainer) {
        console.error('Task container not found');
        return;
    }

    console.log('Task completion initialized');

    // Use event delegation for better performance
    taskContainer.addEventListener('change', async (e) => {
        if (e.target.classList.contains('complete-task-checkbox')) {
            const checkbox = e.target;
            const taskId = checkbox.dataset.taskId;
            const isCompleted = checkbox.checked;
            const taskItem = checkbox.closest('.task-item');

            // Update UI immediately for better UX
            if (isCompleted) {
                taskItem.classList.add('completed');
            } else {
                taskItem.classList.remove('completed');
            }

            // Update server
            await toggleTaskCompletion(taskId, isCompleted);

            // Apply current filters after completion change
            applyFilters();
        }
    });
}

// =====================================
// 7. FILTERING FUNCTIONALITY
// =====================================

/**
 * Apply all filters to task list
 */
function applyFilters() {
    const tasks = document.querySelectorAll('.task-item');
    if (tasks.length === 0) return;

    // Get current filter values
    const activeTab = document.querySelector('.status-tab.text-emerald-600');
    const status = activeTab ? activeTab.dataset.status : 'all';

    const searchInput = document.getElementById('task-search');
    const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';

    const deadlineFilter = document.getElementById('deadline-filter');
    const deadlineValue = deadlineFilter ? deadlineFilter.value : 'all';

    // Setup date ranges
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const tomorrow = new Date(today);
    tomorrow.setDate(today.getDate() + 1);
    const weekEnd = new Date(today);
    weekEnd.setDate(today.getDate() + 7);

    let visibleCount = 0;

    tasks.forEach(task => {
        let visible = true;

        // 1. Filter by status
        const isCompleted = task.classList.contains('completed');
        if (status === 'doing' && isCompleted) visible = false;
        if (status === 'done' && !isCompleted) visible = false;

        // 2. Filter by search term
        if (searchTerm) {
            const title = task.querySelector('.task-title')?.textContent.toLowerCase() || '';
            const description = task.querySelector('.task-description')?.textContent.toLowerCase() || '';
            if (!title.includes(searchTerm) && !description.includes(searchTerm)) {
                visible = false;
            }
        }

        // 3. Filter by deadline
        if (deadlineValue !== 'all') {
            const dateElement = task.querySelector('.task-deadline');
            if (dateElement) {
                const taskDate = parseTaskDate(dateElement.textContent);

                switch (deadlineValue) {
                    case 'today':
                        if (taskDate.getTime() !== today.getTime()) visible = false;
                        break;
                    case 'tomorrow':
                        if (taskDate.getTime() !== tomorrow.getTime()) visible = false;
                        break;
                    case 'week':
                        if (taskDate < today || taskDate > weekEnd) visible = false;
                        break;
                    case 'overdue':
                        if (taskDate >= today) visible = false;
                        break;
                    case 'later':
                        if (taskDate <= weekEnd) visible = false;
                        break;
                }
            } else if (deadlineValue !== 'all') {
                // Hide tasks without deadline when filtering by specific deadline
                visible = false;
            }
        }

        // Apply visibility
        task.style.display = visible ? 'flex' : 'none';
        if (visible) visibleCount++;
    });

    console.log(`Filtered tasks: ${visibleCount} visible out of ${tasks.length} total`);
}

/**
 * Initialize all filter controls
 */
function initializeFilters() {
    // Status tabs
    const statusTabs = document.querySelectorAll('.status-tab');
    statusTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Remove active state from all tabs
            statusTabs.forEach(t => {
                t.classList.remove('text-emerald-600', 'border-emerald-600');
                t.classList.add('text-gray-500', 'border-transparent');
            });

            // Add active state to clicked tab
            tab.classList.remove('text-gray-500', 'border-transparent');
            tab.classList.add('text-emerald-600', 'border-emerald-600');

            applyFilters();
        });
    });

    // Search input
    const searchInput = document.getElementById('task-search');
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            applyFilters();
        });
    }

    // Deadline filter
    const deadlineFilter = document.getElementById('deadline-filter');
    if (deadlineFilter) {
        deadlineFilter.addEventListener('change', () => {
            applyFilters();
        });
    }

    // Apply default filter on page load
    setTimeout(() => {
        applyFilters();
    }, 100);

    console.log('Filters initialized with default "week" filter');
}

// =====================================
// 8. TASK SORTING FUNCTIONALITY
// =====================================

/**
 * Initialize task sorting by deadline
 */
function initializeTaskSorting() {
    const sortButton = document.getElementById('sort-button');

    if (!sortButton) {
        console.error('Sort button not found');
        return;
    }

    console.log('Task sorting initialized');

    sortButton.addEventListener('click', function () {
        const taskList = document.getElementById('task-list-container');
        if (!taskList) return;

        const tasks = Array.from(taskList.querySelectorAll('.task-item'));
        if (tasks.length === 0) return;

        // Toggle sort order
        const currentOrder = this.dataset.order || 'asc';
        const newOrder = currentOrder === 'asc' ? 'desc' : 'asc';
        this.dataset.order = newOrder;

        // Sort tasks by deadline
        tasks.sort((a, b) => {
            const dateElementA = a.querySelector('.task-deadline');
            const dateElementB = b.querySelector('.task-deadline');

            // Handle tasks without deadlines
            if (!dateElementA && !dateElementB) return 0;
            if (!dateElementA) return 1; // Tasks without deadline go to end
            if (!dateElementB) return -1;

            const dateA = parseTaskDate(dateElementA.textContent);
            const dateB = parseTaskDate(dateElementB.textContent);

            return newOrder === 'asc' ? dateA - dateB : dateB - dateA;
        });

        // Re-append tasks in new order
        tasks.forEach(task => taskList.appendChild(task));

        // Update button appearance
        const icon = this.querySelector('.material-icons');
        if (icon) {
            icon.textContent = newOrder === 'asc' ? 'arrow_upward' : 'arrow_downward';
        }

        console.log(`Tasks sorted in ${newOrder} order`);
    });
}

// =====================================
// 9. MODAL FUNCTIONALITY
// =====================================

/**
 * Initialize all modal functionality
 */
function initializeModals() {
    const addTaskBtn = document.getElementById('add-task-btn');
    const taskModal = document.getElementById('task-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const cancelBtn = document.getElementById('cancel-btn');
    const confirmModal = document.getElementById('confirm-modal');

    if (!addTaskBtn || !taskModal || !closeModalBtn || !cancelBtn) {
        console.error('Modal elements not found');
        return;
    }

    // Open modal for new task
    addTaskBtn.addEventListener('click', () => {
        resetModalForm();
        taskModal.classList.remove('hidden');
        taskModal.classList.add('flex');
    });

    // Close modal functions
    const closeModal = () => {
        taskModal.classList.remove('flex');
        taskModal.classList.add('hidden');
        resetModalForm();
    };

    closeModalBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);

    // Close modal when clicking outside
    taskModal.addEventListener('click', (e) => {
        if (e.target === taskModal) {
            closeModal();
        }
    });

    // Handle form submission
    initializeTaskForm();

    console.log('Modals initialized');
}

/**
 * Initialize task form handling
 */
function initializeTaskForm() {
    const taskForm = document.getElementById('task-form');

    if (!taskForm) {
        console.error('Task form not found');
        return;
    }

    taskForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = {
            task_title: document.getElementById('task-title').value,
            task_description: document.getElementById('task-description').value,
            priority: document.getElementById('task-priority').value,
            task_deadline: document.getElementById('task-deadline').value
        };

        if (isEditMode && selectedTaskId) {
            await updateTask(selectedTaskId, formData);
        } else {
            await createTask(formData);
        }

        // Close modal
        const taskModal = document.getElementById('task-modal');
        taskModal.classList.remove('flex');
        taskModal.classList.add('hidden');
        resetModalForm();
    });
}

// =====================================
// 10. TASK ACTIONS (EDIT/DELETE)
// =====================================

/**
 * Initialize task action handlers
 */
function initializeTaskActions() {
    const taskContainer = document.getElementById('task-list-container');
    const confirmModal = document.getElementById('confirm-modal');
    const cancelDeleteBtn = document.getElementById('cancel-delete-btn');
    const confirmDeleteBtn = document.getElementById('confirm-delete-btn');

    if (!taskContainer || !confirmModal) {
        console.error('Task action elements not found');
        return;
    }

    // Use event delegation for task actions
    taskContainer.addEventListener('click', (e) => {
        const editBtn = e.target.closest('.edit-task-btn');
        const deleteBtn = e.target.closest('.delete-task-btn');

        if (editBtn) {
            e.stopPropagation();
            const taskItem = editBtn.closest('.task-item');
            if (taskItem) {
                handleEditTask(taskItem);
            }
        }

        if (deleteBtn) {
            e.stopPropagation();
            const taskId = deleteBtn.dataset.taskId;
            if (taskId) {
                handleDeleteTask(taskId);
            }
        }
    });

    // Delete confirmation handlers
    cancelDeleteBtn.addEventListener('click', () => {
        confirmModal.classList.remove('flex');
        confirmModal.classList.add('hidden');
        selectedTaskId = null;
    });

    confirmDeleteBtn.addEventListener('click', async () => {
        if (selectedTaskId) {
            await deleteTask(selectedTaskId);
        }
        confirmModal.classList.remove('flex');
        confirmModal.classList.add('hidden');
        selectedTaskId = null;
    });

    // Close confirm modal when clicking outside
    confirmModal.addEventListener('click', (e) => {
        if (e.target === confirmModal) {
            confirmModal.classList.remove('flex');
            confirmModal.classList.add('hidden');
            selectedTaskId = null;
        }
    });

    console.log('Task actions initialized');
}

/**
 * Handle edit task action
 * @param {HTMLElement} taskElement - Task element to edit
 */
function handleEditTask(taskElement) {
    setupEditModal(taskElement);

    const taskModal = document.getElementById('task-modal');
    taskModal.classList.remove('hidden');
    taskModal.classList.add('flex');
}

/**
 * Handle delete task action
 * @param {string} taskId - Task ID to delete
 */
function handleDeleteTask(taskId) {
    selectedTaskId = taskId;

    const confirmModal = document.getElementById('confirm-modal');
    confirmModal.classList.remove('hidden');
    confirmModal.classList.add('flex');
}

// =====================================
// 11. MAIN INITIALIZATION
// =====================================

/**
 * Initialize the entire application
 */
function initializeApp() {
    console.log('Initializing Task Management Application...');

    const initFunctions = [
        { name: 'Profile Menu', fn: initializeProfileMenu },
        { name: 'Task Completion', fn: initializeTaskCompletion },
        { name: 'Filters', fn: initializeFilters },
        { name: 'Task Sorting', fn: initializeTaskSorting },
        { name: 'Modals', fn: initializeModals },
        { name: 'Task Actions', fn: initializeTaskActions }
    ];

    initFunctions.forEach(({ name, fn }) => {
        try {
            console.log(`Initializing ${name}...`);
            fn();
        } catch (error) {
            console.error(`Error initializing ${name}:`, error);
        }
    });

    console.log('Application initialization complete!');
}

// =====================================
// 12. EVENT LISTENERS
// =====================================

// Initialize app when DOM is fully loaded
document.addEventListener('DOMContentLoaded', initializeApp);

// Handle page visibility changes to refresh data if needed
document.addEventListener('visibilitychange', () => {
    if (!document.hidden) {
        // Page became visible, could refresh data here if needed
        console.log('Page became visible');
    }
});
