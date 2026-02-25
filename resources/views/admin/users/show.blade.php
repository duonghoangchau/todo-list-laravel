@extends('admin.layouts.app')
@section('title', 'Chi tiết: ' . $user->name)
@section('page-title', 'Chi tiết người dùng')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Thông tin user --}}
    <div class="lg:col-span-1 space-y-5">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="text-center mb-5">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-indigo-600 font-bold text-2xl">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
                <h2 class="font-bold text-gray-800 text-lg">{{ $user->name }}</h2>
                <p class="text-gray-400 text-sm">{{ $user->email }}</p>
                <div class="flex items-center justify-center gap-2 mt-2">
                    <span class="text-xs bg-indigo-100 text-indigo-600 px-2.5 py-1 rounded-full font-medium">{{ ucfirst($user->role) }}</span>
                    @if($user->is_active)
                        <span class="text-xs bg-green-100 text-green-600 px-2.5 py-1 rounded-full font-medium">Đang hoạt động</span>
                    @else
                        <span class="text-xs bg-red-100 text-red-600 px-2.5 py-1 rounded-full font-medium">Đã khóa</span>
                    @endif
                </div>
            </div>

            <div class="border-t pt-4 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Ngày tham gia</span>
                    <span class="font-medium">{{ $user->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        {{-- Thống kê --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4">Thống kê công việc</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 text-sm">Tổng</span>
                    <span class="font-bold text-gray-800">{{ $stats['total'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 text-sm">Hoàn thành</span>
                    <span class="font-bold text-green-600">{{ $stats['completed'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 text-sm">Đang làm</span>
                    <span class="font-bold text-orange-500">{{ $stats['pending'] }}</span>
                </div>
                @if($stats['total'] > 0)
                    <div class="pt-2">
                        <div class="flex justify-between text-xs text-gray-400 mb-1">
                            <span>Tiến độ</span>
                            <span>{{ round($stats['completed'] / $stats['total'] * 100) }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-indigo-500 h-2 rounded-full"
                                 style="width: {{ round($stats['completed'] / $stats['total'] * 100) }}%"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Hành động --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 space-y-3">
            <h3 class="font-bold text-gray-800 mb-2">Thao tác</h3>
            <form action="{{ route('admin.users.toggle', $user) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit"
                    onclick="return confirm('{{ $user->is_active ? 'Khóa tài khoản này?' : 'Mở khóa tài khoản này?' }}')"
                    class="w-full py-2.5 rounded-xl text-sm font-medium transition
                           {{ $user->is_active ? 'bg-orange-50 text-orange-600 hover:bg-orange-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }}">
                    {{ $user->is_active ? '🔒 Khóa tài khoản' : '🔓 Mở khóa tài khoản' }}
                </button>
            </form>
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit"
                    onclick="return confirm('Xóa user này? Tất cả todo của họ cũng bị xóa!')"
                    class="w-full py-2.5 rounded-xl text-sm font-medium bg-red-50 text-red-600 hover:bg-red-100 transition">
                    🗑 Xóa người dùng
                </button>
            </form>
        </div>
    </div>

    {{-- Danh sách todos --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-800">Danh sách công việc</h3>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($todos as $todo)
                    <div class="px-6 py-4 flex items-start gap-4">
                        <span class="w-2.5 h-2.5 rounded-full mt-1.5 flex-shrink-0 {{ $todo->is_completed ? 'bg-green-400' : 'bg-orange-400' }}"></span>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-800 text-sm {{ $todo->is_completed ? 'line-through text-gray-400' : '' }}">
                                {{ $todo->title }}
                            </p>
                            @if($todo->description)
                                <p class="text-gray-400 text-xs mt-0.5 truncate">{{ $todo->description }}</p>
                            @endif
                            <div class="flex items-center gap-3 mt-1.5">
                                @php
                                    $pc = match($todo->priority) { 'high' => 'bg-red-100 text-red-600', 'medium' => 'bg-orange-100 text-orange-600', default => 'bg-green-100 text-green-600' };
                                    $pl = match($todo->priority) { 'high' => 'Cao', 'medium' => 'Trung bình', default => 'Thấp' };
                                @endphp
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $pc }}">{{ $pl }}</span>
                                @if($todo->due_date)
                                    <span class="text-xs text-gray-400">📅 {{ $todo->due_date->format('d/m/Y') }}</span>
                                @endif
                                <span class="text-xs text-gray-300">{{ $todo->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <form action="{{ route('admin.todos.destroy', $todo) }}" method="POST" class="flex-shrink-0">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Xóa công việc này?')"
                                class="p-1.5 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center text-gray-400">Chưa có công việc nào</div>
                @endforelse
            </div>
            @if($todos->hasPages())
                <div class="px-6 py-4 border-t">{{ $todos->links() }}</div>
            @endif
        </div>
    </div>

</div>

@endsection