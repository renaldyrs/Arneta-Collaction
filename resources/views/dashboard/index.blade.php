@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold mb-8 dark:text-white">Dashboard</h1>

        <!-- Statistik Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Pendapatan -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden dark:bg-gray-800 transition-all hover:shadow-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300 mr-4">
                            <i class="fas fa-wallet text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pendapatan</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white">Rp {{ number_format($income, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm font-medium flex items-center @if($monthlyComparison['transaction_change'] >= 0) text-green-600 dark:text-green-400 @else text-red-600 dark:text-red-400 @endif">
                            @if($monthlyComparison['transaction_change'] >= 0)
                                <i class="fas fa-arrow-up mr-1"></i>
                            @else
                                <i class="fas fa-arrow-down mr-1"></i>
                            @endif
                            {{ abs(round($monthlyComparison['transaction_change'], 2)) }}% dari bulan sebelumnya
                        </span>
                    </div>
                </div>
            </div>

            <!-- Total Pengeluaran -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden dark:bg-gray-800 transition-all hover:shadow-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300 mr-4">
                            <i class="fas fa-shopping-cart text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pengeluaran</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white">Rp {{ number_format($expenses, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm font-medium flex items-center @if($monthlyComparison['expense_change'] >= 0) text-red-600 dark:text-red-400 @else text-green-600 dark:text-green-400 @endif">
                            @if($monthlyComparison['expense_change'] >= 0)
                                <i class="fas fa-arrow-up mr-1"></i>
                            @else
                                <i class="fas fa-arrow-down mr-1"></i>
                            @endif
                            {{ abs(round($monthlyComparison['expense_change'], 2)) }}% dari bulan sebelumnya
                        </span>
                    </div>
                </div>
            </div>

            <!-- Laba Bersih -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden dark:bg-gray-800 transition-all hover:shadow-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300 mr-4">
                            <i class="fas fa-chart-line text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Laba/Rugi</p>
                            <p class="text-2xl font-bold @if($profit >= 0) text-green-600 dark:text-green-400 @else text-red-600 dark:text-red-400 @endif">
                                Rp {{ number_format(abs($profit), 0, ',', '.') }}
                                <span class="text-sm">({{ $profit >= 0 ? 'Laba' : 'Rugi' }})</span>
                            </p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm font-medium text-blue-600 dark:text-blue-400">
                            @if($profitComparison >= 0)
                                <i class="fas fa-arrow-up mr-1"></i>
                            @else
                                <i class="fas fa-arrow-down mr-1"></i>
                            @endif
                            {{ abs(round($profitComparison, 2)) }}% dari bulan lalu
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Transaksi Hari Ini -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden dark:bg-gray-800 transition-all hover:shadow-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Transaksi Hari Ini</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $todayTransactions }}</p>
                        </div>
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600 dark:bg-purple-900 dark:text-purple-300">
                            <i class="fas fa-receipt text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Pendapatan Hari Ini -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden dark:bg-gray-800 transition-all hover:shadow-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pendapatan Hari Ini</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
                        </div>
                        <div class="p-3 rounded-full bg-amber-100 text-amber-600 dark:bg-amber-900 dark:text-amber-300">
                            <i class="fas fa-coins text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Produk Terlaris -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden dark:bg-gray-800 transition-all hover:shadow-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="truncate">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Produk Terlaris</p>
                            <p class="text-xl font-bold text-gray-800 dark:text-white truncate">
                                @if ($bestSellingProduct)
                                    {{ $bestSellingProduct->product->name }} 
                                    <span class="text-sm font-normal">({{ $bestSellingProduct->total_sold }} terjual)</span>
                                @else
                                    Tidak ada data
                                @endif
                            </p>
                        </div>
                        <div class="p-3 rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-900 dark:text-emerald-300">
                            <i class="fas fa-star text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Grafik Transaksi -->
            <div class="bg-white p-6 rounded-lg shadow-md dark:bg-gray-800 transition-all hover:shadow-lg">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Transaksi (7 Hari Terakhir)</h2>
                    <div class="p-2 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <div class="chart-container" style="position: relative; height:300px; width:100%">
                    <canvas id="transactionChart"></canvas>
                </div>
            </div>

            <!-- Grafik Pendapatan -->
            <div class="bg-white p-6 rounded-lg shadow-md dark:bg-gray-800 transition-all hover:shadow-lg">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Pendapatan (7 Hari Terakhir)</h2>
                    <div class="p-2 rounded-full bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                </div>
                <div class="chart-container" style="position: relative; height:300px; width:100%">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Chart colors for dark/light mode
            const bgColor = window.matchMedia('(prefers-color-scheme: dark)').matches ? 
                'rgba(31, 41, 55, 0.9)' : 'rgba(255, 255, 255, 0.9)';
            const textColor = window.matchMedia('(prefers-color-scheme: dark)').matches ? 
                '#ffffff' : '#374151';
            const gridColor = window.matchMedia('(prefers-color-scheme: dark)').matches ? 
                'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';

            // Transaction Chart
            const transactionCtx = document.getElementById('transactionChart').getContext('2d');
            const transactionChart = new Chart(transactionCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($transactionChartData->pluck('date')) !!},
                    datasets: [{
                        label: 'Jumlah Transaksi',
                        data: {!! json_encode($transactionChartData->pluck('total')) !!},
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderColor: 'rgba(59, 130, 246, 0.8)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: textColor,
                                font: {
                                    size: 14
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: bgColor,
                            titleColor: textColor,
                            bodyColor: textColor,
                            borderColor: gridColor,
                            borderWidth: 1,
                            padding: 12,
                            usePointStyle: true,
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${context.raw}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: gridColor
                            },
                            ticks: {
                                color: textColor
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: gridColor
                            },
                            ticks: {
                                color: textColor,
                                callback: function(value) {
                                    return value;
                                }
                            }
                        }
                    }
                }
            });

            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($revenueChartData->pluck('date')) !!},
                    datasets: [{
                        label: 'Total Pendapatan (Rp)',
                        data: {!! json_encode($revenueChartData->pluck('total')) !!},
                        backgroundColor: 'rgba(16, 185, 129, 0.7)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: textColor,
                                font: {
                                    size: 14
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: bgColor,
                            titleColor: textColor,
                            bodyColor: textColor,
                            borderColor: gridColor,
                            borderWidth: 1,
                            padding: 12,
                            usePointStyle: true,
                            callbacks: {
                                label: function(context) {
                                    return `Rp ${context.raw.toLocaleString()}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: gridColor
                            },
                            ticks: {
                                color: textColor
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: gridColor
                            },
                            ticks: {
                                color: textColor,
                                callback: function(value) {
                                    return `Rp ${value.toLocaleString()}`;
                                }
                            }
                        }
                    }
                }
            });

            // Update charts when dark mode changes
            const darkModeToggle = document.getElementById('darkModeToggle');
            if (darkModeToggle) {
                darkModeToggle.addEventListener('click', function() {
                    setTimeout(() => {
                        transactionChart.update();
                        revenueChart.update();
                    }, 100);
                });
            }
        });
    </script>
@endsection