@extends('sales.layouts.app')

@section('title', 'Dashboard Sales')

@section('page-title')
<div class="flex items-center gap-2">
    <span>Dashboard Sales</span>
</div>
@endsection

@section('page-subtitle', "Selamat datang, " . Auth::user()->nama . "!")

@section('content')

<div id="realtime-container" 
     x-data 
     x-init="setInterval(() => {
        fetch(window.location.href)
            .then(res => res.text())
            .then(html => {
                let parser = new DOMParser();
                let doc = parser.parseFromString(html, 'text/html');
                let newData = doc.getElementById('realtime-cards').innerHTML;
                document.getElementById('realtime-cards').innerHTML = newData;
                lucide.createIcons(); {{-- Biar ikon lucide ga ilang setelah di-reload --}}
            })
     }, 2000)"> <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6" id="realtime-cards">

        {{-- TOTAL TOKO --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Toko Saya</p>
                    <p class="text-4xl font-bold text-blue-600 mt-3">
                        {{ $totalToko }}
                    </p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center">
                    <i data-lucide="store" class="w-7 h-7 text-blue-600"></i>
                </div>
            </div>
            <a href="{{ route('sales.toko.index') }}" class="inline-flex items-center gap-2 mt-5 text-sm font-medium text-blue-600 hover:text-blue-700 transition">
                <span>Lihat semua toko</span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        {{-- STATUS SETORAN --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Status Setoran Hari Ini</p>
                    <div class="mt-3">
                        @if($setoranHariIni)
                            @if($setoranHariIni->status === 'acc')
                                <div class="inline-flex items-center gap-2 bg-blue-100 text-blue-700 px-4 py-2 rounded-2xl font-semibold text-sm">
                                    <i data-lucide="badge-check" class="w-4 h-4"></i>
                                    <span>DISETUJUI</span>
                                </div>
                            @elseif($setoranHariIni->status === 'dikirim')
                                <div class="inline-flex items-center gap-2 bg-yellow-100 text-yellow-700 px-4 py-2 rounded-2xl font-semibold text-sm">
                                    <i data-lucide="clock-3" class="w-4 h-4"></i>
                                    <span>MENUNGGU ACC</span>
                                </div>
                            @else
                                <div class="inline-flex items-center gap-2 bg-gray-100 text-gray-600 px-4 py-2 rounded-2xl font-semibold text-sm">
                                    <i data-lucide="file-text" class="w-4 h-4"></i>
                                    <span>DRAFT</span>
                                </div>
                            @endif
                        @else
                            <div class="inline-flex items-center gap-2 bg-gray-100 text-gray-500 px-4 py-2 rounded-2xl text-sm font-medium">
                                <i data-lucide="circle-alert" class="w-4 h-4"></i>
                                <span>Belum ada setoran</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center">
                    <i data-lucide="wallet" class="w-7 h-7 text-blue-600"></i>
                </div>
            </div>
            <a href="{{ route('sales.setoran.index') }}" class="inline-flex items-center gap-2 mt-5 text-sm font-medium text-blue-600 hover:text-blue-700 transition">
                <span>Input setoran hari ini</span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

    </div>

</div>

@endsection