@extends('sales.layouts.app')

@section('title', 'Setoran Harian')
@section('page-title', 'Setoran Harian')
@section('page-subtitle', 'Input penjualan hari ini — ' . now()->translatedFormat('l, d F Y'))

@section('content')

    {{-- Import Lucide Icons for clean look --}}
    <script src="https://unpkg.com/lucide@latest"></script>

<div id="realtime-sales-container"
     x-data
     x-init="setInterval(() => {
        if (!document.getElementById('form-input-setoran')) {
            fetch(window.location.href)
                .then(res => res.text())
                .then(html => {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(html, 'text/html');
                    document.getElementById('realtime-sales-content').innerHTML = doc.getElementById('realtime-sales-content').innerHTML;
                    lucide.createIcons();
                });
        }
     }, 4000)">

    <div id="realtime-sales-content" class="container mx-auto px-4 pb-20">
        {{-- ══════════════════════════════════════════ --}}
        {{-- KONDISI 1: BELUM ADA SETORAN HARI INI --}}
        {{-- ══════════════════════════════════════════ --}}
        @if(!$setoran)
            <div class="max-w-lg mx-auto mt-8 md:mt-12 text-center">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 md:p-10">
                    <div class="w-20 h-20 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="clipboard-list" class="w-10 h-10"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Setoran Hari Ini</h3>
                    <p class="text-gray-500 text-sm mb-8">
                        Klik tombol di bawah untuk memulai input setoran harian kamu.
                    </p>
                    <form method="POST" action="{{ route('sales.setoran.store') }}">
                        @csrf
                        <button type="submit"
                            class="w-full py-4 bg-blue-600 text-white rounded-2xl font-bold text-base hover:bg-blue-700 transition shadow-lg shadow-blue-100 flex items-center justify-center gap-2">
                            <i data-lucide="play" class="w-5 h-5"></i>
                            Mulai Setoran Hari Ini
                        </button>
                    </form>
                </div>

                @if($riwayat->count())
                    <div class="mt-10 text-left">
                        <div class="flex items-center gap-2 mb-4 px-1">
                            <i data-lucide="history" class="w-4 h-4 text-gray-400"></i>
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Setoran Terakhir</p>
                        </div>
                        <div class="space-y-3">
                            @foreach($riwayat as $r)
                                <div
                                    class="bg-white rounded-2xl border border-gray-100 p-4 flex items-center justify-between hover:border-blue-200 transition">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400">
                                            <i data-lucide="calendar" class="w-5 h-5"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-800">
                                                {{ $r->tanggal->translatedFormat('d M Y') }}
                                            </p>
                                            <p class="text-xs text-gray-400 flex items-center gap-1">
                                                <i data-lucide="store" class="w-3 h-3"></i> {{ $r->detail->count() }} toko
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-800">
                                            Rp {{ number_format($r->totalUang(), 0, ',', '.') }}
                                        </p>
                                        <span
                                            class="inline-block text-[10px] px-2 py-0.5 rounded-md font-bold mt-1
                                            {{ $r->status === 'acc' ? 'bg-blue-100 text-blue-700' :
                                ($r->status === 'dikirim' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-500') }}">
                                            {{ strtoupper($r->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <a href="{{ route('sales.setoran.riwayat') }}"
                            class="block text-center mt-5 text-sm font-semibold text-blue-600 hover:text-blue-700">
                            Lihat semua riwayat →
                        </a>
                    </div>
                @endif
            </div>

            {{-- ══════════════════════════════════════════ --}}
            {{-- KONDISI 2: SUDAH ADA SETORAN (DRAFT) --}}
            {{-- ══════════════════════════════════════════ --}}
        @elseif($setoran->status === 'draft')
            <div class="max-w-5xl mx-auto" x-data="setoranForm({{ $hargaSatuan }})" id="form-input-setoran">

                {{-- Status Bar (Mobile Friendly) --}}
                <div class="flex items-center justify-between mb-8 overflow-x-auto pb-2 gap-4">
                    <div class="flex items-center gap-2 shrink-0">
                        <div
                            class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold ring-4 ring-blue-100">
                            1</div>
                        <span class="text-xs font-bold text-blue-600 uppercase tracking-tight">Draft</span>
                    </div>
                    <div class="flex-1 h-1 bg-gray-100 rounded min-w-[40px]">
                        <div class="h-1 bg-blue-600 rounded w-1/2"></div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0 opacity-50">
                        <div
                            class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-xs font-bold">
                            2</div>
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-tight">Kirim</span>
                    </div>
                    <div class="flex-1 h-1 bg-gray-100 rounded min-w-[40px]"></div>
                    <div class="flex items-center gap-2 shrink-0 opacity-50">
                        <div
                            class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-xs font-bold">
                            3</div>
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-tight">Selesai</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    {{-- KOLOM KIRI: Form Input --}}
                    <div class="lg:col-span-4">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                            <div class="px-5 py-4 bg-blue-600 text-white flex items-center gap-3">
                                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                                <div>
                                    <h3 class="font-bold text-sm">Tambah Toko</h3>
                                    <p class="text-[10px] opacity-80 uppercase font-semibold">Harga: Rp
                                        {{ number_format($hargaSatuan, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('sales.setoran.detail.store', $setoran) }}"
                                class="p-5 space-y-4">
                                @csrf
                                <div>
                                    <label
                                        class="block text-[10px] font-bold text-gray-400 mb-1.5 uppercase tracking-widest">Pilih
                                        Toko</label>
                                    @if($listToko->count())
                                        <select name="toko_id" x-model="selectedToko"
                                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none appearance-none bg-gray-50">
                                            <option value="">-- Pilih Toko --</option>
                                            @foreach($listToko as $toko)
                                                <option value="{{ $toko->id }}">{{ $toko->nama_toko }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <div class="p-3 bg-red-50 text-red-600 rounded-xl text-xs flex items-center gap-2">
                                            <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                            <span>Belum ada toko. <a href="{{ route('sales.toko.create') }}"
                                                    class="underline font-bold">Tambah</a></span>
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <label
                                        class="block text-[10px] font-bold text-gray-400 mb-1.5 uppercase tracking-widest">Jumlah
                                        Terjual</label>
                                    <div class="relative">
                                        <input type="number" name="jumlah_terjual" x-model.number="jumlah" min="1"
                                            class="w-full pl-4 pr-12 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                                            placeholder="0">
                                        <span class="absolute right-4 top-3 text-gray-400 text-sm">Unit</span>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4 space-y-2 border border-dashed border-gray-200"
                                    x-show="jumlah > 0">
                                    <div class="flex justify-between text-xs text-gray-500">
                                        <span>Subtotal</span>
                                        <span x-text="'Rp ' + formatRupiah(jumlah * {{ $hargaSatuan }})"></span>
                                    </div>
                                </div>

                                <button type="submit" :disabled="!selectedToko || jumlah < 1"
                                    class="w-full py-3.5 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition disabled:opacity-30 flex items-center justify-center gap-2">
                                    <i data-lucide="plus" class="w-4 h-4"></i> Tambahkan
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- KOLOM KANAN: Daftar --}}
                    <div class="lg:col-span-8 space-y-6">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between bg-white">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="shopping-bag" class="w-5 h-5 text-gray-400"></i>
                                    <h3 class="font-bold text-gray-800 text-sm md:text-base">Daftar Setoran</h3>
                                </div>
                                <span class="text-[10px] bg-blue-50 text-blue-600 px-3 py-1 rounded-full font-bold uppercase">
                                    {{ $setoran->detail->count() }} Toko
                                </span>
                            </div>

                            @if($setoran->detail->count())
                                <div class="divide-y divide-gray-50">
                                    @foreach($setoran->detail as $detail)
                                        <div class="px-6 py-5 hover:bg-gray-50 transition"
                                            x-data="{ editing: false, jumlahEdit: {{ $detail->jumlah_terjual }} }">
                                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                                <div class="flex items-center gap-4">
                                                    <div
                                                        class="w-10 h-10 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center shrink-0">
                                                        <i data-lucide="store" class="w-5 h-5"></i>
                                                    </div>
                                                    <div>
                                                        <h4 class="font-bold text-gray-800 text-sm">{{ $detail->toko->nama_toko }}</h4>
                                                        <p class="text-xs text-gray-400">{{ $detail->jumlah_terjual }} unit × Rp
                                                            {{ number_format($detail->harga_satuan, 0, ',', '.') }}</p>
                                                    </div>
                                                </div>

                                                <div
                                                    class="flex items-center justify-between md:justify-end gap-4 border-t md:border-none pt-3 md:pt-0">
                                                    <div class="text-left md:text-right">
                                                        <p class="font-bold text-gray-800">Rp
                                                            {{ number_format($detail->total_uang, 0, ',', '.') }}</p>
                                                    </div>
                                                    <div class="flex gap-1">
                                                        <button @click="editing = !editing"
                                                            class="p-2 text-gray-400 hover:text-blue-600 transition">
                                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                                        </button>
                                                        <form method="POST"
                                                            action="{{ route('sales.setoran.detail.destroy', [$setoran, $detail]) }}"
                                                            onsubmit="return confirm('Hapus item ini?')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit"
                                                                class="p-2 text-gray-400 hover:text-red-500 transition">
                                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Edit Mode Popover Simple --}}
                                            <div x-show="editing" class="mt-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
                                                <form method="POST"
                                                    action="{{ route('sales.setoran.detail.update', [$setoran, $detail]) }}"
                                                    class="flex items-center gap-2">
                                                    @csrf @method('PUT')
                                                    <input type="number" name="jumlah_terjual" x-model.number="jumlahEdit"
                                                        class="w-20 px-3 py-2 border rounded-lg text-sm">
                                                    <button type="submit"
                                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold">Simpan</button>
                                                    <button type="button" @click="editing = false"
                                                        class="px-4 py-2 text-gray-500 text-xs">Batal</button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="px-6 py-6 bg-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
                                    <div class="text-center md:text-left">
                                        <p class="text-xs text-gray-400 uppercase font-bold tracking-widest">Total Keseluruhan</p>
                                        <p class="text-sm font-semibold text-gray-600">{{ $setoran->totalTerjual() }} Unit Terjual
                                        </p>
                                    </div>
                                    <p class="text-3xl font-black text-gray-800 tracking-tight">
                                        Rp {{ number_format($setoran->totalUang(), 0, ',', '.') }}
                                    </p>
                                </div>
                            @else
                                <div class="py-20 text-center">
                                    <i data-lucide="layers" class="w-12 h-12 text-gray-200 mx-auto mb-4"></i>
                                    <p class="text-gray-400 text-sm">Belum ada data toko.</p>
                                </div>
                            @endif
                        </div>

                        {{-- Kirim Laporan Card --}}
                        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-3xl p-6 md:p-8 text-white shadow-xl">
                            <div class="flex flex-col md:flex-row items-center gap-6">
                                <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center shrink-0">
                                    <i data-lucide="send" class="w-8 h-8 text-blue-400"></i>
                                </div>
                                <div class="flex-1 text-center md:text-left">
                                    <h3 class="font-bold text-lg mb-1">Kirim Laporan Sekarang?</h3>
                                    <p class="text-gray-400 text-xs leading-relaxed">Setelah dikirim, data akan dikunci untuk
                                        proses verifikasi admin. Pastikan angka sudah sesuai.</p>
                                </div>
                                <form method="POST" action="{{ route('sales.setoran.kirim', $setoran) }}"
                                    onsubmit="return confirmKirim()" class="w-full md:w-auto">
                                    @csrf
                                    <button type="submit" {{ $setoran->detail->count() === 0 ? 'disabled' : '' }}
                                        class="w-full md:w-auto px-8 py-4 bg-blue-600 hover:bg-blue-500 text-white rounded-2xl font-bold transition shadow-lg shadow-blue-900/20 disabled:opacity-20 flex items-center justify-center gap-2">
                                        Kirim Laporan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════ --}}
            {{-- KONDISI 3: DIKIRIM & KONDISI 4: ACC --}}
            {{-- ══════════════════════════════════════════ --}}
        @else
            <div class="max-w-2xl mx-auto">
                {{-- Status Bar Fixed --}}
                <div class="flex items-center gap-3 mb-8">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full bg-blue-500 text-white flex items-center justify-center"><i
                                data-lucide="check" class="w-4 h-4"></i></div>
                    </div>
                    <div class="flex-1 h-1 bg-blue-500 rounded"></div>
                    <div class="flex items-center gap-2">
                        <div
                            class="w-7 h-7 rounded-full {{ $setoran->status === 'acc' ? 'bg-blue-500' : 'bg-yellow-500' }} text-white flex items-center justify-center">
                            @if($setoran->status === 'acc') <i data-lucide="check" class="w-4 h-4"></i> @else <span
                            class="text-xs font-bold">2</span> @endif
                        </div>
                    </div>
                    <div class="flex-1 h-1 {{ $setoran->status === 'acc' ? 'bg-blue-500' : 'bg-gray-200' }} rounded"></div>
                    <div class="flex items-center gap-2">
                        <div
                            class="w-7 h-7 rounded-full {{ $setoran->status === 'acc' ? 'bg-blue-500' : 'bg-gray-200' }} text-white flex items-center justify-center">
                            @if($setoran->status === 'acc') <i data-lucide="check" class="w-4 h-4"></i> @else <span
                            class="text-xs font-bold">3</span> @endif
                        </div>
                    </div>
                </div>

                <div
                    class="rounded-3xl p-6 mb-6 border flex items-start gap-4 {{ $setoran->status === 'acc' ? 'bg-blue-50 border-blue-100 text-blue-800' : 'bg-yellow-50 border-yellow-100 text-yellow-800' }}">
                    <div
                        class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 {{ $setoran->status === 'acc' ? 'bg-blue-500 text-white' : 'bg-yellow-500 text-white' }}">
                        <i data-lucide="{{ $setoran->status === 'acc' ? 'Check' : 'clock' }}" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">
                            {{ $setoran->status === 'acc' ? 'Laporan Disetujui!' : 'Menunggu Verifikasi' }}</h3>
                        <p class="text-sm opacity-80">
                            {{ $setoran->status === 'acc'
                ? 'Disetujui pada ' . $setoran->acc_at->translatedFormat('d M Y, H:i')
                : 'Dikirim pada ' . $setoran->dikirim_at->translatedFormat('d M Y, H:i') }}
                        </p>
                    </div>
                </div>

                {{-- Ringkasan --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800">Detail Setoran</h3>
                        <i data-lucide="file-text" class="w-5 h-5 text-gray-300"></i>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @foreach($setoran->detail as $detail)
                            <div class="px-6 py-4 flex justify-between items-center">
                                <div>
                                    <p class="font-bold text-gray-800 text-sm">{{ $detail->toko->nama_toko }}</p>
                                    <p class="text-xs text-gray-400">{{ $detail->jumlah_terjual }} unit</p>
                                </div>
                                <p class="font-bold text-gray-800">Rp {{ number_format($detail->total_uang, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="p-6 bg-gray-50 flex justify-between items-center">
                        <span class="text-sm font-bold text-gray-500 uppercase">Total Akhir</span>
                        <span class="text-2xl font-black text-gray-800">Rp
                            {{ number_format($setoran->totalUang(), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        function setoranForm(hargaSatuan) {
            return {
                selectedToko: '',
                jumlah: 0,
                hargaSatuan: hargaSatuan,
                formatRupiah(angka) {
                    return new Intl.NumberFormat('id-ID').format(angka);
                }
            }
        }

        function confirmKirim() {
            return confirm('Kirim laporan sekarang? Data tidak dapat diubah kembali.');
        }
    </script>
@endpush