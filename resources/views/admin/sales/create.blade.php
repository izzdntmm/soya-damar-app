@extends('admin.layouts.app')

@section('title', 'Tambah Sales')
@section('page-title')
    <div class="flex items-center gap-2">
        <span>Tambah Sales Baru</span>
    </div>
@endsection
@section('page-subtitle', 'Buat akun sales baru untuk Soya Damar')

@section('content')

    <div class="max-w-2xl">

        {{-- Tombol Kembali --}}
        <a href="{{ route('admin.sales.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition mb-6">
            ← Kembali
        </a>

        {{-- Card Form --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-semibold text-gray-700">Informasi Sales</h3>
                <p class="text-xs text-gray-400 mt-0.5">Semua field bertanda * wajib diisi</p>
            </div>

            <form method="POST" action="{{ route('admin.sales.store') }}" class="p-6 space-y-5">
                @csrf

                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Budi Santoso" class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400
                                   {{ $errors->has('nama') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}">
                    @error('nama')
                        <p class="text-red-500 text-xs mt-1.5">⚠ {{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Contoh: budi@soyadamar.com"
                        class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400
                                   {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1.5">⚠ {{ $message }}</p>
                    @enderror
                </div>

                {{-- No HP --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        No. HP
                    </label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="Contoh: 08123456789"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                {{-- Alamat --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Alamat
                    </label>
                    <textarea name="alamat" rows="3" placeholder="Alamat lengkap sales..."
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none">{{ old('alamat') }}</textarea>
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password" placeholder="Minimal 8 karakter" class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 pr-12
                                       {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}">
                        <button type="button" onclick="togglePassword()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-lg">
                            👁
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1.5">⚠ {{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-400 mt-1.5">
                        Sales akan menggunakan password ini untuk login.
                    </p>
                </div>

                {{-- Tombol --}}
                <div class="flex gap-3 pt-2">
                    <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition flex items-center gap-2">

                        <i data-lucide="save" class="w-4 h-4"></i>

                        <span>Simpan Sales</span>
                    </button>
                    <a href="{{ route('admin.sales.index') }}"
                        class="px-6 py-2.5 bg-gray-100 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-200 transition">
                        Batal
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
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
@endpush