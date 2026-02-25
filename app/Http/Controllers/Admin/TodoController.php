<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        $query = Todo::with('user');

        // Tìm kiếm theo tiêu đề
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Lọc theo user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('is_completed', $request->status === 'completed');
        }

        // Lọc theo priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $todos = $query->latest()->paginate(15)->withQueryString();
        $users = User::where('role', 'user')->orderBy('name')->get();

        return view('admin.todos.index', compact('todos', 'users'));
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();
        return back()->with('success', 'Đã xóa công việc.');
    }
}