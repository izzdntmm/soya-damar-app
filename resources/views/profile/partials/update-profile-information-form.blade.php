<section>
    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        {{-- Name --}}
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Name</label>
            <input id="name" name="name" type="text"
                class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                value="{{ old('name', auth()->user()->name) }}" required autocomplete="name">
            @error('name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
            <input id="email" name="email" type="email"
                class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                value="{{ old('email', auth()->user()->email) }}" required autocomplete="username">
            @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol Simpan --}}
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-green-600 dark:text-green-400">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
