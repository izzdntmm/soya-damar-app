@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">

    {{-- Notifikasi --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6" x-data="{ showAll: false }">
        <h2 class="text-xl font-semibold mb-4">Notifikasi</h2>

        @if ($notifications->count() === 0)
            <p class="text-sm text-gray-500">Belum ada notifikasi.</p>
        @else
            @foreach ($notifications as $index => $notification)
                <div x-show="showAll || {{ $index }} < 3" class="mb-3 border-b pb-2">
                    <p class="text-sm text-gray-700">
                        {{ $notification->data['message'] ?? 'Tidak ada pesan.' }}
                    </p>
                    <p class="text-xs text-gray-500">
                        {{ $notification->created_at->diffForHumans() }}
                    </p>
                </div>
            @endforeach

            {{-- Tombol Lihat Semua --}}
            <div class="mt-4">
                <button @click="showAll = !showAll"
                    class="bg-blue-500 text-white text-sm w-full sm:w-auto px-4 py-2 rounded hover:bg-blue-600 transition duration-200">
                    <span x-show="!showAll">Lihat Semua</span>
                    <span x-show="showAll">Sembunyikan</span>
                </button>
            </div>
        @endif
    </div>

    {{-- Tabel Laporan Sales --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Daftar Laporan Sales yang Sudah Dikirim</h2>

        <div class="overflow-x-auto">
            <table class="w-full border border-gray-300 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-300 px-4 py-2 text-left">Nama Sales</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Jumlah Setoran</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sales as $salesman)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2">{{ $salesman->name }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $salesman->submitted_count }}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                <a href="{{ route('admin.reports.show', $salesman->id) }}"
                                   class="inline-block w-full sm:w-auto bg-blue-500 text-white text-sm px-4 py-2 rounded hover:bg-blue-600 transition duration-200 text-center">
                                   Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-gray-500 py-4">Belum ada laporan yang dikirim</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
