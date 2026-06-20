@php
    $notifs = \App\Models\Notifikasi::where('user_id', Auth::id())
        ->latest()->take(8)->get();

    $belumDibaca = $notifs->whereNull('dibaca_at')->count();
@endphp

<div class="relative" x-data="{ open: false }" x-init="
         $watch('open', val => {
             if (val) {
                 setTimeout(() => {
                     window.addEventListener('click', closeOnOutside);
                 }, 10);
             } else {
                 window.removeEventListener('click', closeOnOutside);
             }
         });

         function closeOnOutside(e) {
             if (!$el.contains(e.target)) {
                 open = false;
                 window.removeEventListener('click', closeOnOutside);
             }
         }
     ">

    {{-- Bell Button --}}
    <button @click.stop="open = !open" type="button" class="relative w-11 h-11 rounded-2xl border border-gray-200 bg-white
                   hover:bg-blue-50 hover:border-blue-300
                   flex items-center justify-center
                   transition-all duration-200 shadow-sm focus:outline-none">

        <i data-lucide="bell" class="w-5 h-5 text-gray-600"></i>

        {{-- Badge --}}
        @if($belumDibaca > 0)
            <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px]
                         bg-red-500 text-white text-[10px] font-bold
                         rounded-full flex items-center justify-center px-1 leading-none shadow">
                {{ $belumDibaca > 9 ? '9+' : $belumDibaca }}
            </span>
        @endif
    </button>

    {{-- Dropdown --}}
    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-1 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-1 scale-95" @click.stop class="absolute right-0 top-14 w-80 bg-white rounded-3xl
                shadow-2xl border border-gray-100
                z-[999] overflow-hidden">

        {{-- Header --}}
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">

            <div>
                <p class="font-bold text-gray-800 text-sm">
                    Notifikasi
                </p>

                <p class="text-xs text-gray-400 mt-0.5">
                    @if($belumDibaca > 0)
                        {{ $belumDibaca }} belum dibaca
                    @else
                        Semua sudah dibaca
                    @endif
                </p>
            </div>

            <div class="flex items-center gap-2">

                @if($belumDibaca > 0)
                    <form method="POST" action="{{ route('notifikasi.baca-semua') }}">
                        @csrf

                        <button type="submit" class="text-xs text-green-600 hover:text-green-800 font-semibold transition">
                            Baca semua
                        </button>
                    </form>
                @endif

                {{-- Close --}}
                <button @click="open = false" type="button" class="w-7 h-7 rounded-xl bg-gray-100 hover:bg-gray-200
                               flex items-center justify-center
                               text-gray-400 hover:text-gray-600 transition">

                    <i data-lucide="x" class="w-4 h-4"></i>

                </button>
            </div>
        </div>

        {{-- List --}}
        <div class="max-h-80 overflow-y-auto divide-y divide-gray-50">

            @forelse($notifs as $notif)

                <div class="flex items-start gap-3 px-4 py-3 transition-colors duration-100
                            {{ !$notif->sudahDibaca() ? 'bg-blue-50/50' : 'hover:bg-gray-50' }}">

                    {{-- Icon --}}
                    <div class="w-9 h-9 rounded-2xl bg-blue-100
                                flex items-center justify-center
                                flex-shrink-0 mt-0.5">

                        <i data-lucide="{{ $notif->icon ?? 'bell' }}" class="w-4 h-4 text-blue-600"></i>

                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">

                        <p class="text-sm font-semibold text-gray-800 leading-snug">
                            {{ $notif->judul }}
                        </p>

                        <p class="text-xs text-gray-500 mt-1 leading-relaxed break-words">
                            {{ $notif->pesan }}
                        </p>

                        <p class="text-[10px] text-gray-300 mt-1.5">
                            {{ $notif->created_at->diffForHumans() }}
                        </p>
                    </div>

                    {{-- Action --}}
                    <div class="flex flex-col gap-2 flex-shrink-0 mt-0.5">

                        @if(!$notif->sudahDibaca())
                            <form method="POST" action="{{ route('notifikasi.baca', $notif) }}">
                                @csrf

                                <button type="submit" title="Tandai dibaca" class="w-6 h-6 rounded-full bg-blue-100 hover:bg-blue-200
                                           flex items-center justify-center transition">

                                    <i data-lucide="check" class="w-3 h-3 text-blue-600"></i>

                                </button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('notifikasi.hapus', $notif) }}">
                            @csrf
                            @method('DELETE')

                            <button type="submit" title="Hapus notifikasi" class="w-6 h-6 rounded-full bg-gray-100 hover:bg-red-100
                                       flex items-center justify-center
                                       text-gray-400 hover:text-red-500 transition">

                                <i data-lucide="trash-2" class="w-3 h-3"></i>

                            </button>
                        </form>

                    </div>
                </div>

            @empty

                <div class="py-12 text-center">

                    <div class="w-16 h-16 mx-auto rounded-2xl bg-gray-100
                                flex items-center justify-center mb-4">

                        <i data-lucide="bell-off" class="w-8 h-8 text-gray-300"></i>

                    </div>

                    <p class="text-sm font-semibold text-gray-500">
                        Belum ada notifikasi
                    </p>

                    <p class="text-xs text-gray-300 mt-1">
                        Notifikasi akan muncul di sini
                    </p>
                </div>

            @endforelse

        </div>
    </div>
</div>