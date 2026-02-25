<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Todo;

class DashboardController extends Controller
{
    public function index() {
        $stats = [
            'total_users'          => User::where('role', 'user')->count(),
            'active_users'         => User::where('role', 'user')->where('is_active', true)->count(),
            'locked_users'         => User::where('role', 'user')->where('is_active', false)->count(),
            'total_todos'          => Todo::count(),
            'completed_todos'      => Todo::where('is_completed', true)->count(),
            'pending_todos'        => Todo::where('is_completed', false)->count(),
            'new_users_this_month' => User::where('role', 'user')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];
                // Top 5 user có nhiều todo nhất
        $topUsers = User::where('role', 'user')
            ->withCount('todos')
            ->orderByDesc('todos_count')
            ->limit(5)
            ->get();

        // 10 todo mới nhất
        $recentTodos = Todo::with('user')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'topUsers', 'recentTodos'));
    }
}
