@extends('sales.layouts.app')

@section('title', 'Riwayat Setoran')

@section('page-title')
<div class="flex items-center gap-2">
    <i data-lucide="history" class="w-5 h-5 text-blue-600"></i>
    <span>Riwayat Setoran</span>
</div>
@endsection

@section('page-subtitle', 'Semua riwayat laporan setoran kamu')

@section('content')

<div class="max-w-3xl">

    <a href="{{ route('sales.setoran.index') }}"
       class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 mb-6 transition">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Kembali ke Setoran Hari Ini
    </a>

    <div class="space-y-4">
        @forelse($setoran as $item)

        {{-- Card Setoran --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            {{-- Header --}}
            <div class="px-5 py-4 flex items-center justify-between border-b border-gray-50">
                <div>
                    <p class="font-bold text-gray-800">
                        {{ $item->tanggal->translatedFormat('l, d F Y') }}
                    </p>

                    <p class="text-xs text-gray-400 mt-0.5">
                        {{ $item->detail->count() }} toko ·
                        {{ $item->totalTerjual() }} unit
                    </p>
                </div>

                <div class="text-right">
                    <p class="font-bold text-gray-800">
                        Rp {{ number_format($item->totalUang(), 0, ',', '.') }}
                    </p>

                    <span class="inline-flex items-center gap-1 mt-1 text-xs px-2.5 py-1 rounded-full font-semibold
                        {{ $item->status === 'acc'
                            ? 'bg-green-100 text-green-700'
                            : ($item->status === 'dikirim'
                                ? 'bg-yellow-100 text-yellow-700'
                                : 'bg-gray-100 text-gray-500') }}">

                        @if($item->status === 'acc')
                            <i data-lucide="badge-check" class="w-3.5 h-3.5"></i>
                            DISETUJUI
                        @elseif($item->status === 'dikirim')
                            <i data-lucide="clock-3" class="w-3.5 h-3.5"></i>
                            MENUNGGU ACC
                        @else
                            <i data-lucide="file-pen-line" class="w-3.5 h-3.5"></i>
                            DRAFT
                        @endif

                    </span>
                </div>
            </div>

            {{-- Detail Item --}}
            <div class="divide-y divide-gray-50">
                @foreach($item->detail as $detail)
                <div class="px-5 py-3 flex justify-between text-sm gap-4">

                    <div class="flex items-center gap-2 min-w-0">
                        <i data-lucide="store" class="w-4 h-4 text-blue-500 flex-shrink-0"></i>

                        <span class="text-gray-700 truncate">
                            {{ $detail->toko->nama_toko }}
                        </span>
                    </div>

                    <span class="text-gray-500 whitespace-nowrap">
                        {{ $detail->jumlah_terjual }} unit =
                        <strong class="text-gray-800">
                            Rp {{ number_format($detail->total_uang, 0, ',', '.') }}
                        </strong>
                    </span>
                </div>
                @endforeach
            </div>

        </div>

        @empty
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 py-16 text-center text-gray-400">

            <div class="w-20 h-20 mx-auto rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                <i data-lucide="history" class="w-10 h-10 text-gray-400"></i>
            </div>

            <p class="font-semibold text-gray-500">
                Belum ada riwayat setoran
            </p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($setoran->hasPages())
    <div class="mt-6">
        {{ $setoran->links() }}
    </div>
    @endif

</div>

@endsection