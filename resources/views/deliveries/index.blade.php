@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <h2 class="text-xl sm:text-2xl font-bold text-blue-700 mb-4 sm:mb-6">
            📦 Setoran Belum Dikirim
        </h2>

        {{-- Tombol Tambah --}}
        <div class="mb-4 sm:mb-6">
            <a href="{{ route('deliveries.create') }}"
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 sm:px-5 py-2 rounded-md shadow text-sm sm:text-base transition">
                + Tambah Setoran
            </a>
        </div>

        {{-- Pesan Sukses --}}
        @if(session('success'))
            <div class="mb-4 p-3 sm:p-4 bg-green-100 text-green-700 rounded shadow text-sm sm:text-base">
                {{ session('success') }}
            </div>
        @endif

        {{-- Tabel Responsif --}}
        <div class="bg-white shadow rounded-lg overflow-x-auto text-sm sm:text-base">
            <table class="w-full min-w-[500px] sm:min-w-full text-left text-gray-700">
                <thead class="bg-gray-100 text-xs sm:text-sm uppercase">
                    <tr>
                        <th class="px-4 py-3">Toko</th>
                        <th class="px-4 py-3">Jumlah</th>
                        <th class="px-4 py-3">Total Harga</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($deliveries as $delivery)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $delivery->store->name }}</td>
                            <td class="px-4 py-3">{{ $delivery->quantity }}</td>
                            <td class="px-4 py-3">Rp{{ number_format($delivery->total_price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($delivery->delivery_date)->format('d M Y') }}</td>
                            <td class="px-4 py-3 flex space-x-2 text-sm">
                                <a href="{{ route('deliveries.edit', $delivery->id) }}"
                                   class="text-blue-600 hover:underline">Edit</a>

                                <form action="{{ route('deliveries.destroy', $delivery->id) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin hapus?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                                Belum ada setoran yang perlu dikirim.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Tombol Kirim Semua --}}
        @if(count($deliveries) > 0)
            <form action="{{ route('deliveries.submit') }}" method="POST"
                  onsubmit="return confirm('Yakin kirim semua laporan ke admin?')"
                  class="mt-5 sm:mt-6">
                @csrf
                <button type="submit"
                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow text-sm sm:text-base transition">
                    Kirim Semua Laporan ke Admin
                </button>
            </form>
        @endif

    </div>
</div>
@endsection
