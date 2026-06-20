@extends('admin.layouts.app')

@section('title', 'Detail Sales')

@section('page-title')
    <div class="flex items-center gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-800">
                Detail Sales
            </h1>
        </div>
    </div>
@endsection

@section('page-subtitle', 'Informasi lengkap sales')

@section('content')

    <div class="max-w-6xl">

        {{-- BACK --}}
        <a href="{{ route('admin.sales.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition mb-6 font-medium">

            <i data-lucide="arrow-left" class="w-4 h-4"></i>

            <span>Kembali ke Daftar Sales</span>

        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- PROFILE CARD --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">

                {{-- Avatar --}}
                <div class="text-center">

                    <div
                        class="w-24 h-24 rounded-3xl bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-4xl mx-auto shadow-sm">
                        {{ strtoupper(substr($sale->nama, 0, 1)) }}
                    </div>

                    <h2 class="mt-5 text-xl font-bold text-gray-800">
                        {{ $sale->nama }}
                    </h2>

                    <p class="text-sm text-gray-400 mt-1 break-all">
                        {{ $sale->email }}
                    </p>

                    <span
                        class="inline-flex items-center gap-2 mt-4 px-4 py-1.5 bg-blue-100 text-blue-700 text-xs font-bold rounded-full uppercase tracking-wide">

                        <i data-lucide="badge-check" class="w-3.5 h-3.5"></i>

                        Sales

                    </span>

                </div>

                {{-- INFO --}}
                <div class="mt-7 space-y-4">

                    {{-- No HP --}}
                    <div class="flex items-start gap-3">

                        <div class="w-10 h-10 rounded-2xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="smartphone" class="w-4 h-4 text-blue-600"></i>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 mb-1">
                                Nomor HP
                            </p>

                            <p class="text-sm font-medium text-gray-700">
                                {{ $sale->no_hp ?? 'Belum diisi' }}
                            </p>
                        </div>

                    </div>

                    {{-- Alamat --}}
                    <div class="flex items-start gap-3">

                        <div class="w-10 h-10 rounded-2xl bg-red-50 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="map-pin" class="w-4 h-4 text-red-500"></i>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 mb-1">
                                Alamat
                            </p>

                            <p class="text-sm font-medium text-gray-700 leading-relaxed">
                                {{ $sale->alamat ?? 'Belum diisi' }}
                            </p>
                        </div>

                    </div>

                    {{-- Bergabung --}}
                    <div class="flex items-start gap-3">

                        <div class="w-10 h-10 rounded-2xl bg-yellow-50 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="calendar-days" class="w-4 h-4 text-yellow-600"></i>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 mb-1">
                                Bergabung
                            </p>

                            <p class="text-sm font-medium text-gray-700">
                                {{ $sale->created_at->format('d M Y') }}
                            </p>
                        </div>

                    </div>

                </div>

                {{-- BUTTON --}}
                <a href="{{ route('admin.sales.edit', $sale) }}"
                    class="mt-7 w-full flex items-center justify-center gap-2 px-4 py-3 bg-yellow-50 text-yellow-700 rounded-2xl text-sm font-semibold hover:bg-yellow-100 transition">

                    <i data-lucide="pencil" class="w-4 h-4"></i>

                    <span>Edit Data Sales</span>

                </a>

            </div>

            {{-- RIGHT CONTENT --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- STATS --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    {{-- Total Toko --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">

                        <div class="flex items-center justify-between">

                            <div>
                                <p class="text-sm text-gray-500 font-medium">
                                    Total Toko
                                </p>

                                <h3 class="text-3xl font-bold text-blue-600 mt-2">
                                    {{ $sale->toko_count }}
                                </h3>
                            </div>

                            <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center">
                                <i data-lucide="store" class="w-6 h-6 text-blue-600"></i>
                            </div>

                        </div>

                        <p class="text-xs text-gray-400 mt-3">
                            Toko terdaftar
                        </p>

                    </div>

                    {{-- Total Penjualan --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5">

                        <div class="flex items-center justify-between">

                            <div>
                                <p class="text-sm text-gray-500 font-medium">
                                    Total Penjualan
                                </p>

                                <h3 class="text-2xl font-bold text-blue-600 mt-2">
                                    Rp {{ number_format($totalUang, 0, ',', '.') }}
                                </h3>
                            </div>

                            <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center">
                                <i data-lucide="wallet" class="w-6 h-6 text-blue-600"></i>
                            </div>

                        </div>

                        <p class="text-xs text-gray-400 mt-3">
                            Setoran ter-ACC
                        </p>

                    </div>

                </div>

                {{-- RIWAYAT --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

                    {{-- HEADER --}}
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-3">

                        <div class="w-10 h-10 rounded-2xl bg-blue-100 flex items-center justify-center">
                            <i data-lucide="history" class="w-5 h-5 text-blue-600"></i>
                        </div>

                        <div>
                            <h3 class="font-bold text-gray-800">
                                Riwayat Setoran Terbaru
                            </h3>

                            <p class="text-xs text-gray-400 mt-0.5">
                                Data setoran terakhir sales
                            </p>
                        </div>

                    </div>

                    {{-- LIST --}}
                    <div class="divide-y divide-gray-100">

                        @forelse($riwayatSetoran as $setoran)

                                        <div class="px-6 py-4 flex items-center justify-between gap-4">

                                            {{-- LEFT --}}
                                            <div class="flex items-center gap-4 min-w-0">

                                                <div
                                                    class="w-11 h-11 rounded-2xl bg-gray-100 flex items-center justify-center flex-shrink-0">
                                                    <i data-lucide="receipt-text" class="w-5 h-5 text-gray-600"></i>
                                                </div>

                                                <div class="min-w-0">

                                                    <p class="text-sm font-semibold text-gray-800">
                                                        {{ $setoran->tanggal->format('d M Y') }}
                                                    </p>

                                                    <p class="text-xs text-gray-400 mt-1">
                                                        {{ $setoran->detail->count() }} toko
                                                        •
                                                        {{ $setoran->totalTerjual() }} unit
                                                    </p>

                                                </div>

                                            </div>

                                            {{-- RIGHT --}}
                                            <div class="text-right flex-shrink-0">

                                                <p class="text-sm font-bold text-gray-800">
                                                    Rp {{ number_format($setoran->totalUang(), 0, ',', '.') }}
                                                </p>

                                                <span class="inline-flex items-center gap-1.5 mt-2 px-3 py-1 rounded-full text-xs font-semibold
                                                    {{ $setoran->status === 'acc'
                            ? 'bg-blue-100 text-blue-700'
                            : ($setoran->status === 'dikirim'
                                ? 'bg-yellow-100 text-yellow-700'
                                : 'bg-gray-100 text-gray-600') }}">

                                                    <div class="w-1.5 h-1.5 rounded-full
                                                        {{ $setoran->status === 'acc'
                            ? 'bg-blue-500'
                            : ($setoran->status === 'dikirim'
                                ? 'bg-yellow-500'
                                : 'bg-gray-500') }}">
                                                    </div>

                                                    {{ strtoupper($setoran->status) }}

                                                </span>

                                            </div>

                                        </div>

                        @empty

                            <div class="px-6 py-14 text-center">

                                <div class="w-16 h-16 mx-auto rounded-3xl bg-gray-100 flex items-center justify-center mb-4">
                                    <i data-lucide="inbox" class="w-8 h-8 text-gray-400"></i>
                                </div>

                                <p class="text-sm text-gray-500">
                                    Belum ada riwayat setoran
                                </p>

                            </div>

                        @endforelse

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection