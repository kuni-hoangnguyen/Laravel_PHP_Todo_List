<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $userId     = session('user_id');
        $user_name  = session('user_name');
        $user_email = session('user_email');

        // Lấy tham số period từ request, mặc định là 'week'
        $period = $request->get('period', 'week');

        // Lấy thông tin user
        $user = DB::table('users')->where('user_id', $userId)->first();

        // Tính toán ngày bắt đầu dựa trên period
        $startDate = $this->getStartDate($period);
        $endDate   = now();

        // Lấy dữ liệu cơ bản
        $totalTasks = DB::table('tasks')
            ->where('user_id', $userId)
            ->where('created_at', '>=', $startDate)
            ->count();

        $completedTasks = DB::table('tasks')
            ->where('user_id', $userId)
            ->where('is_completed', 1)
            ->where('created_at', '>=', $startDate)
            ->count();

        $doingTasks = DB::table('tasks')
            ->where('user_id', $userId)
            ->where('is_completed', 0)
            ->where('created_at', '>=', $startDate)
            ->count();

        $overdueTasks = DB::table('tasks')
            ->where('user_id', $userId)
            ->where('is_completed', 0)
            ->where('task_deadline', '<', now()->toDateString())
            ->count();

        // Thống kê độ ưu tiên
        $priorityStats = [
            'high'   => DB::table('tasks')
                ->where('user_id', $userId)
                ->where('priority', 'high')
                ->where('created_at', '>=', $startDate)
                ->count(),
            'medium' => DB::table('tasks')
                ->where('user_id', $userId)
                ->where('priority', 'medium')
                ->where('created_at', '>=', $startDate)
                ->count(),
            'low'    => DB::table('tasks')
                ->where('user_id', $userId)
                ->where('priority', 'low')
                ->where('created_at', '>=', $startDate)
                ->count(),
        ];

        // Xu hướng hoàn thành công việc
        $completionTrend = $this->getCompletionTrend($userId, $period);

        // Năng suất hàng ngày (theo period)
        $dailyProductivity = $this->getDailyProductivity($userId, $period);

        // Công việc cần ưu tiên
        $urgentTasks = $this->getUrgentTasks($userId);

        return view('pages.reports', compact(
            'user_name', 'user_email',
            'totalTasks', 'completedTasks', 'doingTasks', 'overdueTasks',
            'priorityStats', 'completionTrend', 'dailyProductivity', 'urgentTasks', 'period'
        ));
    }

    /**
     * Tính toán ngày bắt đầu dựa trên period
     */
    private function getStartDate($period)
    {
        switch ($period) {
            case 'week':
                return now()->subDays(7);
            case 'month':
                return now()->subDays(30);
            case 'year':
                return now()->subYear();
            default:
                return now()->subDays(7);
        }
    }

    /**
     * Lấy xu hướng hoàn thành công việc theo ngày
     */
    private function getCompletionTrend($userId, $period)
    {
        $days  = $period === 'week' ? 7 : ($period === 'month' ? 30 : 365);
        $trend = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date      = now()->subDays($i)->toDateString();
            $completed = DB::table('tasks')
                ->where('user_id', $userId)
                ->where('is_completed', 1)
                ->whereDate('updated_at', $date)
                ->count();

            $trend[Carbon::parse($date)->format('d/m')] = $completed;
        }

        return $trend;
    }

    /**
     * Lấy năng suất hàng ngày (theo period)
     */
    private function getDailyProductivity($userId)
    {
        $productivity = [];
        $today        = now();
        $days         = 7; // luôn 7 ngày gần nhất
        $startDate    = $today->copy()->subDays($days - 1)->toDateString();

        // Lấy tất cả task đã hoàn thành trong 7 ngày (dựa vào updated_at)
        $completedTasks = DB::table('tasks')
            ->where('user_id', $userId)
            ->where('is_completed', 1)
            ->whereBetween(DB::raw('DATE(updated_at)'), [$startDate, $today->toDateString()])
            ->get();

        // Gom task theo ngày
        $completedByDate = [];
        foreach ($completedTasks as $task) {
            $updated                     = \Carbon\Carbon::parse($task->updated_at)->toDateString();
            $completedByDate[$updated][] = $task;
        }

        // Tìm số lượng lớn nhất trong 7 ngày để chuẩn hóa %
        $maxCompleted = 0;
        foreach ($completedByDate as $date => $tasks) {
            $maxCompleted = max($maxCompleted, count($tasks));
        }
        if ($maxCompleted === 0) {
            $maxCompleted = 1; // tránh chia cho 0
        }

        // Tên ngày tiếng Việt
        $dayNames = [
            0 => 'Chủ nhật',
            1 => 'Thứ 2',
            2 => 'Thứ 3',
            3 => 'Thứ 4',
            4 => 'Thứ 5',
            5 => 'Thứ 6',
            6 => 'Thứ 7',
        ];

        // Build dữ liệu 7 ngày gần nhất
        for ($i = $days - 1; $i >= 0; $i--) {
            $date       = $today->copy()->subDays($i);
            $dateString = $date->toDateString();
            $isToday    = $i === 0;

            $doneTasks  = isset($completedByDate[$dateString]) ? count($completedByDate[$dateString]) : 0;
            $percentage = round(($doneTasks / $maxCompleted) * 100);

            // Xác định màu sắc
            if ($percentage >= 70) {
                $progressColor = 'bg-green-500';
                $textColor     = 'text-green-600';
            } elseif ($percentage >= 50) {
                $progressColor = 'bg-amber-500';
                $textColor     = 'text-amber-600';
            } elseif ($percentage > 0) {
                $progressColor = 'bg-red-500';
                $textColor     = 'text-red-600';
            } else {
                $progressColor = 'bg-gray-300';
                $textColor     = 'text-gray-600';
            }

            $dayName = $isToday ? 'Hôm nay' : $dayNames[$date->dayOfWeek];

            $productivity[] = [
                'date'           => $dateString,
                'day_name'       => $dayName,
                'completed'      => $doneTasks,
                'percentage'     => $percentage,
                'progress_color' => $progressColor,
                'text_color'     => $textColor,
                'is_today'       => $isToday,
            ];
        }

        return $productivity;
    }

    /**
     * Lấy danh sách công việc cần ưu tiên
     */
    private function getUrgentTasks($userId)
    {
        $tasks = DB::table('tasks')
            ->where('user_id', $userId)
            ->where('is_completed', 0)
            ->whereNotNull('task_deadline')
            ->orderBy(DB::raw('
                CASE
                    WHEN task_deadline < CURDATE() THEN 1
                    WHEN task_deadline = CURDATE() THEN 2
                    WHEN task_deadline = DATE_ADD(CURDATE(), INTERVAL 1 DAY) THEN 3
                    ELSE 4
                END
            '))
            ->orderBy('priority', 'desc')
            ->limit(5)
            ->get();

        $urgentTasks = [];
        $today       = now()->toDateString();
        $tomorrow    = now()->addDay()->toDateString();

        foreach ($tasks as $task) {
            $deadline   = Carbon::parse($task->task_deadline);
            $isOverdue  = $deadline->toDateString() < $today;
            $isToday    = $deadline->toDateString() === $today;
            $isTomorrow = $deadline->toDateString() === $tomorrow;

            // Xác định text và class dựa trên thời hạn
            if ($isOverdue) {
                $deadlineText = 'Quá hạn ' . $deadline->diffForHumans();
                $urgencyClass = 'bg-red-50 border-red-200';
                $iconColor    = 'text-red-500';
                $icon         = 'warning';
            } elseif ($isToday) {
                $deadlineText = 'Hôm nay';
                $urgencyClass = 'bg-red-50 border-red-200';
                $iconColor    = 'text-red-500';
                $icon         = 'warning';
            } elseif ($isTomorrow) {
                $deadlineText = 'Ngày mai';
                $urgencyClass = 'bg-amber-50 border-amber-200';
                $iconColor    = 'text-amber-500';
                $icon         = 'schedule';
            } else {
                $daysUntil    = $deadline->diffInDays(now());
                $deadlineText = $daysUntil . ' ngày nữa';
                $urgencyClass = 'bg-blue-50 border-blue-200';
                $iconColor    = 'text-blue-500';
                $icon         = 'info';
            }

            // Xác định class và text của priority
            $priorityClasses = [
                'high'   => 'bg-red-100 text-red-800',
                'medium' => 'bg-amber-100 text-amber-800',
                'low'    => 'bg-green-100 text-green-800',
            ];

            $priorityTexts = [
                'high'   => 'Cao',
                'medium' => 'Trung bình',
                'low'    => 'Thấp',
            ];

            $urgentTasks[] = [
                'task_id'          => $task->task_id,
                'task_title'       => $task->task_title,
                'task_description' => $task->task_description,
                'priority'         => $task->priority,
                'task_deadline'    => $task->task_deadline,
                'deadline_text'    => $deadlineText,
                'urgency_class'    => $urgencyClass,
                'icon_color'       => $iconColor,
                'icon'             => $icon,
                'priority_class'   => $priorityClasses[$task->priority] ?? 'bg-gray-100 text-gray-800',
                'priority_text'    => $priorityTexts[$task->priority] ?? 'Không xác định',
                'is_overdue'       => $isOverdue,
                'is_today'         => $isToday,
                'is_tomorrow'      => $isTomorrow,
            ];
        }

        return $urgentTasks;
    }
}