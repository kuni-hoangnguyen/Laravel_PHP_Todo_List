<?php
namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    // Dashboard
    public function index()
    {
        $user_name  = Session::get('user_name');
        $user_email = Session::get('user_email');
        $user_id    = Session::get('user_id');

        $tasks = DB::table('tasks')
            ->where('user_id', $user_id)
            ->orderBy('task_deadline', 'asc')
            ->get();

        // Task ưu tiên hôm nay
        $todayTasks = DB::table('tasks')
            ->where('user_id', $user_id)
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

        // Tính tiến độ
        $totalTasks     = DB::table('tasks')->where('user_id', $user_id)->count();
        $completedTasks = DB::table('tasks')->where('user_id', $user_id)->where('is_completed', 1)->count();
        $progress       = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        return view('pages.dashboard', compact(
            'user_name',
            'user_email',
            'user_id',
            'tasks',
            'todayTasks',
            'progress'
        ));
    }

    // CREATE
    public function store(Request $request)
    {
        $request->validate([
            'task_title'       => 'required|max:50',
            'task_description' => 'nullable|max:255',
            'task_deadline'    => 'nullable|date',
            'priority'         => 'required|in:low,medium,high',
        ]);

        $task = Task::create([
            'user_id'          => Session::get('user_id'),
            'task_title'       => $request->task_title,
            'task_description' => $request->task_description,
            'task_deadline'    => $request->task_deadline,
            'priority'         => $request->priority,
            'is_completed'     => false,
        ]);

        return response()->json($task);
    }

    // UPDATE
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'task_title'       => 'required|max:50',
            'task_description' => 'nullable|max:255',
            'task_deadline'    => 'nullable|date',
            'priority'         => 'required|in:low,medium,high',
            'is_completed'     => 'boolean',
        ]);

        // chỉ cho update task thuộc user đang login
        if ($task->user_id !== Session::get('user_id')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $task->update($request->all());
        return response()->json($task);
    }

    // DELETE
    public function destroy(Task $task)
    {
        if ($task->user_id !== Session::get('user_id')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $task->delete();
        return response()->json(['success' => true]);

    }
    public function toggleComplete(Request $request, $id)
    {
        $task               = Task::findOrFail($id);
        $task->is_completed = $request->is_completed;
        $task->save();

        return response()->json(['success' => true]);
    }

}