@extends('admin.layouts.app')

@section('title', 'Data Toko')

@section('page-title')
<div class="flex items-center gap-3">
    <div>
        <h1 class="text-xl font-bold text-gray-800">
            Data Toko
        </h1>
    </div>

</div>
@endsection

@section('page-subtitle', 'Daftar semua toko dari seluruh sales')

@section('content')

{{-- FILTER --}}
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-4 sm:p-5 mb-6">

    <form method="GET"
          action="{{ route('admin.toko.index') }}"
          class="flex flex-col lg:flex-row gap-3">

        {{-- SEARCH --}}
        <div class="relative flex-1">

            <i data-lucide="search"
               class="w-4 h-4 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2">
            </i>

            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Cari nama toko atau alamat..."
                class="w-full pl-11 pr-4 py-3 text-sm border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
            >

        </div>

        {{-- FILTER SALES --}}
        <div class="relative min-w-[220px]">

            <i data-lucide="users"
               class="w-4 h-4 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2">
            </i>

            <select
                name="sales_id"
                class="w-full pl-11 pr-10 py-3 text-sm border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-400 appearance-none bg-white transition"
            >

                <option value="">
                    Semua Sales
                </option>

                @foreach($listSales as $s)

                    <option value="{{ $s->id }}"
                        {{ $salesId == $s->id ? 'selected' : '' }}>

                        {{ $s->nama }}

                    </option>

                @endforeach

            </select>

            <i data-lucide="chevron-down"
               class="w-4 h-4 text-gray-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none">
            </i>

        </div>

        {{-- BUTTON --}}
        <div class="flex gap-3">

            {{-- FILTER --}}
            <button type="submit"
                class="px-5 py-3 bg-blue-600 text-white rounded-2xl text-sm font-semibold hover:bg-blue-700 transition flex items-center justify-center gap-2 shadow-sm">

                <i data-lucide="filter" class="w-4 h-4"></i>

                <span>Filter</span>

            </button>

            {{-- RESET --}}
            @if($search || $salesId)

                <a href="{{ route('admin.toko.index') }}"
                   class="px-5 py-3 bg-gray-100 text-gray-600 rounded-2xl text-sm font-semibold hover:bg-gray-200 transition flex items-center justify-center gap-2">

                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>

                    <span>Reset</span>

                </a>

            @endif

        </div>

    </form>

</div>

{{-- GRID TOKO --}}
@if($toko->count())

<div class="space-y-4 mb-6">

    @foreach($toko as $item)

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">

    <div class="flex items-center justify-between">

        <div class="flex items-center gap-4">

            {{-- Icon toko --}}
            <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center">
                <i data-lucide="store" class="w-7 h-7 text-blue-600"></i>
            </div>

            {{-- Info toko --}}
            <div>
                <h3 class="font-bold text-lg text-gray-800">
                    {{ $item->nama_toko }}
                </h3>

                <p class="text-sm text-gray-500">
                    {{ $item->alamat ?? 'Alamat belum diisi' }}
                </p>

                <div class="mt-2 text-sm text-gray-600">
                    Sales: <span class="font-semibold">{{ $item->sales->nama }}</span>
                </div>
            </div>

        </div>

        {{-- Tombol --}}
        <a href="{{ route('admin.toko.show', $item) }}"
           class="px-5 py-3 bg-blue-600 text-white rounded-2xl text-sm font-semibold hover:bg-blue-700 transition">

            Detail
        </a>

    </div>

</div>

    @endforeach

</div>

{{-- PAGINATION --}}
<div>
    {{ $toko->appends([
        'search' => $search,
        'sales_id' => $salesId
    ])->links() }}
</div>

@else

{{-- EMPTY --}}
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 py-20 text-center">

    <div class="w-20 h-20 rounded-3xl bg-gray-100 flex items-center justify-center mx-auto mb-5">

        <i data-lucide="store" class="w-10 h-10 text-gray-400"></i>

    </div>

    <h3 class="text-lg font-bold text-gray-700">
        Belum ada data toko
    </h3>

    <p class="text-sm text-gray-400 mt-2">
        Toko akan muncul setelah sales menambahkannya
    </p>

</div>

@endif

@endsection