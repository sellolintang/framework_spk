<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin - Duta PNJ' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="min-h-screen bg-[#F5F7FB] text-slate-900">
    <div id="adminSidebarOverlay" class="fixed inset-0 z-40 hidden bg-slate-900/40 lg:hidden"></div>

    @include('partials.sidebar-admin', ['mode' => 'desktop'])
    @include('partials.sidebar-admin', ['mode' => 'mobile'])

    @include('partials.navbar')

    <main class="min-h-screen pt-13.5 lg:pl-64.5">
        <div class="px-6 py-9 lg:px-7">
            @yield('content')
        </div>

        @include('partials.footer')
    </main>

    @php
        $authUser = auth()->user();
        $authUserData = $authUser
            ? $authUser->only(['id', 'name', 'email', 'role'])
            : null;
    @endphp

    <script>
        window.DutaAdmin = {
            apiBase: "{{ url('/api') }}",
            loginUrl: "{{ route('login') }}",
            logoutUrl: "{{ route('logout') }}",
            csrfToken: "{{ csrf_token() }}",
            user: @json($authUserData),

            getUser() {
                return this.user;
            },

            clearAuth() {
                localStorage.removeItem('duta_kampus_token');
                localStorage.removeItem('duta_kampus_user');
                localStorage.removeItem('auth_token');
                localStorage.removeItem('access_token');
                localStorage.removeItem('token');
            },

            guard() {
                const user = this.getUser();

                if (!user || user.role !== 'admin') {
                    window.location.replace(this.loginUrl);
                    return false;
                }

                return true;
            },

            headers() {
                return {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                };
            },

            async request(endpoint, options = {}) {
                const response = await fetch(`${this.apiBase}${endpoint}`, {
                    ...options,
                    credentials: 'same-origin',
                    headers: {
                        ...this.headers(),
                        ...(options.headers || {}),
                    },
                });

                if (response.status === 401 || response.status === 419) {
                    console.warn('API 401: session belum terbaca oleh endpoint API.');
                    return null;
                }

                const result = await response.json().catch(() => null);

                if (!response.ok) {
                    throw result || {
                        message: 'Terjadi kesalahan saat mengambil data.',
                    };
                }

                return result;
            },

            async logout() {
                try {
                    await fetch(this.logoutUrl, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'text/html,application/xhtml+xml',
                            'X-CSRF-TOKEN': this.csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });
                } catch (error) {
                    console.error(error);
                } finally {
                    this.clearAuth();
                    window.location.href = this.loginUrl;
                }
            },
        };

        //DutaAdmin.guard();

        document.addEventListener('DOMContentLoaded', function () {
            const user = DutaAdmin.getUser();

            if (user) {
                const name = user.name || 'Admin';
                const role = user.role === 'admin' ? 'Administrator' : user.role;
                const initial = name.charAt(0).toUpperCase();

                const navbarUserName = document.getElementById('navbarUserName');
                const navbarUserRole = document.getElementById('navbarUserRole');
                const navbarUserInitial = document.getElementById('navbarUserInitial');

                if (navbarUserName) navbarUserName.textContent = name;
                if (navbarUserRole) navbarUserRole.textContent = role;
                if (navbarUserInitial) navbarUserInitial.textContent = initial;
            }

            const toggleButton = document.getElementById('adminSidebarToggle');
            const closeButton = document.getElementById('adminSidebarClose');
            const mobileSidebar = document.getElementById('adminSidebarMobile');
            const overlay = document.getElementById('adminSidebarOverlay');

            function openSidebar() {
                mobileSidebar?.classList.remove('hidden');
                overlay?.classList.remove('hidden');
            }

            function closeSidebar() {
                mobileSidebar?.classList.add('hidden');
                overlay?.classList.add('hidden');
            }

            toggleButton?.addEventListener('click', openSidebar);
            closeButton?.addEventListener('click', closeSidebar);
            overlay?.addEventListener('click', closeSidebar);
        });

        function openModal(id) {
            document.getElementById(id)?.classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id)?.classList.add('hidden');
        }

        async function loadPeriodOptions(selectId = 'periodIdInput') {
            const select = document.getElementById(selectId);

            if (!select || !window.DutaAdmin) return;

            const currentValue = select.value || '1';

            try {
                const result = await DutaAdmin.request('/periods');

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
