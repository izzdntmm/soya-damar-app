@extends('sales.layouts.app')

@section('title', 'Edit Toko')

@section('page-title')
    <div class="flex items-center gap-2">
        <i data-lucide="square-pen" class="w-6 h-6 text-blue-600"></i>

        <span>Edit Toko</span>
    </div>
@endsection

@section('page-subtitle', $toko->nama_toko)

@section('content')

    <div class="max-w-3xl">

        {{-- Back --}}
        <a href="{{ route('sales.toko.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition mb-6 font-medium">

            <i data-lucide="arrow-left" class="w-4 h-4"></i>

            <span>Kembali ke Daftar Toko</span>

        </a>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

            {{-- Header --}}
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">

                <div class="flex items-center gap-3">

                    <div class="w-11 h-11 rounded-2xl bg-blue-100
                                flex items-center justify-center">

                        <i data-lucide="store" class="w-5 h-5 text-blue-600"></i>

                    </div>

                    <div>

                        <h3 class="font-semibold text-gray-800">
                            Edit Informasi Toko
                        </h3>

                        <p class="text-sm text-gray-400 mt-0.5">
                            Perbarui data toko yang kamu tangani
                        </p>

                    </div>

                </div>

            </div>

            {{-- FORM --}}
            <form method="POST" action="{{ route('sales.toko.update', $toko) }}" class="p-6 space-y-6">

                @csrf
                @method('PUT')

                {{-- Nama Toko --}}
                <div>

                    <label class="flex items-center gap-2
                                  text-sm font-semibold text-gray-700 mb-2">

                        <i data-lucide="store" class="w-4 h-4 text-blue-500"></i>

                        <span>
                            Nama Toko
                            <span class="text-red-500">*</span>
                        </span>

                    </label>

                    <input type="text" name="nama_toko" value="{{ old('nama_toko', $toko->nama_toko) }}" class="w-full px-4 py-3 border rounded-2xl text-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-400
                               transition
                               {{ $errors->has('nama_toko')
        ? 'border-red-400 bg-red-50'
        : 'border-gray-200' }}">

                    @error('nama_toko')

                        <div class="flex items-center gap-2
                                        text-red-500 text-xs mt-2">

                            <i data-lucide="circle-alert" class="w-4 h-4"></i>

                            <span>{{ $message }}</span>

                        </div>

                    @enderror

                </div>

                {{-- No HP --}}
                <div>

                    <label class="flex items-center gap-2
                                  text-sm font-semibold text-gray-700 mb-2">

                        <i data-lucide="phone" class="w-4 h-4 text-blue-500"></i>

                        <span>No. HP Toko</span>

                    </label>

                    <input type="text" name="no_hp" value="{{ old('no_hp', $toko->no_hp) }}" class="w-full px-4 py-3 border border-gray-200
                               rounded-2xl text-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-400
                               transition">

                </div>

                {{-- Alamat --}}
                <div>

                    <label class="flex items-center gap-2
                                  text-sm font-semibold text-gray-700 mb-2">

                        <i data-lucide="map-pin" class="w-4 h-4 text-blue-500"></i>

                        <span>Alamat</span>

                    </label>

                    <textarea name="alamat" rows="3" class="w-full px-4 py-3 border border-gray-200
                               rounded-2xl text-sm resize-none
                               focus:outline-none focus:ring-2 focus:ring-blue-400
                               transition">{{ old('alamat', $toko->alamat) }}</textarea>

                </div>


                {{-- BUTTON --}}
                <div class="flex flex-col sm:flex-row gap-3 pt-3">

                    {{-- Submit --}}
                    <button type="submit" class="inline-flex items-center justify-center gap-2
                               px-6 py-3 bg-blue-600 text-white
                               rounded-2xl text-sm font-semibold
                               hover:bg-blue-700 transition">

                        <i data-lucide="save" class="w-4 h-4"></i>

                        <span>Simpan Perubahan</span>

                    </button>

                    {{-- Cancel --}}
                    <a href="{{ route('sales.toko.index') }}" class="inline-flex items-center justify-center gap-2
                              px-6 py-3 bg-gray-100 text-gray-600
                              rounded-2xl text-sm font-semibold
                              hover:bg-gray-200 transition">

                        <i data-lucide="x" class="w-4 h-4"></i>

                        <span>Batal</span>

                    </a>

                </div>

            </form>

        </div>

    </div>

@endsection

@push('scripts')
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places&callback=Function.prototype">
        </script>
@endpush