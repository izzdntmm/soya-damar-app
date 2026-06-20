@extends('sales.layouts.app')

@section('title', 'Profile')
@section('page-title', 'Profile Saya')
@section('page-subtitle', 'Kelola akun sales')

@section('content')

    <div class="max-w-4xl mx-auto space-y-6">

        {{-- PROFILE --}}
        <div class="bg-white border border-gray-100 rounded-3xl shadow-sm overflow-hidden">

            <div class="p-6 sm:p-8 border-b border-gray-100">

                <div class="flex items-center gap-4">

                    <div class="w-16 h-16 rounded-2xl bg-blue-600
                                flex items-center justify-center
                                text-white text-2xl font-bold shadow">

                        {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}

                    </div>

                    <div>

                        <h2 class="text-xl font-bold text-gray-800">
                            Informasi Profile
                        </h2>

                        <p class="text-sm text-gray-400 mt-1">
                            Update data akun sales.
                        </p>

                    </div>

                </div>

            </div>

            <div class="p-6 sm:p-8">

                <form method="POST" action="{{ route('sales.profile.update') }}" class="space-y-5">

                    @csrf
                    @method('PATCH')

                    {{-- Nama --}}
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Lengkap
                        </label>

                        <input type="text" name="nama" value="{{ old('nama', Auth::user()->nama) }}" class="w-full rounded-2xl border border-gray-200
                                   focus:border-blue-500
                                   focus:ring focus:ring-blue-100
                                   px-4 py-3 text-sm">

                    </div>

                    {{-- Email --}}
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Email
                        </label>

                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="w-full rounded-2xl border border-gray-200
                                   focus:border-blue-500
                                   focus:ring focus:ring-blue-100
                                   px-4 py-3 text-sm">

                    </div>

                    {{-- Button --}}
                    <div class="pt-2">

                        <button type="submit" class="inline-flex items-center gap-2
                                   bg-blue-600 hover:bg-blue-700
                                   text-white text-sm font-semibold
                                   px-5 py-3 rounded-2xl transition">

                            <i data-lucide="save" class="w-4 h-4"></i>

                            Simpan Perubahan

                        </button>

                    </div>

                </form>

            </div>

        </div>

        {{-- PASSWORD --}}
        <div class="bg-white border border-gray-100 rounded-3xl shadow-sm overflow-hidden">

            <div class="p-6 sm:p-8 border-b border-gray-100">

                <h2 class="text-xl font-bold text-gray-800">
                    Update Password
                </h2>

                <p class="text-sm text-gray-400 mt-1">
                    Gunakan password yang aman agar akun tetap terlindungi.
                </p>

            </div>

            <div class="p-6 sm:p-8">

                <form method="POST" action="{{ route('password.update') }}" class="space-y-5">

                    @csrf
                    @method('PUT')

                    {{-- Password Lama --}}
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Password Lama
                        </label>

                        <input type="password" name="current_password" class="w-full rounded-2xl border border-gray-200
                                   focus:border-blue-500
                                   focus:ring focus:ring-blue-100
                                   px-4 py-3 text-sm">

                    </div>

                    {{-- Password Baru --}}
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Password Baru
                        </label>

                        <input type="password" name="password" class="w-full rounded-2xl border border-gray-200
                                   focus:border-blue-500
                                   focus:ring focus:ring-blue-100
                                   px-4 py-3 text-sm">

                    </div>

                    {{-- Konfirmasi --}}
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Konfirmasi Password Baru
                        </label>

                        <input type="password" name="password_confirmation" class="w-full rounded-2xl border border-gray-200
                                   focus:border-blue-500
                                   focus:ring focus:ring-blue-100
                                   px-4 py-3 text-sm">

                    </div>

                    {{-- Button --}}
                    <div class="pt-2">

                        <button type="submit" class="inline-flex items-center gap-2
                                   bg-blue-600 hover:bg-blue-700
                                   text-white text-sm font-semibold
                                   px-5 py-3 rounded-2xl transition">

                            <i data-lucide="shield-check" class="w-4 h-4"></i>

                            Update Password

                        </button>

                    </div>

                </form>

            </div>

        </div>

        {{-- DELETE ACCOUNT --}}
        <div class="bg-white border border-red-100 rounded-3xl shadow-sm overflow-hidden">

            <div class="p-6 sm:p-8 border-b border-red-100">

                <h2 class="text-xl font-bold text-red-600">
                    Hapus Akun
                </h2>

                <p class="text-sm text-gray-400 mt-1">
                    Setelah akun dihapus, semua data akan hilang permanen.
                </p>

            </div>

            <div class="p-6 sm:p-8">

                <form method="POST" action="{{ route('profile.destroy') }}">

                    @csrf
                    @method('DELETE')

                    <button type="submit" onclick="return confirmDeleteAccount(this.form)" class="inline-flex items-center gap-2
               bg-red-500 hover:bg-red-600
               text-white text-sm font-semibold
               px-5 py-3 rounded-2xl transition">

                        <i data-lucide="trash-2" class="w-4 h-4"></i>

                        Hapus Akun

                    </button>

                </form>

            </div>

        </div>

    </div>

@endsection

<script>
function confirmDeleteAccount(form) {
    Swal.fire({
        title: 'Hapus Akun?',
        text: 'Akun yang dihapus tidak dapat dikembalikan.',
        icon: 'warning',
        width: '350px',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });

    return false;
}
</script>