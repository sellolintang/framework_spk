@php
    $mode = $mode ?? 'desktop';
    $isMobile = $mode === 'mobile';

    $asideId = $isMobile ? 'adminSidebarMobile' : 'adminSidebarDesktop';

    $asideClass = $isMobile
        ? 'fixed bottom-0 left-0 top-0 z-50 hidden w-[258px] border-r border-slate-200 bg-white lg:hidden'
        : 'fixed bottom-0 left-0 top-[54px] z-30 hidden w-[258px] border-r border-slate-200 bg-white lg:block';

    $menus = [
        [
            'label' => 'Dashboard',
            'url' => url('/admin/dashboard'),
            'active' => 'admin/dashboard*',
            'icon' => 'grid',
        ],
        [
            'label' => 'Data Pendaftar',
            'url' => url('/admin/candidates'),
            'active' => 'admin/candidates*',
            'icon' => 'users',
        ],
        [
            'label' => 'Jadwal Wawancara',
            'url' => url('/admin/interviews'),
            'active' => 'admin/interviews*',
            'icon' => 'calendar',
        ],
        [
            'label' => 'Akun Juri',
            'url' => url('/admin/juries'),
            'active' => 'admin/juries*',
            'icon' => 'jury',
        ],
        [
            'label' => 'Kriteria',
            'url' => url('/admin/criteria'),
            'active' => 'admin/criteria*',
            'icon' => 'criteria',
        ],
        [
            'label' => 'Monitoring Penilaian',
            'url' => url('/admin/monitoring'),
            'active' => 'admin/monitoring*',
            'icon' => 'chart',
        ],
        [
            'label' => 'Hasil ARAS',
            'url' => url('/admin/aras'),
            'active' => 'admin/aras*',
            'icon' => 'rank',
        ],
        [
            'label' => 'Pengumuman',
            'url' => url('/admin/announcements'),
            'active' => 'admin/announcements*',
            'icon' => 'announcement',
        ],
    ];
@endphp

<aside id="{{ $asideId }}" class="{{ $asideClass }}">
    <div class="flex h-full flex-col justify-between px-4 py-7">
        <div>
            <div class="mb-7 flex items-start justify-between px-2">
                <div>
                    <h1 class="text-[21px] font-extrabold leading-tight text-[#00288E]">
                        Duta PNJ
                    </h1>
                    <p class="mt-1 text-sm font-medium text-slate-500">
                        Sistem Seleksi Mahasiswa
                    </p>
                </div>

                @if ($isMobile)
                    <button type="button" id="adminSidebarClose" class="rounded-md p-1 text-slate-500 hover:bg-slate-100">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                            <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                        </svg>
                    </button>
                @endif
            </div>

            <nav class="space-y-1">
                @foreach ($menus as $menu)
                    @php
                        $active = request()->is($menu['active']);
                    @endphp

                    <a href="{{ $menu['url'] }}"
                       class="flex items-center gap-3 rounded-md px-4 py-3 text-sm font-semibold transition
                       {{ $active ? 'bg-[#FFC74A] text-slate-800' : 'text-slate-600 hover:bg-slate-100 hover:text-[#00288E]' }}">

                        <span class="inline-flex h-5 w-5 items-center justify-center">
                            @if ($menu['icon'] === 'grid')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                                    <path d="M4 4H10V10H4V4Z" stroke="currentColor" stroke-width="2"/>
                                    <path d="M14 4H20V10H14V4Z" stroke="currentColor" stroke-width="2"/>
                                    <path d="M4 14H10V20H4V14Z" stroke="currentColor" stroke-width="2"/>
                                    <path d="M14 14H20V20H14V14Z" stroke="currentColor" stroke-width="2"/>
                                </svg>
                            @elseif ($menu['icon'] === 'users')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                                    <path d="M16 11C17.657 11 19 9.657 19 8M8 11C9.657 11 11 9.657 11 8C11 6.343 9.657 5 8 5C6.343 5 5 6.343 5 8C5 9.657 6.343 11 8 11ZM8 13C5.239 13 3 15.239 3 18V19H13V18C13 15.239 10.761 13 8 13Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            @elseif ($menu['icon'] === 'calendar')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                                    <path d="M7 3V6M17 3V6M4 9H20M5 5H19C19.552 5 20 5.448 20 6V20C20 20.552 19.552 21 19 21H5C4.448 21 4 20.552 4 20V6C4 5.448 4.448 5 5 5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            @elseif ($menu['icon'] === 'jury')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                                    <path d="M4 19H20M6 17L15 8M9 6L18 15M5 14L10 19M14 5L19 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            @elseif ($menu['icon'] === 'criteria')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                                    <path d="M4 5H20V19H4V5Z" stroke="currentColor" stroke-width="2"/>
                                    <path d="M8 9H16M8 13H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            @elseif ($menu['icon'] === 'chart')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                                    <path d="M4 19V13M10 19V9M16 19V5M22 19H2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            @elseif ($menu['icon'] === 'rank')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                                    <path d="M5 20H19M7 20V10M12 20V4M17 20V14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            @else
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                                    <path d="M4 14V10L15 5V19L4 14Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                    <path d="M4 14L6 20H9L7 15" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                </svg>
                            @endif
                        </span>

                        {{ $menu['label'] }}
                    </a>
                @endforeach
            </nav>
        </div>

        <button
            type="button"
            onclick="DutaAdmin.logout()"
            class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-red-600 hover:text-red-700"
        >
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                <path d="M15 17L20 12L15 7M20 12H9M11 21H5C4.448 21 4 20.552 4 20V4C4 3.448 4.448 3 5 3H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Keluar
        </button>
    </div>
</aside>