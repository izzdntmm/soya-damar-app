@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-6">

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-6">Laporan Bulanan Penjualan</h2>

            {{-- Filter Form --}}
            <form method="GET" class="flex flex-col sm:flex-row sm:items-end gap-4 mb-6">
                <div>
                    <label for="sales_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Sales:</label>
                    <select name="sales_id" id="sales_id"
                        class="border border-gray-300 rounded w-full sm:w-60 px-3 py-2 text-sm">
                        <option value="">-- Semua Sales --</option>
                        @foreach ($sales as $s)
                            <option value="{{ $s->id }}" {{ $selectedSalesId == $s->id ? 'selected' : '' }}>
                                {{ $s->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="month" class="block text-sm font-medium text-gray-700 mb-1">Pilih Bulan:</label>
                    <input type="month" name="month" id="month" value="{{ $selectedMonth }}"
                        class="border border-gray-300 rounded w-full sm:w-48 px-3 py-2 text-sm">
                </div>

                <div>
                    <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600 transition">
                        Filter
                    </button>
                </div>
            </form>

            <a href="{{ route('admin.reports.monthly.pdf', ['sales_id' => $selectedSalesId, 'month' => $selectedMonth]) }}"
                class="inline-block bg-green-500 text-white px-4 py-2 rounded text-sm hover:bg-green-600 transition mb-4">
                Export PDF
            </a>


            {{-- Tabel Data --}}
            <div class="overflow-x-auto">
                <table class="w-full border border-gray-300 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2 text-left">Nama Toko</th>
                            <th class="border px-4 py-2 text-left">Total Barang</th>
                            <th class="border px-4 py-2 text-left">Total Uang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($results as $r)
                            <tr class="hover:bg-gray-50">
                                <td class="border px-4 py-2">{{ $r->store_name }}</td>
                                <td class="border px-4 py-2">{{ $r->total_barang }}</td>
                                <td class="border px-4 py-2">Rp{{ number_format($r->total_uang, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-gray-500 py-4">
                                    Tidak ada data untuk bulan ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Grafik Chart --}}
            @if(count($results) > 0)
                <div class="mt-10">
                    <canvas id="salesChart" height="100"></canvas>
                </div>
            @endif
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        @if(count($results) > 0)
            const ctx = document.getElementById('salesChart').getContext('2d');
            const salesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($results->pluck('store_name')) !!},
                    datasets: [{
                        label: 'Total Penjualan (Rp)',
                        data: {!! json_encode($results->pluck('total_uang')) !!},
                        backgroundColor: 'rgba(59, 130, 246, 0.6)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: true, text: 'Grafik Penjualan Bulanan per Toko' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return 'Rp' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        @endif
    </script>
@endsection