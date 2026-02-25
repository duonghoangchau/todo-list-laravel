@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Stats cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <span class="text-gray-500 text-sm font-medium">Tổng người dùng</span>
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $stats['total_users'] }}</p>
        <p class="text-xs text-green-500 mt-1">+{{ $stats['new_users_this_month'] }} tháng này</p>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <span class="text-gray-500 text-sm font-medium">Tổng công việc</span>
            <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $stats['total_todos'] }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $stats['completed_todos'] }} hoàn thành</p>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <span class="text-gray-500 text-sm font-medium">Đang hoạt động</span>
            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $stats['active_users'] }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $stats['locked_users'] }} bị khóa</p>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <span class="text-gray-500 text-sm font-medium">Chưa hoàn thành</span>
            <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $stats['pending_todos'] }}</p>
        <p class="text-xs text-gray-400 mt-1">công việc đang chờ</p>
    </div>

</div>

{{-- Bottom section --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Top Users --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-800">Top người dùng tích cực</h2>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($topUsers as $i => $user)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-600 text-sm font-bold flex items-center justify-center">
                            {{ $i + 1 }}
                        </span>
                        <div>
                            <p class="font-medium text-gray-800 text-sm">{{ $user->name }}</p>
                            <p class="text-gray-400 text-xs">{{ $user->email }}</p>
                        </div>
                    </div>
                    <span class="bg-indigo-50 text-indigo-600 text-xs font-semibold px-3 py-1 rounded-full">
                        {{ $user->todos_count }} việc
                    </span>
                </div>
            @empty
                <p class="text-gray-400 text-sm px-6 py-4">Chưa có dữ liệu</p>
            @endforelse
        </div>
    </div>

    {{-- Recent Todos --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-800">Công việc mới nhất</h2>
            <a href="{{ route('admin.todos.index') }}" class="text-indigo-600 text-sm hover:underline">Xem tất cả</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentTodos as $todo)
                <div class="px-6 py-3 flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $todo->is_completed ? 'bg-green-400' : 'bg-orange-400' }}"></span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-700 truncate">{{ $todo->title }}</p>
                        <p class="text-xs text-gray-400">{{ $todo->user->name }} · {{ $todo->created_at->diffForHumans() }}</p>
                    </div>
                    @php
                        $pc = match($todo->priority) { 'high' => 'bg-red-100 text-red-600', 'medium' => 'bg-orange-100 text-orange-600', default => 'bg-green-100 text-green-600' };
                        $pl = match($todo->priority) { 'high' => 'Cao', 'medium' => 'TB', default => 'Thấp' };
                    @endphp
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $pc }} flex-shrink-0">{{ $pl }}</span>
                </div>
            @empty
                <p class="text-gray-400 text-sm px-6 py-4">Chưa có công việc nào</p>
            @endforelse
        </div>
    </div>

</div>

@endsection