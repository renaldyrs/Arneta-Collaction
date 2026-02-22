@extends('layouts.app')
@section('content')

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Shift</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola shift kasir dan rekonsiliasi kas</p>
        </div>
        @if(!$activeShift)
            <button onclick="document.getElementById('openShiftModal').classList.remove('hidden')"
                class="btn-primary bg-emerald-500 hover:bg-emerald-600">
                <i class="fas fa-play-circle"></i> Buka Shift
            </button>
        @else
            <div class="flex items-center gap-3">
                <div class="text-right">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <p class="text-sm font-bold text-emerald-600 dark:text-emerald-400">Shift Aktif:
                            {{ $activeShift->shift_number }}</p>
                    </div>
                    <p class="text-xs text-gray-400">Dibuka:
                        {{ $activeShift->opened_at ? $activeShift->opened_at->format('H:i') : '-' }}</p>
                </div>
                <a href="{{ route('shifts.show', $activeShift) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white transition-all"
                    style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                    <i class="fas fa-stop-circle"></i> Tutup Shift
                </a>
            </div>
        @endif
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Shift Hari Ini</p>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(99,102,241,0.12);">
                    <i class="fas fa-business-time text-indigo-500 text-sm"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalShifts }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Shift Aktif</p>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(16,185,129,0.12);">
                    <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $openShifts }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800/80 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Revenue Hari Ini</p>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(245,158,11,0.12);">
                    <i class="fas fa-coins text-amber-500 text-sm"></i>
                </div>
            </div>
            <p class="text-xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </p>
        </div>
    </div>

    {{-- Shift Table --}}
    <div
        class="bg-white dark:bg-gray-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/50">
            <h3 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-history text-emerald-500"></i> Riwayat Shift
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80 dark:bg-gray-700/30">
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            No. Shift</th>
                        <th
                            class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Kasir</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Status</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Buka</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Tutup</th>
                        <th
                            class="px-5 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Modal</th>
                        <th
                            class="px-5 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Revenue</th>
                        <th
                            class="px-5 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Selisih</th>
                        <th
                            class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                    @forelse($shifts as $shift)
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/20 transition-colors">
                            <td class="px-5 py-3.5">
                                <code
                                    class="text-xs font-mono font-bold text-emerald-600 dark:text-emerald-400">{{ $shift->shift_number }}</code>
                            </td>
                            <td class="px-5 py-3.5 font-medium text-gray-800 dark:text-white">{{ $shift->user->name }}</td>
                            <td class="px-5 py-3.5 text-center">
                                @if($shift->status === 'open')
                                    <span class="badge badge-green"><span
                                            class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse inline-block mr-1"></span>
                                        Aktif</span>
                                @else
                                    <span class="badge badge-gray">Selesai</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center text-xs text-gray-500 font-mono">
                                {{ $shift->opened_at ? $shift->opened_at->format('H:i') : '—' }}</td>
                            <td class="px-5 py-3.5 text-center text-xs text-gray-500 font-mono">
                                {{ $shift->closed_at ? $shift->closed_at->format('H:i') : '—' }}</td>
                            <td class="px-5 py-3.5 text-right text-xs text-gray-700 dark:text-gray-300">Rp
                                {{ number_format($shift->opening_cash, 0, ',', '.') }}</td>
                            <td class="px-5 py-3.5 text-right font-bold text-emerald-600 dark:text-emerald-400">Rp
                                {{ number_format($shift->total_revenue, 0, ',', '.') }}</td>
                            <td class="px-5 py-3.5 text-right">
                                @if($shift->status === 'closed')
                                    <span
                                        class="font-bold {{ $shift->cash_difference >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                        {{ $shift->cash_difference >= 0 ? '+' : '' }}Rp
                                        {{ number_format($shift->cash_difference, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <a href="{{ route('shifts.show', $shift) }}"
                                    class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-10 text-center text-gray-400">
                                <i class="fas fa-business-time text-3xl mb-2 block opacity-20"></i>
                                <p class="text-sm">Belum ada riwayat shift</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($shifts->hasPages())
            <div class="px-5 py-3.5 border-t border-gray-100 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-700/10">
                {{ $shifts->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Buka Shift --}}
    <div id="openShiftModal"
        class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Buka Shift Baru</h3>
                <button onclick="document.getElementById('openShiftModal').classList.add('hidden')"
                    class="w-8 h-8 flex items-center justify-center rounded-xl text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            <form action="{{ route('shifts.open') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label
                        class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Modal
                        Awal Kas *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-500 font-semibold">Rp</span>
                        <input type="number" name="opening_cash" required min="0" step="1000" class="form-input pl-9">
                    </div>
                </div>
                <div>
                    <label
                        class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Catatan</label>
                    <textarea name="opening_notes" rows="2" class="form-input resize-none"
                        placeholder="Catatan pembukaan shift..."></textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-primary flex-1 justify-center"
                        style="background: linear-gradient(135deg, #10b981, #059669);">
                        <i class="fas fa-play-circle"></i> Buka Shift
                    </button>
                    <button type="button" onclick="document.getElementById('openShiftModal').classList.add('hidden')"
                        class="btn-secondary px-4">Batal</button>
                </div>
            </form>
        </div>
    </div>
@endsection