@extends('admin.layouts.app')

@section('title', 'Manajemen Sales')
@section('page-title')
    <div class="flex items-center gap-2">
        <span>Manajemen Sales</span>
    </div>
@endsection
@section('page-subtitle', 'Kelola akun sales Soya Damar')

@section('content')

    {{-- ── Header Action ───────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">

        {{-- Search Box --}}
        <form method="GET" action="{{ route('admin.sales.index') }}" class="flex gap-2">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, email, atau no HP..."
                class="w-72 px-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400">
            <button type="submit"
                class="px-4 py-2 bg-gray-100 text-gray-600 rounded-xl text-sm hover:bg-gray-200 transition flex items-center gap-2">

                <i data-lucide="search" class="w-4 h-4"></i>

                <span>Cari</span>
            </button>
            @if($search)
                <a href="{{ route('admin.sales.index') }}"
                    class="px-4 py-2 bg-gray-100 text-gray-500 rounded-xl text-sm hover:bg-gray-200 transition">
                    ✕ Reset
                </a>
            @endif
        </form>

        {{-- Tombol Tambah --}}
        <a href="{{ route('admin.sales.create') }}"
            class="flex items-center gap-2 px-5 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
            <span>＋</span> Tambah Sales
        </a>
    </div>

    {{-- ── Tabel Daftar Sales ───────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Info hasil pencarian --}}
        @if($search)
            <div class="px-6 py-3 bg-blue-50 border-b border-blue-100 text-sm text-blue-700">
                Menampilkan hasil pencarian untuk: <strong>"{{ $search }}"</strong>
                — ditemukan {{ $sales->total() }} data
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-6 py-4 text-gray-500 font-semibold text-xs uppercase tracking-wide">Sales
                        </th>
                        <th class="text-left px-6 py-4 text-gray-500 font-semibold text-xs uppercase tracking-wide">Kontak
                        </th>
                        <th class="text-center px-6 py-4 text-gray-500 font-semibold text-xs uppercase tracking-wide">Toko
                        </th>
                        <th class="text-center px-6 py-4 text-gray-500 font-semibold text-xs uppercase tracking-wide">
                            Setoran ACC</th>
                        <th class="text-center px-6 py-4 text-gray-500 font-semibold text-xs uppercase tracking-wide">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($sales as $item)
                        <tr class="hover:bg-gray-50 transition">

                            {{-- Kolom Sales --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-sm flex-shrink-0">
                                        {{ strtoupper(substr($item->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $item->nama }}</p>
                                        <p class="text-xs text-gray-400">{{ $item->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Kolom Kontak --}}
                            <td class="px-6 py-4">
                                <p class="text-gray-700">{{ $item->no_hp ?? '-' }}</p>
                                <p class="text-xs text-gray-400 mt-0.5 max-w-xs truncate">
                                    {{ $item->alamat ?? '-' }}
                                </p>
                            </td>

                            {{-- Kolom Jumlah Toko --}}
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-bold text-sm">
                                    {{ $item->toko_count }}
                                </span>
                            </td>

                            {{-- Kolom Setoran ACC --}}
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-bold text-sm">
                                    {{ $item->total_setoran_acc }}
                                </span>
                            </td>

                            {{-- Kolom Aksi --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">

                                    {{-- Lihat Detail --}}
                                    <a href="{{ route('admin.sales.show', $item) }}"
                                        class="px-3 py-1.5 text-xs bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition font-medium flex items-center gap-1.5">

                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>

                                        <span>Detail</span>
                                    </a>

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.sales.edit', $item) }}"
                                        class="px-3 py-1.5 text-xs bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 transition font-medium flex items-center gap-1.5">

                                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i>

                                        <span>Edit</span>
                                    </a>

                                    {{-- Hapus --}}
                                    <form action="{{ route('admin.sales.destroy', $item) }}" method="POST"
                                        onsubmit="return confirmDelete('{{ $item->nama }}', this)">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="px-3 py-1.5 text-xs bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition font-medium flex items-center gap-1.5">

                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>

                                            <span>Hapus</span>
                                        </button>

                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                                <p class="text-5xl mb-3">👥</p>
                                <p class="font-semibold text-gray-500">Belum ada data sales</p>
                                <p class="text-sm mt-1">Tambahkan sales pertama kamu</p>
                                <a href="{{ route('admin.sales.create') }}"
                                    class="inline-block mt-4 px-5 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                                    ＋ Tambah Sales
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($sales->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $sales->appends(['search' => $search])->links() }}
            </div>
        @endif

    </div>

@endsection

@push('scripts')
    <script>
        function confirmDelete(nama, form) {
            Swal.fire({
                title: 'Hapus Akun?',
                text: 'Data tidak dapat dikembalikan.',
                icon: 'warning',
                width: '320px',
                padding: '1rem',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });

            return false;
        }
    </script>
@endpush