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

    <main class="min-h-screen pt-[54px] lg:pl-[258px]">
        <div class="px-6 py-9 lg:px-7">
            @yield('content')
        </div>

        @include('partials.footer')
    </main>

    <script>
        window.DutaAdmin = {
            apiBase: "{{ url('/api') }}",
            loginUrl: "{{ url('/login') }}",

            getToken() {
                return localStorage.getItem('duta_kampus_token');
            },

            getUser() {
                try {
                    return JSON.parse(localStorage.getItem('duta_kampus_user') || 'null');
                } catch (error) {
                    return null;
                }
            },

            clearAuth() {
                localStorage.removeItem('duta_kampus_token');
                localStorage.removeItem('duta_kampus_user');
            },

            guard() {
                const token = this.getToken();
                const user = this.getUser();

                if (!token || !user || user.role !== 'admin') {
                    this.clearAuth();
                    window.location.replace(this.loginUrl);
                    return false;
                }

                return true;
            },

            headers() {
                return {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${this.getToken()}`,
                };
            },

            async request(endpoint, options = {}) {
                const response = await fetch(`${this.apiBase}${endpoint}`, {
                    ...options,
                    headers: {
                        ...this.headers(),
                        ...(options.headers || {}),
                    },
                });

                if (response.status === 401) {
                    this.clearAuth();
                    window.location.replace(this.loginUrl);
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
                    await fetch(`${this.apiBase}/logout`, {
                        method: 'POST',
                        headers: this.headers(),
                    });
                } catch (error) {
                    console.error(error);
                } finally {
                    this.clearAuth();
                    window.location.href = this.loginUrl;
                }
            },
        };

        DutaAdmin.guard();

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
    </script>

    @stack('scripts')
</body>
</html>
