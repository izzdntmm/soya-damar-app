@extends('admin.layouts.app')

@section('title', 'Laporan Setoran')

@section('page-title')
    <div class="flex items-center gap-2">
        <span>Laporan Setoran</span>
    </div>
@endsection

@section('page-subtitle', 'Kelola dan konfirmasi semua setoran dari sales')

@section('content')

    {{-- Tombol Export --}}
    <div class="flex flex-wrap items-center gap-3 mb-5">
        <span class="text-sm text-gray-500 font-medium">Export:</span>

        <a href="{{ route('admin.export.setoran.excel', [
            'mulai' => $tanggal ?? now()->startOfMonth()->format('Y-m-d'),
            'akhir' => $tanggal ?? now()->endOfMonth()->format('Y-m-d'),
            'sales_id' => $salesId,
        ]) }}"
            class="flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 transition">
            <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
            Excel
        </a>

        <a href="{{ route('admin.export.setoran.pdf', [
            'mulai' => $tanggal ?? now()->startOfMonth()->format('Y-m-d'),
            'akhir' => $tanggal ?? now()->endOfMonth()->format('Y-m-d'),
            'sales_id' => $salesId,
        ]) }}"
            class="flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700 transition">
            <i data-lucide="file-text" class="w-4 h-4"></i>
            PDF
        </a>
    </div>

    {{-- ══ KARTU RINGKASAN ══════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6" id="realtime-ringkasan">

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">
                        Total Setoran
                    </p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">
                        {{ $ringkasan['semua'] }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Semua waktu</p>
                </div>

                <div class="w-11 h-11 rounded-2xl bg-blue-100 flex items-center justify-center">
                    <i data-lucide="wallet" class="w-5 h-5 text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">
                        Draft
                    </p>
                    <p class="text-3xl font-bold text-gray-500 mt-2">
                        {{ $ringkasan['draft'] }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Belum dikirim</p>
                </div>

                <div class="w-11 h-11 rounded-2xl bg-gray-100 flex items-center justify-center">
                    <i data-lucide="file-edit" class="w-5 h-5 text-gray-500"></i>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 rounded-2xl border border-yellow-100 shadow-sm p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-yellow-600 uppercase tracking-wide">
                        Menunggu ACC
                    </p>
                    <p class="text-3xl font-bold text-yellow-600 mt-2">
                        {{ $ringkasan['dikirim'] }}
                    </p>
                    <p class="text-xs text-yellow-500 mt-1">Perlu konfirmasi</p>
                </div>

                <div class="w-11 h-11 rounded-2xl bg-yellow-100 flex items-center justify-center">
                    <i data-lucide="clock-3" class="w-5 h-5 text-yellow-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 rounded-2xl border border-blue-100 shadow-sm p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide">
                        Disetujui
                    </p>
                    <p class="text-3xl font-bold text-blue-600 mt-2">
                        {{ $ringkasan['acc'] }}
                    </p>
                    <p class="text-xs text-blue-500 mt-1">Sudah dikonfirmasi</p>
                </div>

                <div class="w-11 h-11 rounded-2xl bg-blue-100 flex items-center justify-center">
                    <i data-lucide="badge-check" class="w-5 h-5 text-blue-600"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- ══ FILTER ════════════════════════════════════ --}}
    <form method="GET" action="{{ route('admin.setoran.index') }}"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">

        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">
            Filter Setoran
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3">

            {{-- Filter Tanggal --}}
            <div>
                <label class="block text-xs text-gray-500 mb-1">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}"
                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            {{-- Filter Status --}}
            <div>
                <label class="block text-xs text-gray-500 mb-1">Status</label>
                <select name="status"
                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>
                        Draft
                    </option>
                    <option value="dikirim" {{ $status === 'dikirim' ? 'selected' : '' }}>
                        Menunggu ACC
                    </option>
                    <option value="acc" {{ $status === 'acc' ? 'selected' : '' }}>
                        Disetujui
                    </option>
                </select>
            </div>

            {{-- Filter Sales --}}
            <div>
                <label class="block text-xs text-gray-500 mb-1">Sales</label>
                <select name="sales_id"
                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="">Semua Sales</option>

                    @foreach($listSales as $s)
                        <option value="{{ $s->id }}" {{ $salesId == $s->id ? 'selected' : '' }}>
                            {{ $s->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tombol --}}
            <div class="flex items-end gap-2">
                <button type="submit"
                    class="flex-1 flex items-center justify-center gap-2 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    Filter
                </button>

                @if($tanggal || $status || $salesId)
                    <a href="{{ route('admin.setoran.index') }}"
                        class="px-3 py-2.5 bg-gray-100 text-gray-500 rounded-xl hover:bg-gray-200 transition">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </a>
                @endif
            </div>

        </div>
    </form>

    <div id="realtime-admin-setoran"
         x-data
         x-init="setInterval(() => {
            // Kita beri proteksi: JANGAN REFRESH jika Admin sedang memilih/mencentang data untuk ACC massal
            const anyChecked = document.querySelectorAll('.check-item:checked').length > 0;
            
            if (!anyChecked) {
                fetch(window.location.href)
                    .then(res => res.text())
                    .then(html => {
                        let parser = new DOMParser();
                        let doc = parser.parseFromString(html, 'text/html');
                        
                        // 1. Refresh Kartu Ringkasan Statistik Angka di atas
                        document.getElementById('realtime-ringkasan').innerHTML = doc.getElementById('realtime-ringkasan').innerHTML;
                        
                        // 2. Refresh isi Tabel Desktop (Tbody saja agar checkbox master tidak terganggu)
                        document.getElementById('realtime-tbody-desktop').innerHTML = doc.getElementById('realtime-tbody-desktop').innerHTML;
                        
                        // 3. Refresh isi Card List Mobile
                        document.getElementById('realtime-list-mobile').innerHTML = doc.getElementById('realtime-list-mobile').innerHTML;
                        
                        // Bangkitkan kembali icon lucide yang baru dimuat
                        lucide.createIcons();
                    });
            }
         }, 4000)"> {{-- ══ FORM ACC MASSAL ═══════════════════════════ --}}
    <form method="POST"
        action="{{ route('admin.setoran.acc-massal') }}"
        id="formMassal"
        onsubmit="return confirmAccMassal()">

        @csrf

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

            {{-- Toolbar --}}
            <div
                class="px-4 sm:px-6 py-3 border-b border-gray-100 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between bg-gray-50">

                <div class="flex items-center gap-3">
                    <input type="checkbox" id="checkAll"
                        class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-400"
                        onchange="toggleCheckAll(this)">

                    <label for="checkAll"
                        class="text-sm text-gray-600 font-medium cursor-pointer">
                        Pilih Semua
                    </label>
                </div>

                <button type="submit"
                    class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                    <i data-lucide="check-check" class="w-4 h-4"></i>
                    ACC Semua Terpilih
                </button>
            </div>

            {{-- Desktop Table --}}
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="w-10 px-4 py-4"></th>
                            <th class="text-left px-4 py-4 text-gray-500 font-semibold text-xs uppercase">Sales</th>
                            <th class="text-left px-4 py-4 text-gray-500 font-semibold text-xs uppercase">Tanggal</th>
                            <th class="text-center px-4 py-4 text-gray-500 font-semibold text-xs uppercase">Toko</th>
                            <th class="text-center px-4 py-4 text-gray-500 font-semibold text-xs uppercase">Unit</th>
                            <th class="text-right px-4 py-4 text-gray-500 font-semibold text-xs uppercase">Total</th>
                            <th class="text-center px-4 py-4 text-gray-500 font-semibold text-xs uppercase">Status</th>
                            <th class="text-center px-4 py-4 text-gray-500 font-semibold text-xs uppercase">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50" id="realtime-tbody-desktop">

                        @forelse($setoran as $item)
                            <tr class="hover:bg-gray-50 transition
                                {{ $item->status === 'dikirim' ? 'bg-yellow-50/30' : '' }}">

                                {{-- Checkbox --}}
                                <td class="px-4 py-4">
                                    @if($item->status === 'dikirim')
                                        <input type="checkbox"
                                            name="setoran_ids[]"
                                            value="{{ $item->id }}"
                                            class="check-item w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-400">
                                    @endif
                                </td>

                                {{-- Sales --}}
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 font-bold text-sm flex items-center justify-center flex-shrink-0">
                                            {{ strtoupper(substr($item->sales->nama, 0, 1)) }}
                                        </div>

                                        <div>
                                            <p class="font-semibold text-gray-800">
                                                {{ $item->sales->nama }}
                                            </p>

                                            <p class="text-xs text-gray-400">
                                                {{ $item->sales->no_hp ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Tanggal --}}
                                <td class="px-4 py-4">
                                    <p class="font-medium text-gray-700">
                                        {{ $item->tanggal->translatedFormat('d M Y') }}
                                    </p>

                                    @if($item->dikirim_at)
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            Dikirim {{ $item->dikirim_at->diffForHumans() }}
                                        </p>
                                    @endif
                                </td>

                                {{-- Toko --}}
                                <td class="px-4 py-4 text-center">
                                    <span
                                        class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-bold text-sm inline-flex items-center justify-center">
                                        {{ $item->detail->count() }}
                                    </span>
                                </td>

                                {{-- Unit --}}
                                <td class="px-4 py-4 text-center font-medium text-gray-700">
                                    {{ $item->totalTerjual() }}
                                </td>

                                {{-- Total --}}
                                <td class="px-4 py-4 text-right">
                                    <p class="font-bold text-gray-800">
                                        Rp {{ number_format($item->totalUang(), 0, ',', '.') }}
                                    </p>
                                </td>

                                {{-- Status --}}
                                <td class="px-4 py-4 text-center">

                                    @if($item->status === 'acc')
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                            <i data-lucide="badge-check" class="w-3.5 h-3.5"></i>
                                            ACC
                                        </span>

                                    @elseif($item->status === 'dikirim')
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                            <i data-lucide="clock-3" class="w-3.5 h-3.5"></i>
                                            Menunggu
                                        </span>

                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">
                                            <i data-lucide="file-edit" class="w-3.5 h-3.5"></i>
                                            Draft
                                        </span>
                                    @endif

                                </td>

                                {{-- Aksi --}}
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-center gap-2">

                                        <a href="{{ route('admin.setoran.show', $item) }}"
                                            class="flex items-center gap-1.5 px-3 py-2 bg-blue-50 text-blue-600 rounded-xl text-xs font-medium hover:bg-blue-100 transition">

                                            Detail
                                        </a>

                                        @if($item->status === 'dikirim')
                                            <form method="POST"
                                                action="{{ route('admin.setoran.acc', $item) }}"
                                                onsubmit="return confirm('ACC setoran ini?')">

                                                @csrf

                                                <button type="submit"
                                                    class="flex items-center gap-1.5 px-3 py-2 bg-blue-50 text-blue-600 rounded-xl text-xs font-medium hover:bg-blue-100 transition">
                                                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                                    ACC
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="py-16 text-center text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                            <i data-lucide="inbox" class="w-10 h-10 text-gray-300"></i>
                                        </div>

                                        <p class="font-semibold text-gray-500">
                                            Tidak ada data setoran
                                        </p>

                                        <p class="text-sm mt-1">
                                            Coba ubah filter pencarian
                                        </p>
                                    </div>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Mobile Card --}}
            <div class="lg:hidden divide-y divide-gray-100" id="realtime-list-mobile">

                @forelse($setoran as $item)

                    <div class="p-4">

                        <div class="flex items-start justify-between gap-3">

                            <div class="flex items-start gap-3 flex-1 min-w-0">

                                @if($item->status === 'dikirim')
                                    <input type="checkbox"
                                        name="setoran_ids[]"
                                        value="{{ $item->id }}"
                                        class="check-item mt-1 w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-400">
                                @endif

                                <div
                                    class="w-11 h-11 rounded-full bg-blue-100 text-blue-700 font-bold text-sm flex items-center justify-center flex-shrink-0">
                                    {{ strtoupper(substr($item->sales->nama, 0, 1)) }}
                                </div>

                                <div class="min-w-0 flex-1">
                                    <p class="font-semibold text-gray-800 truncate">
                                        {{ $item->sales->nama }}
                                    </p>

                                    <p class="text-xs text-gray-400">
                                        {{ $item->tanggal->translatedFormat('d M Y') }}
                                    </p>

                                    <div class="flex items-center gap-4 mt-3 text-sm">

                                        <div>
                                            <p class="text-gray-400 text-xs">Toko</p>
                                            <p class="font-semibold text-gray-700">
                                                {{ $item->detail->count() }}
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-gray-400 text-xs">Unit</p>
                                            <p class="font-semibold text-gray-700">
                                                {{ $item->totalTerjual() }}
                                            </p>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div class="text-right flex-shrink-0">
                                <p class="font-bold text-gray-800 text-sm">
                                    Rp {{ number_format($item->totalUang(), 0, ',', '.') }}
                                </p>

                                <div class="mt-2">

                                    @if($item->status === 'acc')
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-blue-100 text-blue-700">
                                            <i data-lucide="badge-check" class="w-3 h-3"></i>
                                            ACC
                                        </span>

                                    @elseif($item->status === 'dikirim')
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-yellow-100 text-yellow-700">
                                            <i data-lucide="clock-3" class="w-3 h-3"></i>
                                            Menunggu
                                        </span>

                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-500">
                                            <i data-lucide="file-edit" class="w-3 h-3"></i>
                                            Draft
                                        </span>
                                    @endif

                                </div>
                            </div>

                        </div>

                        <div class="flex gap-2 mt-4">

                            <a href="{{ route('admin.setoran.show', $item) }}"
                                class="flex-1 flex items-center justify-center gap-2 py-2 bg-blue-50 text-blue-600 rounded-xl text-sm font-medium hover:bg-blue-100 transition">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                                Detail
                            </a>

                            @if($item->status === 'dikirim')
                                <form method="POST"
                                    action="{{ route('admin.setoran.acc', $item) }}"
                                    class="flex-1"
                                    onsubmit="return confirm('ACC setoran ini?')">

                                    @csrf

                                    <button type="submit"
                                        class="w-full flex items-center justify-center gap-2 py-2 bg-blue-50 text-blue-600 rounded-xl text-sm font-medium hover:bg-blue-100 transition">
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                        ACC
                                    </button>
                                </form>
                            @endif

                        </div>

                    </div>

                @empty

                    <div class="py-16 text-center text-gray-400">
                        <div
                            class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="inbox" class="w-10 h-10 text-gray-300"></i>
                        </div>

                        <p class="font-semibold text-gray-500">
                            Tidak ada data setoran
                        </p>

                        <p class="text-sm mt-1">
                            Coba ubah filter pencarian
                        </p>
                    </div>

                @endforelse

            </div>

            {{-- Pagination --}}
            @if($setoran->hasPages())
                <div class="px-4 sm:px-6 py-4 border-t border-gray-100 overflow-x-auto">
                    {{ $setoran->appends([
                        'tanggal' => $tanggal,
                        'status' => $status,
                        'sales_id' => $salesId,
                    ])->links() }}
                </div>
            @endif

        </div>

    </form>

</div>

@endsection

@push('scripts')
    <script>

        // Toggle semua checkbox
        function toggleCheckAll(master) {
            document.querySelectorAll('.check-item')
                .forEach(cb => cb.checked = master.checked);
        }

        // Konfirmasi ACC massal
        function confirmAccMassal() {
            const checked = document.querySelectorAll('.check-item:checked').length;

            if (checked === 0) {
                alert('Pilih minimal 1 setoran terlebih dahulu.');
                return false;
            }

            return confirm(`ACC ${checked} setoran sekaligus?`);
        }

        // Render Lucide Icon
        lucide.createIcons();

    </script>
@endpush