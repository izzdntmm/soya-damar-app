@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Laporan Setoran: {{ $user->name }}</h2>

        {{-- Filter Tanggal --}}
        <form method="GET" class="mb-6 flex flex-col sm:flex-row sm:items-center gap-4">
            <label for="tanggal" class="text-sm font-medium">Pilih Tanggal:</label>
            <input type="date" name="tanggal" id="tanggal"
                   value="{{ $tanggal }}"
                   class="border border-gray-300 rounded px-3 py-2 text-sm w-full sm:w-60">
            <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600 transition">
                Filter
            </button>
        </form>

        {{-- Tabel Setoran --}}
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-300 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-300 px-4 py-2 text-left">Toko</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Alamat</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Jumlah Barang</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Total Harga</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($deliveries as $delivery)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2">{{ $delivery->store->name }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $delivery->store->address }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $delivery->quantity }}</td>
                            <td class="border border-gray-300 px-4 py-2">Rp{{ number_format($delivery->total_price, 0, ',', '.') }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ \Carbon\Carbon::parse($delivery->delivery_date)->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-500 py-4">
                                Tidak ada data setoran pada tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Total --}}
        <div class="mt-6 text-sm font-semibold text-gray-700">
            Total Barang: {{ $totalBarang }} <br>
            Total Setoran: Rp{{ number_format($totalHarga, 0, ',', '.') }}
        </div>

        {{-- Tombol Kembali --}}
        <div class="mt-6">
            <a href="{{ route('admin.reports.index') }}"
               class="inline-block bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600 transition">
                ← Kembali
            </a>
        </div>
    </div>

</div>
@endsection
