@extends('admin.layouts.app')

@section('title', 'Detail Toko')

@section('page-title')
    <div class="flex items-center gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-800">
                Detail Toko
            </h1>
        </div>

    </div>
@endsection



@section('content')

    <div class="max-w-6xl">

        {{-- BACK --}}
        <a href="{{ route('admin.toko.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition mb-6 font-medium">

            <i data-lucide="arrow-left" class="w-4 h-4"></i>

            <span>Kembali ke Daftar Toko</span>

        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT --}}
            <div class="space-y-6">

                {{-- INFO TOKO --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">

                    {{-- ICON --}}
                    <div class="w-20 h-20 rounded-3xl bg-blue-100 flex items-center justify-center mx-auto mb-5 shadow-sm">

                        <i data-lucide="store" class="w-10 h-10 text-blue-600"></i>

                    </div>

                    {{-- TITLE --}}
                    <div class="text-center">

                        <h2 class="text-xl font-bold text-gray-800">
                            {{ $toko->nama_toko }}
                        </h2>

                        <p class="text-sm text-gray-400 mt-1">
                            Informasi detail toko
                        </p>

                    </div>

                    {{-- INFO --}}
                    <div class="mt-7 space-y-4">

                        {{-- PHONE --}}
                        <div class="flex items-start gap-3">

                            <div class="w-10 h-10 rounded-2xl bg-blue-50 flex items-center justify-center flex-shrink-0">

                                <i data-lucide="smartphone" class="w-4 h-4 text-blue-600"></i>

                            </div>

                            <div>

                                <p class="text-xs text-gray-400 mb-1">
                                    Nomor HP
                                </p>

                                <p class="text-sm font-medium text-gray-700">
                                    {{ $toko->no_hp ?? 'Belum diisi' }}
                                </p>

                            </div>

                        </div>

                        {{-- ADDRESS --}}
                        <div class="flex items-start gap-3">

                            <div class="w-10 h-10 rounded-2xl bg-blue-50 flex items-center justify-center flex-shrink-0">

                                <i data-lucide="map-pin" class="w-4 h-4 text-blue-500"></i>

                            </div>

                            <div>

                                <p class="text-xs text-gray-400 mb-1">
                                    Alamat
                                </p>

                                <p class="text-sm font-medium text-gray-700 leading-relaxed">
                                    {{ $toko->alamat ?? 'Belum diisi' }}
                                </p>

                            </div>

                        </div>

                        {{-- SALES --}}
                        <div class="flex items-start gap-3">

                            <div class="w-10 h-10 rounded-2xl bg-blue-50 flex items-center justify-center flex-shrink-0">

                                <i data-lucide="user-round" class="w-4 h-4 text-blue-600"></i>

                            </div>

                            <div>

                                <p class="text-xs text-gray-400 mb-1">
                                    Sales
                                </p>

                                <p class="text-sm font-semibold text-gray-700">
                                    {{ $toko->sales->nama }}
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- STATS --}}
                <div class="grid grid-cols-2 gap-4">

                    {{-- TOTAL TERJUAL --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5 text-center">

                        <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center mx-auto mb-3">

                            <i data-lucide="package-check" class="w-6 h-6 text-blue-600"></i>

                        </div>

                        <h3 class="text-xl font-bold text-blue-600">
                            {{ $totalTerjual }}
                        </h3>

                        <p class="text-xs text-gray-400 mt-2">
                            Total Terjual
                        </p>

                    </div>

                    {{-- TOTAL UANG --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5 text-center">

                        <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center mx-auto mb-3">

                            <i data-lucide="wallet" class="w-6 h-6 text-blue-600"></i>

                        </div>

                        <h3 class="text-xl font-bold text-blue-600">
                            {{ number_format($totalUang / 1000, 0, ',', '.') }}k
                        </h3>

                        <p class="text-xs text-gray-400 mt-2">
                            Total Uang
                        </p>

                    </div>

                </div>

            </div>

            {{-- RIGHT --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- MAP --}}
                @if($toko->latitude && $toko->longitude)

                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

                        {{-- HEADER --}}
                        <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-3">

                            <div class="w-11 h-11 rounded-2xl bg-blue-100 flex items-center justify-center">

                                <i data-lucide="map-pinned" class="w-5 h-5 text-blue-500"></i>

                            </div>

                            <div>

                                <h3 class="font-bold text-gray-800">
                                    Lokasi Toko
                                </h3>

                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ $toko->latitude }}, {{ $toko->longitude }}
                                </p>

                            </div>

                        </div>

                        {{-- MAP --}}
                        <div id="map-view" class="h-[320px]"></div>

                    </div>

                @else

                    {{-- EMPTY MAP --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 py-14 text-center">

                        <div class="w-20 h-20 rounded-3xl bg-gray-100 flex items-center justify-center mx-auto mb-5">

                            <i data-lucide="map" class="w-10 h-10 text-gray-400"></i>

                        </div>

                        <h3 class="text-lg font-bold text-gray-700">
                            Lokasi belum ditentukan
                        </h3>

                        <p class="text-sm text-gray-400 mt-2">
                            Toko belum memiliki titik koordinat
                        </p>

                    </div>

                @endif

                {{-- RIWAYAT --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

                    {{-- HEADER --}}
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-3">

                        <div class="w-11 h-11 rounded-2xl bg-blue-100 flex items-center justify-center">

                            <i data-lucide="history" class="w-5 h-5 text-blue-600"></i>

                        </div>

                        <div>

                            <h3 class="font-bold text-gray-800">
                                Riwayat Penjualan
                            </h3>

                            <p class="text-xs text-gray-400 mt-0.5">
                                Riwayat transaksi toko
                            </p>

                        </div>

                    </div>

                    {{-- LIST --}}
                    <div class="divide-y divide-gray-100">

                        @forelse($riwayat as $detail)

                            <div class="px-6 py-4 flex items-center justify-between gap-4">

                                {{-- LEFT --}}
                                <div class="flex items-center gap-4 min-w-0">

                                    <div
                                        class="w-11 h-11 rounded-2xl bg-gray-100 flex items-center justify-center flex-shrink-0">

                                        <i data-lucide="receipt-text" class="w-5 h-5 text-gray-600"></i>

                                    </div>

                                    <div class="min-w-0">

                                        <p class="text-sm font-semibold text-gray-800">
                                            {{ $detail->setoran->tanggal->format('d M Y') }}
                                        </p>

                                        <p class="text-xs text-gray-400 mt-1">
                                            {{ $detail->jumlah_terjual }} unit
                                            ×
                                            Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                        </p>

                                    </div>

                                </div>

                                {{-- RIGHT --}}
                                <div class="text-right flex-shrink-0">

                                    <p class="text-sm font-bold text-blue-700">
                                        Rp {{ number_format($detail->total_uang, 0, ',', '.') }}
                                    </p>

                                </div>

                            </div>

                        @empty

                            <div class="py-14 text-center">

                                <div class="w-16 h-16 rounded-3xl bg-gray-100 flex items-center justify-center mx-auto mb-4">

                                    <i data-lucide="inbox" class="w-8 h-8 text-gray-400"></i>

                                </div>

                                <p class="text-sm text-gray-500">
                                    Belum ada riwayat penjualan
                                </p>

                            </div>

                        @endforelse

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection

@if($toko->latitude && $toko->longitude)

    @push('scripts')
        <script>

            function initViewMap() {

                const lat = {{ $toko->latitude }};
                const lng = {{ $toko->longitude }};

                const map = new google.maps.Map(
                    document.getElementById('map-view'),
                    {
                        center: { lat, lng },
                        zoom: 16,
                        mapTypeControl: false,
                        streetViewControl: false,
                        fullscreenControl: false,
                    }
                );

                new google.maps.Marker({
                    position: { lat, lng },
                    map,
                    title: '{{ $toko->nama_toko }}',
                });

            }

        </script>

        <script async defer
            src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places&callback=initViewMap">
            </script>
    @endpush

@endif