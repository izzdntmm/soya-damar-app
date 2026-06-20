@extends('admin.layouts.app')

@section('title', 'Detail Setoran')

@section('page-title')
    <div class="flex items-center gap-2">
        <span>Detail Setoran</span>
    </div>
@endsection

@section('page-subtitle', $setoran->sales->nama . ' — ' . $setoran->tanggal->translatedFormat('d F Y'))

@section('content')

    <div class="max-w-7xl">

        {{-- Tombol Kembali --}}
        <a href="{{ route('admin.setoran.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition mb-6 font-medium">

            <i data-lucide="arrow-left" class="w-4 h-4"></i>

            <span>Kembali ke Daftar Setoran</span>

        </a>

        {{-- ══ TIMELINE STATUS ══════════════════════════ --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-3 sm:p-6 mb-5">

            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-5">
                Alur Status Setoran
            </p>

            <div class="flex items-start">

                {{-- Step 1 --}}
                <div class="flex flex-col items-center flex-1">

                    <div class="w-8 h-8 sm:w-11 sm:h-11 rounded-full flex items-center justify-center
                            {{ in_array($setoran->status, ['draft', 'dikirim', 'acc'])
        ? 'bg-blue-500 text-white'
        : 'bg-gray-200 text-gray-400' }}">

                        @if(in_array($setoran->status, ['dikirim', 'acc']))
                            <i data-lucide="check" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                        @else
                            <span class="font-bold text-sm">1</span>
                        @endif

                    </div>

                    <p class="text-xs font-semibold mt-2
                            {{ in_array($setoran->status, ['draft', 'dikirim', 'acc'])
        ? 'text-blue-600'
        : 'text-gray-400' }}">
                        Draft
                    </p>

                    <p class="text-[11px] text-gray-400 mt-0.5 text-center">
                        {{ $setoran->created_at->format('d M, H:i') }}
                    </p>

                </div>

                {{-- Garis --}}
                <div class="flex-1 h-0.5 rounded mt-4 sm:mt-5
                        {{ in_array($setoran->status, ['dikirim', 'acc'])
        ? 'bg-blue-400'
        : 'bg-gray-200' }}">
                </div>

                {{-- Step 2 --}}
                <div class="flex flex-col items-center flex-1">

                    <div class="w-8 h-8 sm:w-11 sm:h-11 rounded-full flex items-center justify-center
                            {{ in_array($setoran->status, ['dikirim', 'acc'])
        ? 'bg-blue-500 text-white'
        : 'bg-gray-200 text-gray-400' }}">

                        @if($setoran->status === 'acc')
                            <i data-lucide="check" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                        @else
                            <span class="font-bold text-sm">2</span>
                        @endif

                    </div>

                    <p class="text-xs font-semibold mt-2
                            {{ in_array($setoran->status, ['dikirim', 'acc'])
        ? 'text-blue-600'
        : 'text-gray-400' }}">
                        Dikirim
                    </p>

                    <p class="text-[11px] text-gray-400 mt-0.5 text-center">
                        {{ $setoran->dikirim_at ? $setoran->dikirim_at->format('d M, H:i') : '-' }}
                    </p>

                </div>

                {{-- Garis --}}
                <div class="flex-1 h-1 rounded mt-5
                        {{ $setoran->status === 'acc'
        ? 'bg-blue-400'
        : 'bg-gray-200' }}">
                </div>

                {{-- Step 3 --}}
                <div class="flex flex-col items-center flex-1">

                    <div class="w-8 h-8 sm:w-11 sm:h-11 rounded-full flex items-center justify-center
                            {{ $setoran->status === 'acc'
        ? 'bg-blue-600 text-white'
        : 'bg-gray-200 text-gray-400' }}">

                        @if($setoran->status === 'acc')
                            <i data-lucide="check-check" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                        @else
                            <span class="font-bold text-xs sm:text-sm">3</span>
                        @endif

                    </div>

                    <p class="text-[11px] sm:text-xs font-semibold mt-1.5
                            {{ $setoran->status === 'acc'
        ? 'text-blue-700'
        : 'text-gray-400' }}">
                        Disetujui
                    </p>

                    <p class="text-[9px] sm:text-[11px] text-gray-400 mt-0.5 text-center leading-tight">
                        {{ $setoran->acc_at ? $setoran->acc_at->format('d M, H:i') : '-' }}
                    </p>

                </div>

            </div>

        </div>

        {{-- GRID --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-5">

            {{-- INFO SALES --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">

                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-4">
                    Informasi Sales
                </p>

                <div class="flex items-center gap-3 mb-5">

                    <div
                        class="w-14 h-14 rounded-full bg-blue-100 text-blue-700 font-bold text-lg flex items-center justify-center">
                        {{ strtoupper(substr($setoran->sales->nama, 0, 1)) }}
                    </div>

                    <div class="min-w-0">
                        <p class="font-bold text-gray-800 truncate">
                            {{ $setoran->sales->nama }}
                        </p>

                        <p class="text-xs text-gray-400 break-all">
                            {{ $setoran->sales->email }}
                        </p>
                    </div>

                </div>

                <div class="space-y-3 text-sm">

                    <div class="flex items-start gap-3 text-gray-600">
                        <i data-lucide="smartphone" class="w-4 h-4 mt-0.5 text-gray-400"></i>
                        <span>{{ $setoran->sales->no_hp ?? 'Belum diisi' }}</span>
                    </div>

                    <div class="flex items-start gap-3 text-gray-600">
                        <i data-lucide="map-pin" class="w-4 h-4 mt-0.5 text-gray-400"></i>
                        <span>{{ $setoran->sales->alamat ?? 'Belum diisi' }}</span>
                    </div>

                </div>

            </div>

            {{-- RINGKASAN --}}
            <div class="xl:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-3">

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-400 font-medium">Toko</p>
                            <p class="text-3xl font-bold text-blue-600 mt-1">
                                {{ $setoran->detail->count() }}
                            </p>
                        </div>

                        <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center">
                            <i data-lucide="store" class="w-5 h-5 text-blue-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-400 font-medium">Unit Terjual</p>
                            <p class="text-3xl font-bold text-gray-700 mt-1">
                                {{ $setoran->totalTerjual() }}
                            </p>
                        </div>

                        <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center">
                            <i data-lucide="package" class="w-5 h-5 text-gray-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-400 font-medium">Total Uang</p>
                            <p class="text-xl font-bold text-blue-600 mt-1 break-all">
                                Rp {{ number_format($setoran->totalUang(), 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center">
                            <i data-lucide="wallet" class="w-5 h-5 text-blue-600"></i>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        {{-- DETAIL TOKO --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-5">

            <div
                class="px-4 sm:px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">

                <div class="flex items-center gap-2">
                    <i data-lucide="store" class="w-5 h-5 text-blue-600"></i>
                    <h3 class="font-bold text-gray-800">
                        Detail Per Toko
                    </h3>
                </div>

                <span class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-semibold w-fit">
                    {{ $setoran->detail->count() }} toko
                </span>

            </div>

            {{-- Desktop Table --}}
            <div class="hidden lg:block overflow-x-auto">

                <table class="w-full text-sm">

                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase">#</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-400 uppercase">Nama Toko</th>
                            <th class="text-center px-6 py-3 text-xs font-semibold text-gray-400 uppercase">Jumlah</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-gray-400 uppercase">Harga</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-gray-400 uppercase">Total</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50">

                        @foreach($setoran->detail as $i => $detail)

                            <tr class="hover:bg-gray-50 transition">

                                <td class="px-6 py-4 text-gray-400 text-xs">
                                    {{ $i + 1 }}
                                </td>

                                <td class="px-6 py-4">

                                    <p class="font-semibold text-gray-800">
                                        {{ $detail->toko->nama_toko }}
                                    </p>

                                    @if($detail->toko->alamat)
                                        <div class="flex items-start gap-1.5 mt-1">
                                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-gray-400 mt-0.5"></i>

                                            <p class="text-xs text-gray-400 truncate max-w-xs">
                                                {{ $detail->toko->alamat }}
                                            </p>
                                        </div>
                                    @endif

                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="font-bold text-gray-800">
                                        {{ $detail->jumlah_terjual }}
                                    </span>
                                    <span class="text-gray-400 text-xs">
                                        unit
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-right text-gray-600">
                                    Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <span class="font-bold text-gray-800">
                                        Rp {{ number_format($detail->total_uang, 0, ',', '.') }}
                                    </span>
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                    <tfoot>

                        <tr class="bg-blue-50 border-t-2 border-blue-200">

                            <td colspan="2" class="px-6 py-4 font-bold text-gray-700">
                                TOTAL KESELURUHAN
                            </td>

                            <td class="px-6 py-4 text-center font-bold text-gray-800">
                                {{ $setoran->totalTerjual() }} unit
                            </td>

                            <td></td>

                            <td class="px-6 py-4 text-right font-bold text-blue-700 text-base">
                                Rp {{ number_format($setoran->totalUang(), 0, ',', '.') }}
                            </td>

                        </tr>

                    </tfoot>

                </table>

            </div>

            {{-- Mobile Card --}}
            <div class="lg:hidden divide-y divide-gray-100">

                @foreach($setoran->detail as $i => $detail)

                    <div class="p-4">

                        <div class="flex items-start justify-between gap-3">

                            <div class="min-w-0 flex-1">

                                <div class="flex items-center gap-2 mb-1">
                                    <span
                                        class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 text-xs font-bold flex items-center justify-center">
                                        {{ $i + 1 }}
                                    </span>

                                    <p class="font-semibold text-gray-800 truncate">
                                        {{ $detail->toko->nama_toko }}
                                    </p>
                                </div>

                                @if($detail->toko->alamat)
                                    <div class="flex items-start gap-1.5 mt-1">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-gray-400 mt-0.5"></i>

                                        <p class="text-xs text-gray-400">
                                            {{ $detail->toko->alamat }}
                                        </p>
                                    </div>
                                @endif

                            </div>

                            <div class="text-right">
                                <p class="font-bold text-gray-800">
                                    {{ $detail->jumlah_terjual }}
                                    <span class="text-xs text-gray-400">unit</span>
                                </p>
                            </div>

                        </div>

                        <div class="grid grid-cols-2 gap-3 mt-4">

                            <div class="bg-gray-50 rounded-xl p-3">
                                <p class="text-xs text-gray-400 mb-1">Harga</p>

                                <p class="font-semibold text-gray-700 text-sm break-all">
                                    Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="bg-blue-50 rounded-xl p-3">
                                <p class="text-xs text-blue-600 mb-1">Total</p>

                                <p class="font-bold text-blue-700 text-sm break-all">
                                    Rp {{ number_format($detail->total_uang, 0, ',', '.') }}
                                </p>
                            </div>

                        </div>

                    </div>

                @endforeach

                {{-- TOTAL --}}
                <div class="p-4 bg-blue-50 border-t border-blue-200">

                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm font-bold text-gray-700">
                                TOTAL KESELURUHAN
                            </p>

                            <p class="text-xs text-gray-500 mt-1">
                                {{ $setoran->totalTerjual() }} unit terjual
                            </p>
                        </div>

                        <p class="text-lg font-bold text-blue-700">
                            Rp {{ number_format($setoran->totalUang(), 0, ',', '.') }}
                        </p>

                    </div>

                </div>

            </div>

        </div>

        {{-- AKSI --}}
        @if($setoran->status === 'dikirim')

            <div class="bg-white rounded-2xl border border-yellow-200 shadow-sm p-5 sm:p-6">

                <div class="flex items-start gap-4 mb-5">

                    <div class="w-14 h-14 rounded-2xl bg-yellow-100 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="alert-triangle" class="w-7 h-7 text-yellow-600"></i>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-800">
                            Tindakan Diperlukan
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Setoran ini sedang menunggu konfirmasi dari Anda.
                            Periksa detail di atas sebelum mengambil keputusan.
                        </p>
                    </div>

                </div>

                <div class="flex flex-col sm:flex-row gap-3">

                    {{-- ACC --}}
                    <form method="POST" action="{{ route('admin.setoran.acc', $setoran) }}" class="flex-1"
                        onsubmit="return confirmAcc()">

                        @csrf

                        <button type="submit"
                            class="w-full py-3.5 bg-blue-600 text-white rounded-2xl font-bold text-base hover:bg-blue-700 transition shadow-sm flex items-center justify-center gap-2">

                            <i data-lucide="badge-check" class="w-5 h-5"></i>

                            Konfirmasi & Setujui Setoran

                        </button>

                    </form>

                    {{-- TOLAK --}}
                    <form method="POST" action="{{ route('admin.setoran.tolak', $setoran) }}" class="flex-1"
                        onsubmit="return confirmTolak()">

                        @csrf

                        <button type="submit"
                            class="w-full py-3.5 bg-red-50 text-red-600 border border-red-200 rounded-2xl font-bold text-base hover:bg-red-100 transition flex items-center justify-center gap-2">

                            <i data-lucide="rotate-ccw" class="w-5 h-5"></i>

                            Kembalikan ke Draft

                        </button>

                    </form>

                </div>

                <p class="text-xs text-gray-400 mt-3 text-center">
                    "Kembalikan ke Draft" akan memberi kesempatan sales untuk mengedit ulang setorannya.
                </p>

            </div>

        @elseif($setoran->status === 'acc')

            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 flex items-start gap-4">

                <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="Check" class="w-7 h-7 text-blue-600"></i>
                </div>

                <div>

                    <p class="font-bold text-blue-800">
                        Setoran Sudah Disetujui
                    </p>

                    <p class="text-sm text-blue-700 mt-1">
                        Dikonfirmasi pada
                        {{ $setoran->acc_at->translatedFormat('l, d F Y') }}
                        pukul {{ $setoran->acc_at->format('H:i') }}.
                    </p>

                </div>

            </div>

        @else

            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5 flex items-start gap-4">

                <div class="w-14 h-14 rounded-2xl bg-gray-200 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="file-edit" class="w-7 h-7 text-gray-500"></i>
                </div>

                <div>

                    <p class="font-bold text-gray-700">
                        Setoran Masih Draft
                    </p>

                    <p class="text-sm text-gray-500 mt-1">
                        Sales belum mengirim laporan ini. Tidak ada tindakan yang diperlukan.
                    </p>

                </div>

            </div>

        @endif

    </div>

@endsection

@push('scripts')
    <script>

        function confirmAcc() {
            return confirm(
                'Konfirmasi Setoran?\n\n' +
                'Setoran dari {{ $setoran->sales->nama }} ' +
                'tanggal {{ $setoran->tanggal->format("d M Y") }} ' +
                'senilai Rp {{ number_format($setoran->totalUang(), 0, ",", ".") }} ' +
                'akan disetujui.'
            );
        }

        function confirmTolak() {
            return confirm(
                'Kembalikan ke Draft?\n\n' +
                'Sales akan bisa mengedit ulang setoran ini. ' +
                'Lanjutkan?'
            );
        }

        lucide.createIcons();

    </script>
@endpush