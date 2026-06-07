@php
    $active = $active ?? 'home';

    $menus = [
        [
            'label' => 'Beranda',
            'url' => url('/#beranda'),
            'key' => 'beranda',
        ],
        [
            'label' => 'Persyaratan',
            'url' => url('/#persyaratan'),
            'key' => 'persyaratan',
        ],
        [
            'label' => 'Jadwal',
            'url' => url('/#jadwal'),
            'key' => 'jadwal',
        ],
        [
            'label' => 'Pengumuman',
            'url' => route('public.results'),
            'key' => 'results',
        ],
    ];
@endphp

<header class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur">
    <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-6 lg:px-8">
        <a href="{{ url('/') }}" class="flex items-center gap-3">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-900">
                <img
                    src="{{ asset('images/LOGO DUTA.png') }}"
                    alt="Logo Duta PNJ"
                    class="h-8 w-8 object-contain"
                >
            </div>

            <div>
                <p class="text-xl font-extrabold leading-tight text-blue-900">
                    Duta PNJ
                </p>
                <p class="text-xs font-semibold text-slate-500">
                    Sistem Seleksi Mahasiswa
                </p>
            </div>
        </a>

        <nav class="hidden items-center gap-8 text-sm font-bold md:flex">
            @foreach ($menus as $menu)
                @php
                    $isActive = $active === $menu['key'] || ($active === 'home' && $menu['key'] === 'beranda');
                @endphp

                <a
                    href="{{ $menu['url'] }}"
                    data-public-nav="{{ $menu['key'] }}"
                    class="border-b-2 pb-1 transition hover:text-blue-900 {{ $isActive ? 'border-blue-900 text-blue-900' : 'border-transparent text-slate-600' }}"
                >
                    {{ $menu['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="flex items-center gap-3">
            <a
                href="{{ route('login') }}"
                class="hidden text-sm font-extrabold text-blue-900 transition hover:text-blue-800 sm:inline"
            >
                Login
            </a>

            <a
                href="{{ route('registration') }}"
                class="{{ $active === 'registration'
                    ? 'inline-flex h-11 items-center justify-center rounded-xl bg-yellow-300 px-5 text-sm font-extrabold text-blue-900 shadow-sm transition hover:bg-yellow-400'
                    : 'inline-flex h-11 items-center justify-center rounded-xl bg-blue-900 px-5 text-sm font-extrabold text-white shadow-sm transition hover:bg-blue-800'
                }}"
            >
                Daftar
            </a>
        </div>
    </div>
</header>