<section class="space-y-6">
    {{-- Tombol trigger --}}
    <x-danger-button
        x-data
        x-on:click.prevent="$dispatch('open-modal', { name: 'confirm-user-deletion' })"
    >
        {{ __('Delete Account') }}
    </x-danger-button>

    {{-- Modal --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                Yakin ingin hapus akun?
            </h2>

            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                Jika akun dihapus, semua data kamu juga akan hilang permanen. Masukkan password kamu untuk konfirmasi.
            </p>

            {{-- Password --}}
            <div class="mt-4">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                    Password
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="••••••••"
                    class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm px-4 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm"
                />
                @error('password', 'userDeletion')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Aksi --}}
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ml-3">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
