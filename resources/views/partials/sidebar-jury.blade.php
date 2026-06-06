@php
    $menus = [
        [
            'label' => 'Dashboard',
            'url' => url('/jury/dashboard'),
            'active' => 'jury/dashboard',
            'icon' => 'dashboard',
        ],
        [
            'label' => 'Penilaian Peserta',
            'url' => url('/jury/scoring'),
            'active' => 'jury/scoring*',
            'icon' => 'score',
        ],
        [
            'label' => 'Riwayat Penilaian',
            'url' => url('/jury/history'),
            'active' => 'jury/history*',
            'icon' => 'history',
        ],
    ];
@endphp

<aside class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col border-r border-slate-200 bg-white">
    <div class="border-b border-slate-100 px-6 py-5">
        <a href="{{ url('/jury/dashboard') }}" class="block">
            <h1 class="text-xl font-extrabold leading-tight text-[#00288E]">
                Duta PNJ
            </h1>
            <p class="mt-1 text-sm font-medium text-slate-500">
                Portal Juri
            </p>
        </a>
    </div>

    <nav class="flex-1 space-y-1 px-4 py-5">
        @foreach ($menus as $menu)
            @php
                $isActive = request()->is($menu['active']);
            @endphp

            <a
                href="{{ $menu['url'] }}"
                class="{{ $isActive
                    ? 'bg-yellow-400 text-slate-900'
                    : 'text-slate-600 hover:bg-slate-50 hover:text-[#00288E]'
                }} flex items-center gap-3 rounded-md px-4 py-3 text-sm font-bold transition"
            >
                @if ($menu['icon'] === 'dashboard')
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                        <path d="M4 4h7v7H4V4ZM13 4h7v7h-7V4ZM4 13h7v7H4v-7ZM13 13h7v7h-7v-7Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                    </svg>
                @elseif ($menu['icon'] === 'score')
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                        <path d="M9 11l2 2 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M5 4h14v16H5V4Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                    </svg>
                @else
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                        <path d="M12 8v5l3 2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M21 12a9 9 0 1 1-2.64-6.36" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M21 4v6h-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                @endif

                <span>{{ $menu['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="border-t border-slate-100 px-4 py-4">
        <div class="mb-4 rounded-xl bg-slate-50 px-4 py-3">
            <p id="jurySidebarName" class="text-sm font-extrabold text-slate-900">
                Juri
            </p>
            <p id="jurySidebarEmail" class="mt-1 truncate text-xs text-slate-500">
                -
            </p>
        </div>

        <button
            type="button"
            onclick="logoutJury()"
            class="flex w-full items-center gap-3 rounded-md px-4 py-3 text-sm font-bold text-red-600 hover:bg-red-50"
        >
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                <path d="M16 17l5-5-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M21 12H9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M13 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Keluar
        </button>
    </div>
</aside>