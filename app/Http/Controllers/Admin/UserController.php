<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Danh sách users
    public function index(Request $request)
    {
        $query = User::where('role', 'user')->withCount('todos');

        // Tìm kiếm
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    // Chi tiết user + todos của họ
    public function show(User $user)
    {
        $todos = $user->todos()->latest()->paginate(10);
        $stats = [
            'total'     => $user->todos()->count(),
            'completed' => $user->todos()->where('is_completed', true)->count(),
            'pending'   => $user->todos()->where('is_completed', false)->count(),
        ];
        return view('admin.users.show', compact('user', 'todos', 'stats'));
    }

    // Khóa / Mở khóa tài khoản
    public function toggleActive(User $user)
    {
        // Không cho khóa chính mình
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Không thể khóa tài khoản của chính bạn.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $message = $user->is_active ? 'Đã mở khóa tài khoản.' : 'Đã khóa tài khoản.';
        return back()->with('success', $message);
    }

    // Đổi role
    public function changeRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|in:admin,user']);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Không thể đổi role của chính bạn.');
        }

        $user->update(['role' => $request->role]);
        return back()->with('success', 'Đã cập nhật role thành công.');
    }

    // Xóa user
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Không thể xóa tài khoản của chính bạn.');
        }

        $user->delete(); // Cascade tự xóa todos theo
        return redirect()->route('admin.users.index')->with('success', 'Đã xóa người dùng.');
    }
}