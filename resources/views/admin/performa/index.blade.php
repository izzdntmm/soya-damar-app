@extends('admin.layouts.app')

@section('page-title')
<div class="flex items-center gap-2">
    <span>Performa & Kinerja Sales</span>
</div>
@endsection
@section('content')

{{-- Tombol Export Performa --}}
<div class="flex items-center gap-3 mb-5">
    <span class="text-sm text-gray-500 font-medium">Export Rekap:</span>
    <a href="{{ route('admin.export.performa.excel', [
        'mulai' => $mulai->format('Y-m-d'),
        'akhir' => $akhir->format('Y-m-d'),
    ]) }}"
    class="flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 transition w-full sm:w-auto justify-center">

        <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
        <span>Export Excel</span>
    </a>
</div>

{{-- ══ FILTER PERIODE ════════════════════════════════ --}}
<form method="GET" action="{{ route('admin.performa.index') }}"
      class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6"
      x-data="{ periode: '{{ $periode }}' }">

    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">
        Pilih Periode
    </p>

    <div class="flex flex-wrap gap-2 mb-4">
        @foreach([
            'minggu_ini'  => 'Minggu Ini',
            'bulan_ini'   => 'Bulan Ini',
            'bulan_lalu'  => 'Bulan Lalu',
            'tahun_ini'   => 'Tahun Ini',
            'custom'      => 'Custom',
        ] as $val => $label)
        <label class="cursor-pointer">
            <input type="radio" name="periode" value="{{ $val }}"
                   x-model="periode" class="sr-only">
            <span :class="periode === '{{ $val }}'
                    ? 'bg-blue-600 text-white border-blue-600'
                    : 'bg-white text-gray-600 border-gray-200 hover:border-blue-400'"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border text-sm font-medium transition cursor-pointer">

                @if($val === 'custom')
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                @endif

                {{ $label }}
            </span>
        </label>
        @endforeach
    </div>

    {{-- Input custom tanggal --}}
    <div x-show="periode === 'custom'"
         x-transition
         class="flex flex-wrap items-center gap-3 mb-4">
        <div>
            <label class="text-xs text-gray-500 block mb-1">Dari Tanggal</label>
            <input type="date" name="tanggal_mulai"
                   value="{{ $tanggalMulai ?? $mulai->format('Y-m-d') }}"
                   class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
            <label class="text-xs text-gray-500 block mb-1">Sampai Tanggal</label>
            <input type="date" name="tanggal_akhir"
                   value="{{ $tanggalAkhir ?? $akhir->format('Y-m-d') }}"
                   class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
    </div>

    <button type="submit"
    class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition flex items-center gap-2">

    <i data-lucide="search" class="w-4 h-4"></i>
    <span>Tampilkan</span>

</button>

</form>

