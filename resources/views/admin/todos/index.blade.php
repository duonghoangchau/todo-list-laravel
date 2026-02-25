@extends('admin.layouts.app')
@section('title', 'Quản lý công việc')
@section('page-title', 'Quản lý công việc')

@section('content')

{{-- Filter --}}
<form method="GET" action="{{ route('admin.todos.index') }}"
      class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 mb-6 flex flex-wrap gap-3 items-end">
    <div class="flex-1 min-w-44">
        <label class="text-xs font-medium text-gray-500 mb-1 block">Tìm tiêu đề</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nhập tiêu đề..."
            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
    </div>
    <div>
        <label class="text-xs font-medium text-gray-500 mb-1 block">Người dùng</label>
        <select name="user_id" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">Tất cả</option>
            @foreach($users as $u)
                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="text-xs font-medium text-gray-500 mb-1 block">Trạng thái</label>
        <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">Tất cả</option>
            <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Chưa xong</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
        </select>
    </div>
    <div>
        <label class="text-xs font-medium text-gray-500 mb-1 block">Ưu tiên</label>
        <select name="priority" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">Tất cả</option>
            <option value="high"   {{ request('priority') === 'high'   ? 'selected' : '' }}>Cao</option>
            <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Trung bình</option>
            <option value="low"    {{ request('priority') === 'low'    ? 'selected' : '' }}>Thấp</option>
        </select>
    </div>
    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition">Lọc</button>
    @if(request()->hasAny(['search','user_id','status','priority']))
        <a href="{{ route('admin.todos.index') }}" class="border border-gray-200 text-gray-500 hover:bg-gray-50 px-5 py-2.5 rounded-xl text-sm font-medium transition">Xóa lọc</a>
    @endif
</form>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <span class="text-sm text-gray-500">Tổng: <strong class="text-gray-800">{{ $todos->total() }}</strong> công việc</span>
    </div>
    <table class="w-full">
        <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wide">
            <tr>
                <th class="px-6 py-4 text-left">Công việc</th>
                <th class="px-6 py-4 text-left">Người dùng</th>
                <th class="px-6 py-4 text-left">Ưu tiên</th>
                <th class="px-6 py-4 text-left">Trạng thái</th>
                <th class="px-6 py-4 text-left">Hạn</th>
                <th class="px-6 py-4 text-left">Ngày tạo</th>
                <th class="px-6 py-4 text-center">Xóa</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($todos as $todo)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-800 text-sm {{ $todo->is_completed ? 'line-through text-gray-400' : '' }}">{{ $todo->title }}</p>
                        @if($todo->description)
                            <p class="text-gray-400 text-xs truncate max-w-xs mt-0.5">{{ $todo->description }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.users.show', $todo->user) }}" class="text-indigo-600 hover:underline text-sm">
                            {{ $todo->user->name }}
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        @php $pc = match($todo->priority) { 'high' => 'bg-red-100 text-red-600', 'medium' => 'bg-orange-100 text-orange-600', default => 'bg-green-100 text-green-600' }; $pl = match($todo->priority) { 'high' => 'Cao', 'medium' => 'Trung bình', default => 'Thấp' }; @endphp
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $pc }}">{{ $pl }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($todo->is_completed)
                            <span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">Hoàn thành</span>
                        @else
                            <span class="bg-orange-100 text-orange-600 text-xs font-semibold px-2.5 py-1 rounded-full">Đang làm</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-sm">
                        {{ $todo->due_date ? $todo->due_date->format('d/m/Y') : '—' }}
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-sm">{{ $todo->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-center">
                        <form action="{{ route('admin.todos.destroy', $todo) }}" method="POST">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Xóa công việc này?')"
                                class="p-1.5 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400">Không có công việc nào</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($todos->hasPages())
        <div class="px-6 py-4 border-t">{{ $todos->links() }}</div>
    @endif
</div>

@endsection