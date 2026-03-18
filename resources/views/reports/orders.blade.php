<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pesanan - Arneta POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .dark .glass {
            background: rgba(31, 41, 55, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .gradient-text {
            background: linear-gradient(135deg, #0d9373 0%, #065f46 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight gradient-text">Laporan Pesanan</h1>
                <p class="text-gray-500 text-sm mt-1 font-medium">Pantau performa penjualan dan tren transaksi Anda.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button onclick="window.open('{{ route('reports.orders.print', request()->query()) }}', '_blank')" class="flex items-center space-x-2 bg-white px-5 py-2.5 rounded-xl border border-gray-200 shadow-sm hover:bg-gray-50 transition-all font-semibold text-gray-700">
                    <i class="fas fa-print text-emerald-500"></i>
                    <span>Cetak Laporan</span>
                </button>
                <button id="exportBtn" class="flex items-center space-x-2 bg-emerald-600 px-5 py-2.5 rounded-xl text-white shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all font-semibold">
                    <i class="fas fa-file-export"></i>
                    <span>Export Data</span>
                </button>
            </div>
        </div>

        {{-- Filter Section --}}
        <div class="mb-10">
            @include('reports._filter')
        </div>

        {{-- KPI Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            {{-- Total Orders --}}
            <div class="glass p-6 rounded-2xl shadow-sm hover:shadow-md transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i class="fas fa-shopping-bag text-lg"></i>
                    </div>
                </div>
                <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Total Pesanan</h3>
                <div class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ number_format($totalOrders) }}</div>
                <div class="mt-2 text-xs font-semibold text-blue-600 bg-blue-50 inline-block px-2 py-1 rounded-md">
                    {{ number_format($completedOrders) }} Selesai
                </div>
            </div>

            {{-- Total Revenue --}}
            <div class="glass p-6 rounded-2xl shadow-sm hover:shadow-md transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        <i class="fas fa-money-bill-wave text-lg"></i>
                    </div>
                </div>
                <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Total Omzet (Net)</h3>
                <div class="text-3xl font-extrabold text-gray-900 tracking-tight">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                <div class="mt-2 text-xs font-semibold text-emerald-600 bg-emerald-50 inline-block px-2 py-1 rounded-md">
                    Setelah Diskon
                </div>
            </div>

            {{-- Avg Order Value --}}
            <div class="glass p-6 rounded-2xl shadow-sm hover:shadow-md transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="fas fa-chart-line text-lg"></i>
                    </div>
                </div>
                <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Rata-rata Pesanan</h3>
                <div class="text-3xl font-extrabold text-gray-900 tracking-tight">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</div>
                <div class="mt-2 text-xs font-semibold text-indigo-600 bg-indigo-50 inline-block px-2 py-1 rounded-md">
                    Per Transaksi
                </div>
            </div>

            {{-- Total Discounts --}}
            <div class="glass p-6 rounded-2xl shadow-sm hover:shadow-md transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-rose-50 text-rose-600 rounded-xl group-hover:bg-rose-600 group-hover:text-white transition-colors">
                        <i class="fas fa-percentage text-lg"></i>
                    </div>
                </div>
                <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-1">Total Diskon</h3>
                <div class="text-3xl font-extrabold text-gray-900 tracking-tight">Rp {{ number_format($totalDiscount, 0, ',', '.') }}</div>
                <div class="mt-2 text-xs font-semibold text-rose-600 bg-rose-50 inline-block px-2 py-1 rounded-md">
                    Promo Diberikan
                </div>
            </div>
        </div>

        {{-- Charts Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
            {{-- Sales Trend Chart --}}
            <div class="lg:col-span-2 glass p-6 rounded-3xl shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-gray-800">Tren Penjualan Harian</h3>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">30 Hari Terakhir</span>
                </div>
                <div style="height: 300px;">
                    <canvas id="salesTrendChart"></canvas>
                </div>
            </div>

            {{-- Sidebar Column --}}
            <div class="space-y-6">
                {{-- Payment Methods Chart --}}
                <div class="glass p-6 rounded-3xl shadow-sm">
                    <h3 class="font-bold text-gray-800 mb-6">Metode Pembayaran</h3>
                    <div style="height: 200px;">
                        <canvas id="paymentMethodsChart"></canvas>
                    </div>
                </div>

                {{-- Top Products --}}
                <div class="glass p-6 rounded-3xl shadow-sm divide-y divide-gray-100 dark:divide-gray-700">
                    <h3 class="font-bold text-gray-800 mb-4">5 Produk Terlaris</h3>
                    @foreach ($topProducts as $item)
                    <div class="flex items-center py-4 @if ($loop->first) pt-0 @endif @if ($loop->last) pb-0 @endif">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-500 mr-3">
                            <i class="fas fa-box text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-bold text-gray-800 truncate">{{ $item->product->name }}</div>
                            <div class="text-[10px] font-bold text-gray-400 uppercase">{{ $item->total_qty }} Unit Terjual</div>
                        </div>
                        <div class="text-xs font-extrabold text-emerald-600">
                            Rp {{ number_format($item->total_sales, 0, ',', '.') }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Transactions Table --}}
        <div class="glass rounded-3xl shadow-sm overflow-hidden mb-10">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-white/50">
                <h3 class="font-bold text-gray-800">Detail Transaksi</h3>
                <span class="text-xs font-bold text-gray-400">{{ $totalOrders }} Pesanan Ditemukan</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50/50 text-gray-500 text-[10px] uppercase font-bold tracking-widest">
                        <tr>
                            <th class="px-6 py-4 text-left">No. Invoice</th>
                            <th class="px-6 py-4 text-left">Tanggal</th>
                            <th class="px-6 py-4 text-left">Pelanggan</th>
                            <th class="px-6 py-4 text-center">Metode</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Total Akhir</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($orders as $order)
                        <tr class="hover:bg-white transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono font-bold text-blue-600">#{{ $order->invoice_number }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                {{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-gray-800">{{ $order->customer->name ?? 'Umum' }}</div>
                                <div class="text-[10px] text-gray-400">{{ $order->customer->phone ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-xs font-semibold text-gray-600 px-2 py-1 bg-gray-100 rounded-md">
                                    {{ $order->paymentMethod->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $statusConfig = [
                                        'pending' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-600', 'border' => 'border-yellow-100'],
                                        'processing' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-100'],
                                        'completed' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-100'],
                                        'cancelled' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-600', 'border' => 'border-rose-100'],
                                    ];
                                    $sc = $statusConfig[$order->status] ?? ['bg' => 'bg-gray-50', 'text' => 'text-gray-600', 'border' => 'border-gray-100'];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold {{ $sc['bg'] }} {{ $sc['text'] }} border {{ $sc['border'] }} uppercase tracking-wider">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-black text-gray-900">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <a href="{{ route('orders.show', $order->id) }}" class="w-8 h-8 inline-flex items-center justify-center bg-white border border-gray-200 rounded-lg text-gray-400 hover:text-emerald-600 hover:border-emerald-600 transition-all">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-search-minus text-3xl text-gray-200"></i>
                                    </div>
                                    <h4 class="font-bold text-gray-800">Tidak ada data pesanan</h4>
                                    <p class="text-gray-400 text-xs">Coba ubah filter atau periode tanggal.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Export Modal --}}
    <div id="exportModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white rounded-3xl shadow-2xl p-8 w-full max-w-md scale-95 animate-in zoom-in duration-200">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-gray-800">Export Laporan</h3>
                <button onclick="closeExportModal()" class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('reports.export-csv') }}" method="GET" id="exportForm">
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">
                
                <div class="space-y-4 mb-8">
                    <div class="p-4 rounded-2xl border-2 border-emerald-500 bg-emerald-50/50 flex items-center">
                        <div class="w-10 h-10 bg-emerald-600 text-white rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-file-csv"></i>
                        </div>
                        <div>
                            <div class="font-bold text-gray-800 text-sm">Format CSV / Excel</div>
                            <p class="text-[10px] text-emerald-700">Terbaik untuk analisis data mandiri.</p>
                        </div>
                    </div>
                    <div class="p-4 rounded-2xl border border-gray-100 opacity-50 cursor-not-allowed grayscale flex items-center">
                        <div class="w-10 h-10 bg-gray-200 text-gray-500 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div>
                            <div class="font-bold text-gray-800 text-sm">Format PDF</div>
                            <p class="text-[10px] text-gray-400">Gunakan 'Cetak Laporan' untuk PDF formal.</p>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-emerald-600 py-4 rounded-2xl text-white font-bold shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all flex items-center justify-center space-x-2">
                    <i class="fas fa-download"></i>
                    <span>Export Sekarang</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        // Charts Initialization
        document.addEventListener('DOMContentLoaded', function() {
            // Sales Trend Chart
            const salesCtx = document.getElementById('salesTrendChart').getContext('2d');
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartData['labels']) !!},
                    datasets: [{
                        label: 'Penjualan',
                        data: {!! json_encode($chartData['sales']) !!},
                        borderColor: '#0d9373',
                        backgroundColor: (context) => {
                            const gradient = context.chart.ctx.createLinearGradient(0, 0, 0, 300);
                            gradient.addColorStop(0, 'rgba(13, 147, 115, 0.2)');
                            gradient.addColorStop(1, 'rgba(13, 147, 115, 0)');
                            return gradient;
                        },
                        borderWidth: 4,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#0d9373',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: { display: true, grid: { display: false } },
                        y: { 
                            beginAtZero: true,
                            grid: { borderDash: [5, 5], drawBorder: false },
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000) + 'k';
                                }
                            }
                        }
                    }
                }
            });

            // Payment Methods Chart
            const paymentCtx = document.getElementById('paymentMethodsChart').getContext('2d');
            const paymentData = {!! json_encode($paymentMethodData) !!};
            new Chart(paymentCtx, {
                type: 'doughnut',
                data: {
                    labels: paymentData.map(d => d.name),
                    datasets: [{
                        data: paymentData.map(d => d.total),
                        backgroundColor: ['#0d9373', '#4f46e5', '#f59e0b', '#ef4444', '#8b5cf6'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 10, padding: 15, font: { size: 10, weight: 'bold' } } }
                    }
                }
            });
        });

        // UI Interactions
        document.getElementById('exportBtn').addEventListener('click', () => {
            document.getElementById('exportModal').classList.remove('hidden');
        });

        function closeExportModal() {
            document.getElementById('exportModal').classList.add('hidden');
        }
    </script>
</body>
</html>