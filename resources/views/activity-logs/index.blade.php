@extends('layouts.app')
@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Log Aktivitas</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Rekaman semua aktivitas pengguna di sistem</p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 p-4 mb-5">
        <form method="GET" action="{{ route('activity-logs.index') }}" class="flex flex-wrap gap-2">
            <div class="relative flex-1 min-w-40">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari deskripsi..."
                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700/50 dark:text-white focus:outline-none focus:border-emerald-400 focus:ring-2 focus:ring-emerald-400/20">
            </div>
            <select name="action" class="form-select text-sm">
                <option value="">Semua Aksi</option>
                @foreach($actions as $act)
                    <option value="{{ $act }}" {{ $action === $act ? 'selected' : '' }}>{{ ucfirst($act) }}</option>
                @endforeach
            </select>
            <select name="user_id" class="form-select text-sm">
                <option value="">Semua User</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ $userId == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
            <input type="date" name="date" value="{{ $date }}" class="form-select text-sm">
            <button type="submit" class="btn-primary"><i class="fas fa-filter"></i> Filter</button>
            <a href="{{ route('activity-logs.index') }}" class="btn-secondary"><i class="fas fa-times"></i></a>
        </form>
    </div>

    {{-- Log Table --}}
    <div
        class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80 dark:bg-gray-700/30">
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Waktu</th>
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Pengguna</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Aksi</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Model</th>
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Deskripsi</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                    @forelse($logs as $log)
                        @php
                            $actionBadge = match ($log->action) {
                                'created' => 'badge-green',
                                'updated' => 'badge-blue',
                                'deleted' => 'badge-red',
                                'login' => 'badge-purple',
                                'logout' => 'badge-gray',
                                default => 'badge-gray'
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/20 transition-colors">
                            <td class="px-5 py-3.5 text-xs text-gray-500 whitespace-nowrap">
                                <p class="font-medium text-gray-700 dark:text-gray-300">{{ $log->created_at->format('d/m/Y') }}
                                </p>
                                <p class="font-mono text-gray-400">{{ $log->created_at->format('H:i:s') }}</p>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                                        style="background: linear-gradient(135deg, #0d9373, #6366f1);">
                                        {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 dark:text-white text-xs">
                                            {{ $log->user->name ?? 'System' }}</p>
                                        <p class="text-xs text-gray-400">{{ $log->user->role ?? '—' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="badge {{ $actionBadge }}">{{ $log->action_label }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <code class="badge badge-gray font-mono text-xs">{{ $log->model_name }}</code>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-gray-600 dark:text-gray-400 max-w-xs">{{ $log->description }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <code class="text-xs text-gray-400 font-mono">{{ $log->ip_address }}</code>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-gray-400">
                                <i class="fas fa-history text-3xl mb-2 block opacity-20"></i>
                                <p class="text-sm">Belum ada log aktivitas</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
            <div class="px-5 py-3.5 border-t border-gray-100 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-700/10">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
@endsection