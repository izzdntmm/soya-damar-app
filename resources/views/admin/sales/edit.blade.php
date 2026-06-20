@extends('admin.layouts.app')

@section('title', 'Edit Sales')

@section('page-title')
<div class="flex items-center gap-3">
    <div>
        <h1 class="text-xl font-bold text-gray-800">
            Edit Data Sales
        </h1>
    </div>

</div>
@endsection

@section('page-subtitle', 'Perbarui informasi sales')

@section('content')

<div class="max-w-3xl">

    {{-- BACK --}}
    <a href="{{ route('admin.sales.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition mb-6 font-medium">

            <i data-lucide="arrow-left" class="w-4 h-4"></i>

            <span>Kembali ke Daftar Sales</span>

        </a>

    {{-- CARD --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- HEADER --}}
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50 flex items-center gap-4">

            {{-- Avatar --}}
            <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-xl shadow-sm">
                {{ strtoupper(substr($sale->nama, 0, 1)) }}
            </div>

            {{-- Info --}}
            <div>

                <h3 class="font-bold text-gray-800 text-lg">
                    {{ $sale->nama }}
                </h3>

                <p class="text-sm text-gray-400 mt-1">
                    {{ $sale->email }}
                </p>

            </div>

        </div>

        {{-- FORM --}}
        <form method="POST"
              action="{{ route('admin.sales.update', $sale) }}"
              class="p-6 space-y-6">

            @csrf
            @method('PUT')

            {{-- NAMA --}}
            <div>

                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">

                    <i data-lucide="user-round" class="w-4 h-4 text-blue-600"></i>

                    <span>
                        Nama Lengkap
                    </span>

                    <span class="text-red-500">*</span>

                </label>

                <input
                    type="text"
                    name="nama"
                    value="{{ old('nama', $sale->nama) }}"
                    class="w-full px-4 py-3 border rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition
                    {{ $errors->has('nama')
                        ? 'border-red-400 bg-red-50'
                        : 'border-gray-200' }}"
                >

                @error('nama')
                    <div class="flex items-center gap-1.5 text-red-500 text-xs mt-2">

                        <i data-lucide="circle-alert" class="w-3.5 h-3.5"></i>

                        <span>{{ $message }}</span>

                    </div>
                @enderror

            </div>

            {{-- EMAIL --}}
            <div>

                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">

                    <i data-lucide="mail" class="w-4 h-4 text-blue-600"></i>

                    <span>Email</span>

                    <span class="text-red-500">*</span>

                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $sale->email) }}"
                    class="w-full px-4 py-3 border rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition
                    {{ $errors->has('email')
                        ? 'border-red-400 bg-red-50'
                        : 'border-gray-200' }}"
                >

                @error('email')
                    <div class="flex items-center gap-1.5 text-red-500 text-xs mt-2">

                        <i data-lucide="circle-alert" class="w-3.5 h-3.5"></i>

                        <span>{{ $message }}</span>

                    </div>
                @enderror

            </div>

            {{-- NO HP --}}
            <div>

                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">

                    <i data-lucide="smartphone" class="w-4 h-4 text-purple-600"></i>

                    <span>No. HP</span>

                </label>

                <input
                    type="text"
                    name="no_hp"
                    value="{{ old('no_hp', $sale->no_hp) }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
                >

            </div>

            {{-- ALAMAT --}}
            <div>

                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">

                    <i data-lucide="map-pin" class="w-4 h-4 text-red-500"></i>

                    <span>Alamat</span>

                </label>

                <textarea
                    name="alamat"
                    rows="4"
                    class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none transition"
                >{{ old('alamat', $sale->alamat) }}</textarea>

            </div>

            {{-- PASSWORD --}}
            <div>

                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">

                    <i data-lucide="lock-keyhole" class="w-4 h-4 text-yellow-600"></i>

                    <span>Password Baru</span>

                </label>

                <div class="relative">

                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Kosongkan jika tidak ingin ubah password"
                        class="w-full px-4 py-3 border rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 pr-12 transition
                        {{ $errors->has('password')
                            ? 'border-red-400 bg-red-50'
                            : 'border-gray-200' }}"
                    >

                    {{-- Toggle --}}
                    <button
                        type="button"
                        onclick="togglePassword()"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition"
                    >

                        <i data-lucide="eye" class="w-5 h-5"></i>

                    </button>

                </div>

                @error('password')
                    <div class="flex items-center gap-1.5 text-red-500 text-xs mt-2">

                        <i data-lucide="circle-alert" class="w-3.5 h-3.5"></i>

                        <span>{{ $message }}</span>

                    </div>
                @enderror

                <p class="text-xs text-gray-400 mt-2">
                    Biarkan kosong jika tidak ingin mengubah password.
                </p>

            </div>

            {{-- BUTTON --}}
            <div class="flex flex-col sm:flex-row gap-3 pt-2">

                {{-- SAVE --}}
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white rounded-2xl text-sm font-semibold hover:bg-blue-700 transition flex items-center justify-center gap-2 shadow-sm">

                    <i data-lucide="save" class="w-4 h-4"></i>

                    <span>Simpan Perubahan</span>

                </button>

                {{-- CANCEL --}}
                <a href="{{ route('admin.sales.index') }}"
                   class="px-6 py-3 bg-gray-100 text-gray-600 rounded-2xl text-sm font-semibold hover:bg-gray-200 transition flex items-center justify-center gap-2">

                    <i data-lucide="x" class="w-4 h-4"></i>

                    <span>Batal</span>

                </a>

            </div>

        </form>

    </div>

</div>

@endsection

@push('scripts')
<script>

function togglePassword() {

    const input = document.getElementById('password');

    input.type =
        input.type === 'password'
        ? 'text'
        : 'password';

}

lucide.createIcons();

</script>
@endpush