{{-- ══ KARTU TOTAL PERIODE ═══════════════════════════ --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

    <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-5 text-white">
        <p class="text-sm text-blue-100 font-medium">Total Pendapatan</p>
        <p class="text-3xl font-bold mt-2">
            Rp {{ number_format($totalPeriode['uang'], 0, ',', '.') }}
        </p>
        <p class="text-xs text-blue-200 mt-1">{{ $totalPeriode['setoran'] }} setoran terkonfirmasi</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-sm text-gray-500 font-medium">Total Unit Terjual</p>
        <p class="text-3xl font-bold text-gray-800 mt-2">
            {{ number_format($totalPeriode['unit'], 0, ',', '.') }}
        </p>
        <p class="text-xs text-gray-400 mt-1">unit produk</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-sm text-gray-500 font-medium">Sales Aktif</p>
        <p class="text-3xl font-bold text-gray-800 mt-2">
            {{ $rankingSales->where('total_uang', '>', 0)->count() }}
        </p>
        <p class="text-xs text-gray-400 mt-1">dari {{ $rankingSales->count() }} total sales</p>
    </div>

</div>

{{-- ══ HIGHLIGHT CARDS ════════════════════════════════ --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

    {{-- Sales Terbaik --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center gap-2 mb-3">
            <div class="w-10 h-10 rounded-xl bg-yellow-100 flex items-center justify-center">
                <i data-lucide="trophy" class="w-5 h-5 text-yellow-600"></i>
            </div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Sales Terbaik</p>
        </div>
        @if($salesTerbaik && $salesTerbaik->total_uang > 0)
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-yellow-100 text-yellow-700 font-bold text-lg flex items-center justify-center">
                {{ strtoupper(substr($salesTerbaik->nama, 0, 1)) }}
            </div>
            <div>
                <p class="font-bold text-gray-800">{{ $salesTerbaik->nama }}</p>
                <p class="text-xs text-gray-500 mt-0.5">
                    Rp {{ number_format($salesTerbaik->total_uang, 0, ',', '.') }}
                </p>
                <p class="text-xs text-gray-400">{{ $salesTerbaik->total_unit }} unit</p>
            </div>
        </div>
        @else
        <p class="text-gray-400 text-sm">Belum ada data</p>
        @endif
    </div>

    {{-- Toko Paling Laris --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center gap-2 mb-3">
            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                <i data-lucide="store" class="w-5 h-5 text-blue-600"></i>
            </div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Toko Paling Laris</p>
        </div>
        @if($tokoPaling)
        <div>
            <p class="font-bold text-gray-800">{{ $tokoPaling->toko->nama_toko }}</p>
            <p class="text-xs text-gray-500 mt-1">
                Sales: {{ $tokoPaling->toko->sales->nama ?? '-' }}
            </p>
            <p class="text-xs text-gray-400 mt-0.5">
                {{ number_format($tokoPaling->total_terjual, 0, ',', '.') }} unit terjual
            </p>
        </div>
        @else
        <p class="text-gray-400 text-sm">Belum ada data</p>
        @endif
    </div>

    {{-- Hari Penjualan Tertinggi --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center gap-2 mb-3">
            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                <i data-lucide="calendar-days" class="w-5 h-5 text-blue-600"></i>
            </div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Hari Tersibuk</p>
        </div>
        @if($hariTertinggi)
        <div>
            <p class="font-bold text-gray-800">
                {{ \Carbon\Carbon::parse($hariTertinggi->tanggal)->translatedFormat('l') }}
            </p>
            <p class="text-xs text-gray-500 mt-1">
                {{ \Carbon\Carbon::parse($hariTertinggi->tanggal)->translatedFormat('d F Y') }}
            </p>
            <p class="text-xs text-gray-400 mt-0.5">
                {{ $hariTertinggi->jumlah_setoran }} setoran masuk
            </p>
        </div>
        @else
        <p class="text-gray-400 text-sm">Belum ada data</p>
        @endif
    </div>

</div>

{{-- ══ GRAFIK ══════════════════════════════════════════ --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-5 mb-6">

    {{-- Bar Chart: Perbandingan Sales --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="mb-5">
            <h3 class="font-bold text-gray-800">Perbandingan Penjualan per Sales</h3>
            <p class="text-xs text-gray-400 mt-0.5">Total pendapatan dalam periode yang dipilih</p>
        </div>
        <canvas id="grafikBar" height="220"></canvas>
    </div>

    {{-- Line Chart: Tren Harian --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="mb-5">
            <h3 class="font-bold text-gray-800">Tren Penjualan Harian</h3>
            <p class="text-xs text-gray-400 mt-0.5">14 hari terakhir dalam periode</p>
        </div>
        <canvas id="grafikLine" height="220"></canvas>
    </div>

</div>

{{-- ══ RANKING SALES ══════════════════════════════════ --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">

    <div class="px-6 py-4 border-b border-gray-100">
        <i data-lucide="medal" class="w-5 h-5 text-yellow-500"></i>
        <h3 class="font-bold text-gray-800">Ranking Sales</h3>
        <p class="text-xs text-gray-400 mt-0.5">Diurutkan berdasarkan total pendapatan</p>
    </div>

    @php $maxUang = $rankingSales->max('total_uang') ?: 1; @endphp

    <div class="divide-y divide-gray-50">
        @forelse($rankingSales as $index => $sales)
        <div class="px-6 py-5 hover:bg-gray-50 transition cursor-pointer"
             onclick="showDetail({{ $sales->id }}, '{{ addslashes($sales->nama) }}')"
             title="Klik untuk lihat detail">

            <div class="flex items-center gap-4">

                {{-- Rank Badge --}}
                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                    {{ $index === 0 ? 'bg-yellow-100 text-yellow-700 ring-2 ring-yellow-300' :
                    ($index === 1 ? 'bg-gray-100 text-gray-600 ring-2 ring-gray-300' :
                    ($index === 2 ? 'bg-orange-100 text-orange-600 ring-2 ring-orange-300' :
                                    'bg-gray-50 text-gray-400')) }}">

                    @if($index === 0)
                        <i data-lucide="trophy" class="w-5 h-5"></i>
                    @elseif($index === 1)
                        <i data-lucide="medal" class="w-5 h-5"></i>
                    @elseif($index === 2)
                        <i data-lucide="award" class="w-5 h-5"></i>
                    @else
                        <span class="font-bold text-sm">{{ $index + 1 }}</span>
                    @endif

                </div>

                {{-- Avatar --}}
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 font-bold flex items-center justify-center flex-shrink-0">
                    {{ strtoupper(substr($sales->nama, 0, 1)) }}
                </div>

                {{-- Info & Progress --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-3 mb-2">
                        <div>
                            <p class="font-bold text-gray-800">{{ $sales->nama }}</p>
                            <p class="text-xs text-gray-400">
                                {{ $sales->toko_aktif }} toko aktif ·
                                {{ $sales->jumlah_setoran }} setoran ·
                                {{ number_format($sales->total_unit, 0, ',', '.') }} unit
                            </p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="font-bold text-blue-700">
                                Rp {{ number_format($sales->total_uang, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ $maxUang > 0 ? round(($sales->total_uang / $maxUang) * 100) : 0 }}% dari terbaik
                            </p>
                        </div>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="h-2 rounded-full transition-all duration-700
                            {{ $index === 0 ? 'bg-yellow-400' :
                               ($index === 1 ? 'bg-gray-400' :
                               ($index === 2 ? 'bg-orange-400' : 'bg-blue-400')) }}"
                             style="width: {{ $maxUang > 0 ? ($sales->total_uang / $maxUang) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>

                {{-- Arrow --}}
                <div class="text-gray-300 flex-shrink-0">›</div>

            </div>
        </div>
        @empty
        <div class="py-16 text-center text-gray-400">
            <p class="text-5xl mb-3">👥</p>
            <p class="font-semibold text-gray-500">Belum ada data sales</p>
        </div>
        @endforelse
    </div>
</div>

{{-- ══ TOP 5 TOKO TERLARIS ═══════════════════════════ --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

    <div class="px-6 py-4 border-b border-gray-100">
        <i data-lucide="store" class="w-5 h-5 text-blue-500"></i>
        <h3 class="font-bold text-gray-800">Top 5 Toko Terlaris</h3>
        <p class="text-xs text-gray-400 mt-0.5">Berdasarkan total unit terjual dalam periode ini</p>
    </div>

    @php $maxToko = $topToko->max('total_terjual') ?: 1; @endphp

    <div class="divide-y divide-gray-50">
        @forelse($topToko as $i => $item)
        <div class="px-6 py-4">
            <div class="flex items-center gap-4">

                {{-- Rank --}}
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0
                    {{ $i === 0 ? 'bg-yellow-100 text-yellow-700' :
                       ($i === 1 ? 'bg-gray-100 text-gray-600' :
                       ($i === 2 ? 'bg-orange-100 text-orange-600' : 'bg-gray-50 text-gray-400')) }}">
                    {{ $i + 1 }}
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-1.5">
                        <div>
                            <p class="font-semibold text-gray-800 truncate">
                                {{ $item->toko->nama_toko }}
                            </p>
                            <p class="text-xs text-gray-400">
                                Sales: {{ $item->toko->sales->nama ?? '-' }} ·
                                Rp {{ number_format($item->total_uang, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="text-right flex-shrink-0 ml-3">
                            <p class="font-bold text-gray-800">
                                {{ number_format($item->total_terjual, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-400">unit</p>
                        </div>
                    </div>

                    {{-- Progress --}}
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div class="h-1.5 rounded-full bg-blue-400"
                             style="width: {{ ($item->total_terjual / $maxToko) * 100 }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="py-10 text-center text-gray-400 text-sm">
            Belum ada data toko
        </div>
        @endforelse
    </div>
</div>

{{-- ══ MODAL DETAIL SALES ════════════════════════════ --}}
<div id="modalDetail"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden"
     onclick="if(event.target===this) closeModal()">

    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[85vh] overflow-hidden flex flex-col">

        {{-- Header Modal --}}
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between flex-shrink-0">
            <div>
                <h3 class="font-bold text-gray-800 text-lg" id="modalNama">Detail Performa</h3>
                <p class="text-xs text-gray-400 mt-0.5">Breakdown per toko dalam periode ini</p>
            </div>
            <button onclick="closeModal()"
                class="w-8 h-8 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 transition flex items-center justify-center font-bold">
                ✕
            </button>
        </div>

        {{-- Body Modal --}}
        <div class="flex-1 overflow-y-auto p-6" id="modalBody">
            <div class="text-center py-10 text-gray-400">
                <div class="text-4xl mb-2">⏳</div>
                <p>Memuat data...</p>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Data dari Laravel ──────────────────────────
    const barLabels = @json($grafikBar->pluck('nama'));
    const barUang   = @json($grafikBar->pluck('total_uang'));
    const barUnit   = @json($grafikBar->pluck('total_unit'));

    const lineLabels = @json($grafikLine->pluck('tanggal'));
    const lineData   = @json($grafikLine->pluck('total'));

    // Warna untuk tiap sales di bar chart
    const warna = [
        'rgba(59,130,246,0.8)',  // blue
    ];

    // ── BAR CHART ──────────────────────────────────
    const ctxBar = document.getElementById('grafikBar').getContext('2d');

    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: barLabels,
            datasets: [
                {
                    label: 'Total Pendapatan (Rp)',
                    data: barUang,
                    backgroundColor: warna,
                    borderRadius: 8,
                    borderSkipped: false,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ' Rp ' + ctx.raw.toLocaleString('id-ID'),
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.04)' },
                    ticks: {
                        callback: val => 'Rp ' + (val/1000) + 'k',
                        font: { size: 11 }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 12, weight: '600' } }
                }
            }
        }
    });

    // ── LINE CHART ─────────────────────────────────
    const ctxLine = document.getElementById('grafikLine').getContext('2d');

    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: lineLabels,
            datasets: [{
                label: 'Total Penjualan',
                data: lineData,
                borderColor: 'rgba(59, 130, 246, 1)',
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                borderWidth: 2.5,
                pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ' Rp ' + ctx.raw.toLocaleString('id-ID'),
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.04)' },
                    ticks: {
                        callback: val => 'Rp ' + (val/1000) + 'k',
                        font: { size: 11 }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 } }
                }
            }
        }
    });

});

// ── MODAL DETAIL SALES ─────────────────────────────
let modalChartInstance = null;

async function showDetail(salesId, nama) {
    // Tampilkan modal
    document.getElementById('modalDetail').classList.remove('hidden');
    document.getElementById('modalNama').textContent = '📊 ' + nama;
    document.getElementById('modalBody').innerHTML = `
        <div class="text-center py-10 text-gray-400">
            <div class="text-4xl mb-2 animate-spin">⏳</div>
            <p>Memuat data...</p>
        </div>`;

    try {
        // Fetch data dari controller
        const params = new URLSearchParams(window.location.search);
        const res    = await fetch(`/admin/performa/${salesId}/detail?${params}`);
        const data   = await res.json();

        // Destroy chart lama kalau ada
        if (modalChartInstance) {
            modalChartInstance.destroy();
            modalChartInstance = null;
        }

        // Render konten modal
        document.getElementById('modalBody').innerHTML = buildModalHtml(data);

        // Render chart tren di dalam modal
        const ctx = document.getElementById('chartTrenModal');
        if (ctx && data.tren_harian.length) {
            modalChartInstance = new Chart(ctx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: data.tren_harian.map(d => d.tanggal),
                    datasets: [{
                        data: data.tren_harian.map(d => d.total),
                        backgroundColor: 'rgba(34,197,94,0.7)',
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: v => 'Rp' + (v/1000) + 'k',
                                font: { size: 10 }
                            }
                        },
                        x: { ticks: { font: { size: 10 } } }
                    }
                }
            });
        }

    } catch (e) {
        document.getElementById('modalBody').innerHTML = `
            <div class="text-center py-10 text-red-400">
                <p>Gagal memuat data. Coba refresh halaman.</p>
            </div>`;
    }
}

function buildModalHtml(data) {
    let rows = '';

    if (data.breakdown_toko.length === 0) {
        rows = '<p class="text-gray-400 text-sm text-center py-6">Belum ada data toko dalam periode ini.</p>';
    } else {
        const maxTerjual = Math.max(...data.breakdown_toko.map(t => t.total_terjual));

        data.breakdown_toko.forEach((t, i) => {
            const pct   = maxTerjual > 0 ? Math.round((t.total_terjual / maxTerjual) * 100) : 0;
            const nama  = t.toko?.nama_toko ?? '-';
            const total = parseInt(t.total_uang).toLocaleString('id-ID');

            rows += `
            <div class="py-3 ${i < data.breakdown_toko.length - 1 ? 'border-b border-gray-50' : ''}">
                <div class="flex justify-between items-start mb-1.5">
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">${nama}</p>
                        <p class="text-xs text-gray-400">${t.frekuensi}x setoran · Rp ${total}</p>
                    </div>
                    <div class="text-right ml-4 flex-shrink-0">
                        <p class="font-bold text-gray-800">${t.total_terjual}</p>
                        <p class="text-xs text-gray-400">unit</p>
                    </div>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-1.5">
                    <div class="h-1.5 rounded-full bg-blue-400" style="width:${pct}%"></div>
                </div>
            </div>`;
        });
    }

    return `
    <div class="mb-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">
            Breakdown Per Toko
        </p>
        <div>${rows}</div>
    </div>

    <div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">
            Tren Penjualan 14 Hari Terakhir
        </p>
        <canvas id="chartTrenModal" height="120"></canvas>
    </div>`;
}

function closeModal() {
    document.getElementById('modalDetail').classList.add('hidden');
    if (modalChartInstance) {
        modalChartInstance.destroy();
        modalChartInstance = null;
    }
}

// Tutup modal dengan tombol Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeModal();
});
</script>
@endpush