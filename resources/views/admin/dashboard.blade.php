@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan aktivitas penjualan Soya Damar')

@section('content')

    @php
        $draft = \App\Models\Setoran::where('status', 'draft')->count();
        $dikirim = \App\Models\Setoran::where('status', 'dikirim')->count();
        $acc = \App\Models\Setoran::where('status', 'acc')->count();

        $totalStatus = $draft + $dikirim + $acc;
        $persenAcc = $totalStatus > 0 ? round(($acc / $totalStatus) * 100) : 0;
    @endphp

    {{-- ══════════════════════════════════════ --}}
    {{-- KARTU STATISTIK --}}
    {{-- ══════════════════════════════════════ --}}
    <div id="realtime-container" 
         x-data 
         x-init="setInterval(() => {
            fetch(window.location.href)
                .then(res => res.text())
                .then(html => {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(html, 'text/html');
                    
                    // Ambil konten kartu dan list terbaru, lalu timpa tanpa merusak grafik chart
                    document.getElementById('realtime-cards').innerHTML = doc.getElementById('realtime-cards').innerHTML;
                    document.getElementById('realtime-lists').innerHTML = doc.getElementById('realtime-lists').innerHTML;
                    
                    // Bangkitkan kembali icon lucide yang baru dimuat
                    lucide.createIcons();
                })
         }, 2000)">

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6" id="realtime-cards">

        {{-- Total Sales --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-4">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Total Sales Aktif
                    </p>

                    <h3 class="text-3xl font-bold text-gray-800 mt-2">
                        {{ $totalSales }}
                    </h3>
                </div>

                <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center">
                    <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
                </div>

            </div>

            <p class="text-xs text-gray-400">
                Sales terdaftar
            </p>
        </div>

        {{-- Total Setoran --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-4">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Total Setoran
                    </p>

                    <h3 class="text-3xl font-bold text-gray-800 mt-2">
                        {{ $totalSetoran }}
                    </h3>
                </div>

                <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center">
                    <i data-lucide="wallet" class="w-6 h-6 text-blue-600"></i>
                </div>

            </div>

            <p class="text-xs text-gray-400">
                Semua waktu
            </p>
        </div>

        {{-- Menunggu Konfirmasi --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-4">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Menunggu Konfirmasi
                    </p>

                    <h3 class="text-3xl font-bold text-yellow-500 mt-2">
                        {{ $menungguAcc }}
                    </h3>
                </div>

                <div class="w-12 h-12 bg-yellow-100 rounded-2xl flex items-center justify-center">
                    <i data-lucide="clock-3" class="w-6 h-6 text-yellow-600"></i>
                </div>

            </div>

            <p class="text-xs text-gray-400">
                Perlu ditindaklanjuti
            </p>
        </div>

        {{-- Uang Hari Ini --}}
        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-3xl shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">

                <div>
                    <p class="text-sm font-medium text-blue-100">
                        Uang Masuk Hari Ini
                    </p>

                    <h3 class="text-2xl font-bold text-white mt-2 leading-tight">
                        Rp {{ number_format($totalUangHariIni, 0, ',', '.') }}
                    </h3>
                </div>

                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                    <i data-lucide="badge-dollar-sign" class="w-6 h-6 text-white"></i>
                </div>

            </div>

            <p class="text-xs text-blue-100">
                Setoran ter-ACC hari ini
            </p>
        </div>

    </div>

    {{-- ══════════════════════════════════════ --}}
    {{-- CHART --}}
    {{-- ══════════════════════════════════════ --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-6">

        {{-- Grafik Penjualan --}}
        <div class="xl:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 p-6">

            <div class="flex items-center justify-between mb-6">

                <div>
                    <h3 class="text-lg font-bold text-gray-800">
                        Grafik Penjualan
                    </h3>

                    <p class="text-sm text-gray-400 mt-1">
                        7 hari terakhir (setoran ter-ACC)
                    </p>
                </div>

                <div class="hidden sm:flex items-center gap-2 bg-blue-100 text-blue-700 px-4 py-2 rounded-2xl text-xs font-semibold">
                    <div class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></div>
                    Live Data
                </div>

            </div>

            <canvas id="grafikPenjualan" height="110"></canvas>

        </div>

        {{-- Status Setoran --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6" id="realtime-lists">

            <div class="mb-5">
                <h3 class="text-lg font-bold text-gray-800">
                    Status Setoran
                </h3>

                <p class="text-sm text-gray-400 mt-1">
                    Distribusi status saat ini
                </p>
            </div>

            {{-- CHART KECIL --}}
            <div class="flex justify-center">

                <div class="relative w-[220px] h-[220px]">

                    <canvas id="grafikStatus"></canvas>

                    {{-- CENTER --}}
                    <div class="absolute inset-0 flex flex-col items-center justify-center">

                        <h3 class="text-3xl font-bold text-gray-800">
                            {{ $persenAcc }}%
                        </h3>

                        <p class="text-sm text-gray-400">
                            Disetujui
                        </p>

                    </div>

                </div>

            </div>

            {{-- LEGEND --}}
            <div class="mt-6 space-y-3">

                <div class="flex items-center justify-between">

                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-gray-400"></div>

                        <span class="text-sm text-gray-600">
                            Draft
                        </span>
                    </div>

                    <span class="text-sm font-bold text-gray-700">
                        {{ $draft }}
                    </span>

                </div>

                <div class="flex items-center justify-between">

                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-yellow-400"></div>

                        <span class="text-sm text-gray-600">
                            Dikirim
                        </span>
                    </div>

                    <span class="text-sm font-bold text-gray-700">
                        {{ $dikirim }}
                    </span>

                </div>

                <div class="flex items-center justify-between">

                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-blue-500"></div>

                        <span class="text-sm text-gray-600">
                            Disetujui
                        </span>
                    </div>

                    <span class="text-sm font-bold text-gray-700">
                        {{ $acc }}
                    </span>

                </div>

            </div>

        </div>

    </div>

    {{-- ══════════════════════════════════════ --}}
    {{-- TOP SALES & PENDING --}}
    {{-- ══════════════════════════════════════ --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5" id="realtime-lists">

        {{-- TOP SALES --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">

            <div class="flex items-center justify-between mb-5">

                <div class="flex items-center gap-2">
                    <i data-lucide="trophy" class="w-5 h-5 text-yellow-500"></i>

                    <h3 class="text-lg font-bold text-gray-800">
                        Top Sales
                    </h3>
                </div>

                <a href="{{ route('admin.performa.index') }}"
                   class="text-sm text-blue-600 hover:text-blue-700 font-semibold">
                    Lihat semua →
                </a>

            </div>

            @forelse($topSales as $index => $sales)

                <div class="flex items-center gap-4 py-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">

                    {{-- Ranking --}}
                    <div class="w-9 h-9 rounded-2xl flex items-center justify-center text-sm font-bold flex-shrink-0
                        {{ $index === 0 ? 'bg-yellow-100 text-yellow-600' :
                        ($index === 1 ? 'bg-gray-100 text-gray-600' :
                        ($index === 2 ? 'bg-orange-100 text-orange-600' : 'bg-gray-50 text-gray-400')) }}">

                        {{ $index + 1 }}

                    </div>

                    {{-- Avatar --}}
                    <div class="w-10 h-10 rounded-2xl bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr($sales->nama, 0, 1)) }}
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">

                        <p class="font-semibold text-gray-800 text-sm truncate">
                            {{ $sales->nama }}
                        </p>

                        <p class="text-xs text-gray-400">
                            {{ $sales->no_hp ?? '-' }}
                        </p>

                    </div>

                    {{-- Total --}}
                    <div class="text-right">

                        <p class="font-bold text-blue-700 text-sm">
                            Rp {{ number_format($sales->total_uang, 0, ',', '.') }}
                        </p>

                    </div>

                </div>

            @empty

                <div class="text-center py-10">

                    <div class="w-16 h-16 mx-auto rounded-3xl bg-gray-100 flex items-center justify-center mb-4">
                        <i data-lucide="inbox" class="w-8 h-8 text-gray-400"></i>
                    </div>

                    <p class="text-sm text-gray-500">
                        Belum ada data penjualan
                    </p>

                </div>

            @endforelse

        </div>

        {{-- SETORAN PENDING --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">

            <div class="flex items-center justify-between mb-5">

                <div class="flex items-center gap-2">
                    <i data-lucide="clock-3" class="w-5 h-5 text-yellow-500"></i>

                    <h3 class="text-lg font-bold text-gray-800">
                        Menunggu Konfirmasi
                    </h3>
                </div>

                <a href="{{ route('admin.setoran.index', ['status' => 'dikirim']) }}"
                   class="text-sm text-blue-600 hover:text-blue-700 font-semibold">
                    Lihat semua →
                </a>

            </div>

            @forelse($setoranPending as $setoran)

                <div class="flex items-center gap-4 py-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">

                    {{-- Avatar --}}
                    <div class="w-10 h-10 rounded-2xl bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr($setoran->sales->nama, 0, 1)) }}
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">

                        <p class="font-semibold text-gray-800 text-sm truncate">
                            {{ $setoran->sales->nama }}
                        </p>

                        <p class="text-xs text-gray-400">
                            {{ $setoran->tanggal->format('d M Y') }}
                            •
                            {{ $setoran->detail->count() }} toko
                        </p>

                    </div>

                    {{-- Right --}}
                    <div class="text-right">

                        <p class="font-bold text-gray-800 text-sm">
                            Rp {{ number_format($setoran->totalUang(), 0, ',', '.') }}
                        </p>

                        <a href="{{ route('admin.setoran.show', $setoran) }}"
                           class="text-xs text-blue-600 hover:text-blue-700 font-semibold">
                            Konfirmasi →
                        </a>

                    </div>

                </div>

            @empty

                <div class="text-center py-10">

                    <div class="w-16 h-16 mx-auto rounded-3xl bg-blue-100 flex items-center justify-center mb-4">
                        <i data-lucide="badge-check" class="w-8 h-8 text-blue-600"></i>
                    </div>

                    <p class="text-sm text-gray-500">
                        Semua setoran sudah dikonfirmasi!
                    </p>

                </div>

            @endforelse

        </div>

    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    lucide.createIcons();

    // ─────────────────────────────
    // DATA
    // ─────────────────────────────
    const grafikLabels = @json($grafikData->pluck('tanggal'));
    const grafikValues = @json($grafikData->pluck('total'));

    // ─────────────────────────────
    // LINE CHART
    // ─────────────────────────────
    const ctxLine = document.getElementById('grafikPenjualan');

    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: grafikLabels,
            datasets: [{
                label: 'Total Penjualan',
                data: grafikValues,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37,99,235,0.08)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: '#2563eb',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            return ' Rp ' + ctx.raw.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.04)'
                    },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + (value / 1000) + 'k';
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // ─────────────────────────────
    // DOUGHNUT STATUS
    // ─────────────────────────────
    const ctxPie = document.getElementById('grafikStatus');

    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: ['Draft', 'Dikirim', 'Disetujui'],
            datasets: [{
                data: [
                    {{ $draft }},
                    {{ $dikirim }},
                    {{ $acc }}
                ],
                backgroundColor: [
                    '#9ca3af',
                    '#facc15',
                    '#2563eb'
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

});
</script>
@endpush