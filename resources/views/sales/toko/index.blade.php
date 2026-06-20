@extends('sales.layouts.app')

@section('title', 'Toko Saya')

@section('page-title')
<div class="flex items-center gap-2">
    <span>Toko Saya</span>
</div>
@endsection

@section('page-subtitle', 'Kelola daftar toko yang kamu tangani')

@section('content')

<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">

    {{-- SEARCH --}}
    <form
        method="GET"
        action="{{ route('sales.toko.index') }}"
        class="flex flex-col sm:flex-row gap-2"
    >

        <div class="relative">

            <i data-lucide="search"
               class="w-4 h-4 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2"></i>

            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Cari nama toko..."
                class="w-full sm:w-72 pl-11 pr-4 py-3 text-sm
                       border border-gray-200 rounded-2xl
                       focus:outline-none focus:ring-2 focus:ring-blue-400"
            >

        </div>

        <button
            type="submit"
            class="px-5 py-3 bg-blue-600 text-white
                   rounded-2xl text-sm font-semibold
                   hover:bg-blue-700 transition
                   flex items-center justify-center gap-2"
        >

            <i data-lucide="search" class="w-4 h-4"></i>

            <span>Cari</span>

        </button>

        @if($search)

            <a href="{{ route('sales.toko.index') }}"
               class="px-4 py-3 bg-gray-100 text-gray-600
                      rounded-2xl text-sm hover:bg-gray-200
                      transition flex items-center justify-center">

                <i data-lucide="x" class="w-4 h-4"></i>

            </a>

        @endif

    </form>

    {{-- BUTTON --}}
    <a href="{{ route('sales.toko.create') }}"
       class="inline-flex items-center justify-center gap-2
              px-5 py-3 bg-blue-600 text-white
              rounded-2xl text-sm font-semibold
              hover:bg-blue-700 transition">

        <i data-lucide="plus" class="w-4 h-4"></i>

        <span>Tambah Toko</span>

    </a>

</div>

@if($toko->count())

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 mb-6">

    @foreach($toko as $item)

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100
                overflow-hidden hover:shadow-lg transition-all duration-300 group">

        {{-- THUMBNAIL --}}
        <div class="h-36 relative
                    {{ $item->latitude ? 'bg-gray-100' : 'bg-gradient-to-br from-blue-50 to-blue-100' }}
                    flex items-center justify-center">

            @if($item->latitude && $item->longitude)

                <div class="absolute inset-0 flex items-center justify-center text-gray-300">

                    <i data-lucide="map" class="w-16 h-16"></i>

                </div>

                <div class="absolute top-3 right-3
                            bg-blue-500 text-white text-xs
                            px-3 py-1 rounded-full
                            flex items-center gap-1 shadow">

                    <i data-lucide="map-pin" class="w-3 h-3"></i>

                    <span>Ada Lokasi</span>

                </div>

            @else

                <div class="text-center text-blue-300">

                    <i data-lucide="store" class="w-14 h-14 mx-auto"></i>

                    <p class="text-xs mt-2">
                        Belum ada lokasi
                    </p>

                </div>

            @endif

        </div>

        {{-- CONTENT --}}
        <div class="p-5">

            <h3 class="font-bold text-gray-800 truncate mb-3 text-lg">
                {{ $item->nama_toko }}
            </h3>

            {{-- Alamat --}}
            <div class="flex items-start gap-2 text-sm text-gray-500 mb-2">

                <i data-lucide="map-pin" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>

                <span class="line-clamp-2">
                    {{ $item->alamat ?? 'Alamat belum diisi' }}
                </span>

            </div>

            {{-- No HP --}}
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-5">

                <i data-lucide="phone" class="w-4 h-4 flex-shrink-0"></i>

                <span>
                    {{ $item->no_hp ?? 'No HP belum diisi' }}
                </span>

            </div>

            {{-- BUTTON --}}
            <div class="flex gap-2">

                {{-- Edit --}}
                <a href="{{ route('sales.toko.edit', $item) }}"
                   class="flex-1 flex items-center justify-center gap-2
                          py-2.5 bg-yellow-50 text-yellow-700
                          rounded-2xl text-sm font-semibold
                          hover:bg-yellow-100 transition">

                    <i data-lucide="square-pen" class="w-4 h-4"></i>

                    <span>Edit</span>

                </a>

                {{-- Delete --}}
                <form
                    action="{{ route('sales.toko.destroy', $item) }}"
                    method="POST"
                    class="flex-1"
                    onsubmit="return confirm('Hapus toko \'{{ $item->nama_toko }}\'?')"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="w-full flex items-center justify-center gap-2
                               py-2.5 bg-red-50 text-red-600
                               rounded-2xl text-sm font-semibold
                               hover:bg-red-100 transition"
                    >

                        <i data-lucide="trash-2" class="w-4 h-4"></i>

                        <span>Hapus</span>

                    </button>

                </form>

            </div>

        </div>

    </div>

    @endforeach

</div>

{{-- PAGINATION --}}
<div class="mt-8">
    {{ $toko->appends(['search' => $search])->links() }}
</div>

@else

{{-- EMPTY STATE --}}
<div class="bg-white rounded-3xl shadow-sm border border-gray-100
            py-20 px-6 text-center">

    <div class="w-24 h-24 mx-auto rounded-full bg-blue-50
                flex items-center justify-center mb-5">

        <i data-lucide="store" class="w-12 h-12 text-blue-400"></i>

    </div>

    <h3 class="text-xl font-bold text-gray-700">
        Belum ada toko
    </h3>

    <p class="text-sm text-gray-400 mt-2">
        Tambahkan toko pertama yang kamu tangani
    </p>

    <a href="{{ route('sales.toko.create') }}"
       class="inline-flex items-center gap-2
              mt-6 px-6 py-3 bg-blue-600 text-white
              rounded-2xl text-sm font-semibold
              hover:bg-blue-700 transition">

        <i data-lucide="plus" class="w-4 h-4"></i>

        <span>Tambah Toko Sekarang</span>

    </a>

</div>

@endif

@endsection