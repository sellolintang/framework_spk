<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Juri - Duta PNJ' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 text-slate-900">
    @include('partials.sidebar-jury')

    <div class="min-h-screen pl-64">
        <header class="sticky top-0 z-30 border-b border-slate-200 bg-white">
            <div class="flex h-16 items-center justify-between px-6">
                <div class="w-full max-w-xl">
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none">
                            <path d="M21 21l-4.35-4.35" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/>
                        </svg>

                        <input
                            type="text"
                            placeholder="Cari data..."
                            class="h-10 w-full rounded-md border border-slate-300 bg-slate-50 pl-10 pr-4 text-sm outline-none focus:border-[#00288E] focus:ring-2 focus:ring-blue-100"
                        >
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p id="juryTopbarName" class="text-sm font-extrabold text-slate-900">
                            Juri
                        </p>
                        <p class="text-xs font-medium text-slate-500">
                            Tim Penilai
                        </p>
                    </div>

                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#00288E] text-sm font-extrabold text-white">
                        J
                    </div>
                </div>
            </div>
        </header>

        <main class="px-8 py-8">
            @yield('content')
        </main>
    </div>

    @php
        $authUser = auth()->user();
        $authUserData = $authUser
            ? $authUser->only(['id', 'name', 'email', 'role'])
            : null;
    @endphp

    <script>
        window.DutaJury = {
            apiBase: "{{ url('/api') }}",
            loginUrl: "{{ route('login') }}",
            logoutUrl: "{{ route('logout') }}",
            csrfToken: "{{ csrf_token() }}",
            currentUser: @json($authUserData),

            user() {
                return this.currentUser;
            },

            headers() {
                return {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                };
            },

            async request(path, options = {}) {
                const response = await fetch(`${this.apiBase}${path}`, {
                    ...options,
                    credentials: 'same-origin',
                    headers: {
                        ...this.headers(),
                        ...(options.headers || {}),
                    },
                });

                if (response.status === 401 || response.status === 419) {
                    window.location.href = this.loginUrl;
                    throw new Error('Sesi login sudah berakhir.');
                }

                const result = await response.json().catch(() => null);

                if (!response.ok) {
                    const message = result?.errors
                        ? Object.values(result.errors).flat().join(' ')
                        : result?.message;

                    throw new Error(message || 'Terjadi kesalahan.');
                }

                return result;
            },
        };

        document.addEventListener('DOMContentLoaded', function () {
            const user = DutaJury.user();

            if (!user || user.role !== 'juri') {
                window.location.href = DutaJury.loginUrl;
                return;
            }

            const juryTopbarName = document.getElementById('juryTopbarName');
            const jurySidebarName = document.getElementById('jurySidebarName');
            const jurySidebarEmail = document.getElementById('jurySidebarEmail');

            if (juryTopbarName) juryTopbarName.textContent = user.name || 'Juri';
            if (jurySidebarName) jurySidebarName.textContent = user.name || 'Juri';
            if (jurySidebarEmail) jurySidebarEmail.textContent = user.email || '-';
        });

        async function logoutJury() {
            try {
                await fetch(DutaJury.logoutUrl, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'text/html,application/xhtml+xml',
                        'X-CSRF-TOKEN': DutaJury.csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });
            } catch (error) {
                console.error(error);
            } finally {
                localStorage.removeItem('duta_kampus_token');
                localStorage.removeItem('duta_kampus_user');
                localStorage.removeItem('auth_token');
                localStorage.removeItem('access_token');
                localStorage.removeItem('token');

                window.location.href = DutaJury.loginUrl;
            }
        }

        async function loadPeriodOptions(selectId = 'periodIdInput') {
            const select = document.getElementById(selectId);

            if (!select || !window.DutaJury) return;

            const currentValue = select.value || '1';

            try {
                const result = await DutaJury.request('/periods');

                const periods = Array.isArray(result?.data)
                    ? result.data
                    : (result?.data?.data || []);

                if (!periods.length) return;

                select.innerHTML = periods.map(period => `
                    <option value="${period.id}" ${String(period.id) === String(currentValue) ? 'selected' : ''}>
                        ${period.election_year}
                    </option>
                `).join('');
            } catch (error) {
                console.error('Gagal memuat periode:', error);
            }
        }
    </script>

    @stack('scripts')
</body>
</html>