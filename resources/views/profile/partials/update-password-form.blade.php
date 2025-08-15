<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        {{-- Current Password --}}
        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Password Lama</label>
            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
            @error('current_password', 'updatePassword')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- New Password --}}
        <div>
            <label for="update_password_password" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Password Baru</label>
            <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
            @error('password', 'updatePassword')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Konfirmasi Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
            @error('password_confirmation', 'updatePassword')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-green-600 dark:text-green-400">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
