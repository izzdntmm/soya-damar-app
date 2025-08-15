@extends('layouts.app')

@section('content')
    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 border-b pb-2">👤 Pengaturan Profil</h2>

            <div class="space-y-8">




                {{-- Form Update Informasi Profil --}}
                <div class="p-6 bg-white shadow-lg rounded-xl">
                    <div class="flex items-center mb-4">
                        <svg class="h-6 w-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A4 4 0 017 17h10a4 4 0 011.879.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-800">Informasi Pengguna</h3>
                    </div>
                    <div class="text-sm text-gray-500 mb-4">Perbarui nama dan email Anda.</div>

                    @include('profile.partials.update-profile-information-form')
                </div>

                {{-- Form Update Password --}}
                <div class="p-6 bg-white shadow-lg rounded-xl">
                    <div class="flex items-center mb-4">
                        <svg class="h-6 w-6 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 11c0-1.657 1.343-3 3-3s3 1.343 3 3v1h-6v-1zM6 14v1a2 2 0 002 2h8a2 2 0 002-2v-1" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-800">Ubah Password</h3>
                    </div>
                    <div class="text-sm text-gray-500 mb-4">Pastikan akun kamu menggunakan password yang panjang dan acak
                        agar tetap aman.</div>

                    @include('profile.partials.update-password-form')
                </div>

                {{-- Form Hapus Akun --}}
                <div class="p-6 bg-white shadow-lg rounded-xl border border-red-200">
                    <div class="flex items-center mb-4">
                        <svg class="h-6 w-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a1 1 0 011 1v2H9V4a1 1 0 011-1z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-red-600">Hapus Akun</h3>
                    </div>
                    <div class="text-sm text-gray-500 mb-4">Setelah akun Anda dihapus, semua sumber daya dan datanya akan
                        dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi apa pun yang
                        ingin Anda simpan.</div>

                    @include('profile.partials.delete-user-form')
                </div>

            </div>
        </div>
    </div>
@endsection