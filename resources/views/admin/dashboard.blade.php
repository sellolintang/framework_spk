<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Duta PNJ</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#F5F7FB] text-slate-900">
    <div class="min-h-screen">

        <!-- Topbar -->
        <header class="fixed left-0 right-0 top-0 z-40 h-[54px] border-b border-slate-200 bg-white">
            <div class="flex h-full items-center justify-between px-5">
                <a href="{{ url('/') }}" class="text-[21px] font-extrabold tracking-tight text-[#00288E]">
                    Duta PNJ
                </a>

                <div class="hidden w-[520px] items-center rounded-md border border-slate-300 bg-slate-50 px-3 py-2 md:flex">
                    <svg class="mr-2 h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none">
                        <path d="M21 21L16.65 16.65M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <input
                        id="searchInput"
                        type="text"
                        placeholder="Cari data pendaftar..."
                        class="w-full bg-transparent text-sm outline-none placeholder:text-slate-400"
                    >
                </div>

                <div class="flex items-center gap-5">
                    <button class="text-slate-600 hover:text-[#00288E]" title="Notifikasi">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                            <path d="M18 8A6 6 0 0 0 6 8C6 15 3 17 3 17H21C21 17 18 15 18 8Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13.73 21A2 2 0 0 1 10.27 21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>

                    <button class="text-slate-600 hover:text-[#00288E]" title="Bantuan">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                            <path d="M9.75 9A2.25 2.25 0 0 1 14 10.05C14 12 12 12.25 12 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M12 17H12.01" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                        </svg>
                    </button>

                    <div class="h-8 w-8 overflow-hidden rounded-full bg-slate-800">
                        <div class="flex h-full w-full items-center justify-center text-xs font-bold text-white">
                            A
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Sidebar -->
        <aside class="fixed bottom-0 left-0 top-[54px] z-30 hidden w-[258px] border-r border-slate-200 bg-white lg:block">
            <div class="flex h-full flex-col justify-between px-4 py-7">
                <div>
                    <div class="mb-7 px-2">
                        <h1 class="text-[21px] font-extrabold leading-tight text-[#00288E]">Duta PNJ</h1>
                        <p class="mt-1 text-sm font-medium text-slate-500">Sistem Seleksi Mahasiswa</p>
                    </div>

                    <nav class="space-y-1">
                        <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-3 rounded-md bg-[#FFC74A] px-4 py-3 text-sm font-semibold text-slate-800">
                            <span class="inline-flex h-5 w-5 items-center justify-center">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                                    <path d="M4 4H10V10H4V4Z" stroke="currentColor" stroke-width="2"/>
                                    <path d="M14 4H20V10H14V4Z" stroke="currentColor" stroke-width="2"/>
                                    <path d="M4 14H10V20H4V14Z" stroke="currentColor" stroke-width="2"/>
                                    <path d="M14 14H20V20H14V14Z" stroke="currentColor" stroke-width="2"/>
                                </svg>
                            </span>
                            Dashboard
                        </a>

                        <a href="#" class="nav-link">
                            <span class="nav-icon">
                                <svg viewBox="0 0 24 24" fill="none"><path d="M16 11C17.657 11 19 9.657 19 8C19 6.343 17.657 5 16 5M8 11C9.657 11 11 9.657 11 8C11 6.343 9.657 5 8 5C6.343 5 5 6.343 5 8C5 9.657 6.343 11 8 11ZM8 13C5.239 13 3 15.239 3 18V19H13V18C13 15.239 10.761 13 8 13ZM16 13C14.948 13 13.977 13.326 13.177 13.882C14.296 14.976 15 16.504 15 18V19H21V18C21 15.239 18.761 13 16 13Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </span>
                            Data Pendaftar
                        </a>

                        <a href="#" class="nav-link">
                            <span class="nav-icon">
                                <svg viewBox="0 0 24 24" fill="none"><path d="M7 3V6M17 3V6M4 9H20M5 5H19C19.552 5 20 5.448 20 6V20C20 20.552 19.552 21 19 21H5C4.448 21 4 20.552 4 20V6C4 5.448 4.448 5 5 5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                            </span>
                            Jadwal Wawancara
                        </a>

                        <a href="#" class="nav-link">
                            <span class="nav-icon">
                                <svg viewBox="0 0 24 24" fill="none"><path d="M4 19H20M6 17L15 8M9 6L18 15M5 14L10 19M14 5L19 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                            </span>
                            Akun Juri
                        </a>

                        <a href="#" class="nav-link">
                            <span class="nav-icon">
                                <svg viewBox="0 0 24 24" fill="none"><path d="M4 5H20V19H4V5Z" stroke="currentColor" stroke-width="2"/><path d="M8 9H16M8 13H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                            </span>
                            Kriteria
                        </a>

                        <a href="#" class="nav-link">
                            <span class="nav-icon">
                                <svg viewBox="0 0 24 24" fill="none"><path d="M4 19V13M10 19V9M16 19V5M22 19H2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                            </span>
                            Monitoring Penilaian
                        </a>

                        <a href="#" class="nav-link">
                            <span class="nav-icon">
                                <svg viewBox="0 0 24 24" fill="none"><path d="M5 20H19M7 20V10M12 20V4M17 20V14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                            </span>
                            Hasil ARAS
                        </a>

                        <a href="#" class="nav-link">
                            <span class="nav-icon">
                                <svg viewBox="0 0 24 24" fill="none"><path d="M4 14V10L15 5V19L4 14Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M4 14L6 20H9L7 15" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
                            </span>
                            Pengumuman
                        </a>
                    </nav>
                </div>

                <button onclick="logout()" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-red-600 hover:text-red-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                        <path d="M15 17L20 12L15 7M20 12H9M11 21H5C4.448 21 4 20.552 4 20V4C4 3.448 4.448 3 5 3H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Keluar
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="pt-[54px] lg:pl-[258px]">
            <div class="px-6 py-9 lg:px-7">
                <section class="mb-7">
                    <h2 class="text-[36px] font-extrabold leading-none tracking-tight text-[#00288E]">
                        Ringkasan Dashboard
                    </h2>
                    <p class="mt-2 text-sm font-medium text-slate-500">
                        Selamat datang kembali, Admin Seleksi Duta PNJ <span id="dashboardYear">{{ now()->year }}</span>.
                    </p>
                </section>

                <!-- Stat Cards -->
                <section class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="stat-card">
                        <div class="stat-icon bg-blue-50 text-[#00288E]">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M16 11C17.657 11 19 9.657 19 8M8 11C9.657 11 11 9.657 11 8C11 6.343 9.657 5 8 5C6.343 5 5 6.343 5 8C5 9.657 6.343 11 8 11ZM8 13C5.239 13 3 15.239 3 18V19H13V18C13 15.239 10.761 13 8 13Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        </div>
                        <span class="rounded-full bg-blue-50 px-2 py-1 text-[11px] font-bold text-[#00288E]">+12%</span>
                        <p class="mt-3 text-sm font-semibold text-slate-700">Total Pendaftar</p>
                        <h3 id="totalCandidates" class="text-[25px] font-extrabold leading-tight text-slate-900">0</h3>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon bg-yellow-50 text-yellow-700">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M7 4H17V20H7V4Z" stroke="currentColor" stroke-width="2"/><path d="M9 8H15M9 12H14M9 16H12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        </div>
                        <p class="mt-7 text-sm font-semibold text-slate-700">Menunggu Verifikasi</p>
                        <h3 id="pendingCandidates" class="text-[25px] font-extrabold leading-tight text-slate-900">0</h3>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon bg-green-50 text-green-700">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <p class="mt-7 text-sm font-semibold text-slate-700">Lolos Administrasi</p>
                        <h3 id="validCandidates" class="text-[25px] font-extrabold leading-tight text-slate-900">0</h3>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon bg-red-50 text-red-600">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
                        </div>
                        <p class="mt-7 text-sm font-semibold text-slate-700">Ditolak</p>
                        <h3 id="invalidCandidates" class="text-[25px] font-extrabold leading-tight text-slate-900">0</h3>
                    </div>
                </section>

                <section class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="stat-card">
                        <div class="stat-icon bg-blue-50 text-[#00288E]">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M7 3V6M17 3V6M4 9H20M5 5H19V21H5V5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        </div>
                        <p class="mt-7 text-sm font-semibold text-slate-700">Dijadwalkan</p>
                        <h3 id="scheduledCandidates" class="text-[25px] font-extrabold leading-tight text-slate-900">0</h3>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon bg-yellow-50 text-yellow-700">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M4 5H20V17H8L4 21V5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M8 9H16M8 13H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        </div>
                        <p class="mt-7 text-sm font-semibold text-slate-700">Sudah Dinilai</p>
                        <h3 id="scoredCandidates" class="text-[25px] font-extrabold leading-tight text-slate-900">0</h3>
                    </div>

                    <div class="rounded-xl bg-[#00288E] p-5 text-white shadow-sm md:col-span-2">
                        <div class="flex items-start justify-between">
                            <div class="flex h-9 w-9 items-center justify-center rounded-md bg-white/15">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                                    <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                                    <path d="M12 8H12.01M11 12H12V17H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </div>

                            <span class="rounded-full bg-white/15 px-3 py-1 text-[12px] font-semibold">
                                Sistem Aktif
                            </span>
                        </div>

                        <p class="mt-5 text-sm font-medium text-blue-100">Status Pengumuman</p>
                        <h3 class="text-[24px] font-extrabold leading-tight">Belum Dibuka</h3>
                    </div>
                </section>

                <!-- Progress -->
                <section class="mt-10">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-[24px] font-extrabold text-slate-800">Progress Seleksi</h3>
                        <a href="#" class="text-sm font-bold text-[#00288E] hover:underline">Detail Alur ›</a>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white px-8 py-7 shadow-sm">
                        <div class="flex items-start justify-between">
                            <div class="progress-step active">
                                <span>1</span>
                                <p>Pendaftaran</p>
                            </div>
                            <div class="progress-line active"></div>

                            <div class="progress-step active">
                                <span>2</span>
                                <p>Verifikasi</p>
                            </div>
                            <div class="progress-line"></div>

                            <div class="progress-step">
                                <span>3</span>
                                <p>Jadwal Wawancara</p>
                            </div>
                            <div class="progress-line"></div>

                            <div class="progress-step">
                                <span>4</span>
                                <p>Penilaian Juri</p>
                            </div>
                            <div class="progress-line"></div>

                            <div class="progress-step">
                                <span>5</span>
                                <p>Perhitungan ARAS</p>
                            </div>
                            <div class="progress-line"></div>

                            <div class="progress-step">
                                <span>6</span>
                                <p>Pengumuman</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Latest Candidates -->
                <section class="mt-10">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-[24px] font-extrabold text-slate-800">Pendaftar Terbaru</h3>

                        <div class="flex items-center gap-2">
                            <button class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Filter
                            </button>
                            <button class="rounded-md bg-[#00288E] px-4 py-2 text-sm font-bold text-white hover:bg-[#001F73]">
                                Ekspor Data
                            </button>
                        </div>
                    </div>

                    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left">
                                <thead class="bg-slate-50">
                                    <tr class="border-b border-slate-200 text-[12px] uppercase tracking-wider text-slate-600">
                                        <th class="px-6 py-4 font-extrabold">Nama</th>
                                        <th class="px-6 py-4 font-extrabold">NIM</th>
                                        <th class="px-6 py-4 font-extrabold">Program Studi</th>
                                        <th class="px-6 py-4 font-extrabold">Status</th>
                                        <th class="px-6 py-4 font-extrabold">Tanggal Daftar</th>
                                        <th class="px-6 py-4 text-right font-extrabold">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody id="latestCandidatesTable" class="divide-y divide-slate-200 text-sm">
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                                            Memuat data pendaftar...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="flex items-center justify-between bg-slate-50 px-6 py-4 text-sm text-slate-600">
                            <span id="tableInfo">Menampilkan data pendaftar terbaru</span>

                            <div class="flex items-center gap-1">
                                <button class="flex h-6 w-6 items-center justify-center rounded border border-slate-300 text-slate-400">
                                    ‹
                                </button>
                                <button class="flex h-6 w-6 items-center justify-center rounded border border-slate-300 text-slate-500">
                                    ›
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <style>
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-radius: 0.375rem;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #334155;
            transition: 150ms ease;
        }

        .nav-link:hover {
            background: #F1F5F9;
            color: #00288E;
        }

        .nav-icon {
            display: inline-flex;
            width: 1.25rem;
            height: 1.25rem;
            align-items: center;
            justify-content: center;
        }

        .nav-icon svg {
            width: 1.25rem;
            height: 1.25rem;
        }

        .stat-card {
            position: relative;
            min-height: 128px;
            border-radius: 0.75rem;
            border: 1px solid #E2E8F0;
            background: white;
            padding: 1rem;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }

        .stat-icon {
            display: inline-flex;
            height: 2.25rem;
            width: 2.25rem;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
        }

        .stat-icon svg {
            height: 1.25rem;
            width: 1.25rem;
        }

        .stat-card > span {
            position: absolute;
            right: 1rem;
            top: 1rem;
        }

        .progress-step {
            width: 130px;
            text-align: center;
        }

        .progress-step span {
            display: inline-flex;
            height: 38px;
            width: 38px;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: #CBD0E1;
            color: white;
            font-weight: 800;
        }

        .progress-step p {
            margin-top: 0.75rem;
            font-size: 0.875rem;
            font-weight: 700;
            color: #475569;
            white-space: nowrap;
        }

        .progress-step.active span {
            background: #00288E;
        }

        .progress-step.active p {
            color: #00288E;
        }

        .progress-line {
            margin-top: 19px;
            height: 2px;
            flex: 1;
            background: #CBD5E1;
        }

        .progress-line.active {
            background: #00288E;
        }

        @media (max-width: 1024px) {
            .progress-step p {
                white-space: normal;
                font-size: 0.75rem;
            }
        }
    </style>

    <script>
        const token = localStorage.getItem('duta_kampus_token');
        const user = JSON.parse(localStorage.getItem('duta_kampus_user') || 'null');

        if (!token || !user) {
            window.location.href = "{{ url('/login') }}";
        }

        if (user && user.role !== 'admin') {
            window.location.href = "{{ url('/login') }}";
        }

        const apiBase = "{{ url('/api') }}";

        document.addEventListener('DOMContentLoaded', function () {
            loadDashboardData();

            const searchInput = document.getElementById('searchInput');
            let searchTimer = null;

            searchInput?.addEventListener('input', function () {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => {
                    loadLatestCandidates(searchInput.value);
                }, 500);
            });
        });

        async function apiGet(endpoint) {
            const response = await fetch(`${apiBase}${endpoint}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`,
                },
            });

            if (response.status === 401) {
                localStorage.removeItem('duta_kampus_token');
                localStorage.removeItem('duta_kampus_user');
                window.location.href = "{{ url('/login') }}";
                return null;
            }

            return await response.json();
        }

        async function loadDashboardData() {
            try {
                await Promise.all([
                    loadCandidateStats(),
                    loadLatestCandidates(),
                ]);
            } catch (error) {
                console.error(error);
                renderTableError('Gagal memuat data dashboard.');
            }
        }

        async function getCandidateTotal(query = '') {
            const separator = query ? `&${query}` : '';
            const result = await apiGet(`/candidates?per_page=1${separator}`);
            return result?.data?.total ?? 0;
        }

        async function loadCandidateStats() {
            const [
                total,
                pending,
                valid,
                invalid,
                scheduled,
                scored,
            ] = await Promise.all([
                getCandidateTotal(),
                getCandidateTotal('status=pending'),
                getCandidateTotal('status=valid'),
                getCandidateTotal('status=invalid'),
                getCandidateTotal('status=interview_scheduled'),
                getCandidateTotal('status=scored'),
            ]);

            document.getElementById('totalCandidates').textContent = total;
            document.getElementById('pendingCandidates').textContent = pending;
            document.getElementById('validCandidates').textContent = valid;
            document.getElementById('invalidCandidates').textContent = invalid;
            document.getElementById('scheduledCandidates').textContent = scheduled;
            document.getElementById('scoredCandidates').textContent = scored;
        }

        async function loadLatestCandidates(keyword = '') {
            const tableBody = document.getElementById('latestCandidatesTable');
            const tableInfo = document.getElementById('tableInfo');

            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                        Memuat data pendaftar...
                    </td>
                </tr>
            `;

            const keywordQuery = keyword ? `&keyword=${encodeURIComponent(keyword)}` : '';
            const result = await apiGet(`/candidates?per_page=3${keywordQuery}`);

            const candidates = result?.data?.data ?? [];
            const total = result?.data?.total ?? 0;

            if (candidates.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                            Belum ada data pendaftar.
                        </td>
                    </tr>
                `;
                tableInfo.textContent = 'Menampilkan 0 data pendaftar';
                return;
            }

            tableBody.innerHTML = candidates.map(candidate => {
                const initial = getInitial(candidate.full_name);
                const statusBadge = renderStatusBadge(candidate.status);
                const createdAt = formatDate(candidate.created_at);

                return `
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full ${avatarClass(candidate.status)} text-xs font-extrabold">
                                    ${initial}
                                </div>
                                <span class="font-extrabold text-slate-900">${escapeHtml(candidate.full_name ?? '-')}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">${escapeHtml(candidate.student_number ?? '-')}</td>
                        <td class="px-6 py-4 text-slate-600">${escapeHtml(candidate.study_program ?? '-')}</td>
                        <td class="px-6 py-4">${statusBadge}</td>
                        <td class="px-6 py-4 text-slate-600">${createdAt}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="#" class="font-extrabold text-[#00288E] hover:underline">Detail</a>
                        </td>
                    </tr>
                `;
            }).join('');

            tableInfo.textContent = `Menampilkan ${candidates.length} dari ${total} pendaftar`;
        }

        function renderTableError(message) {
            document.getElementById('latestCandidatesTable').innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-red-600">
                        ${message}
                    </td>
                </tr>
            `;
        }

        async function logout() {
            try {
                await fetch(`${apiBase}/logout`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`,
                    },
                });
            } catch (error) {
                console.error(error);
            } finally {
                localStorage.removeItem('duta_kampus_token');
                localStorage.removeItem('duta_kampus_user');
                window.location.href = "{{ url('/login') }}";
            }
        }

        function getInitial(name) {
            if (!name) return '-';

            return name
                .split(' ')
                .filter(Boolean)
                .slice(0, 2)
                .map(word => word[0])
                .join('')
                .toUpperCase();
        }

        function renderStatusBadge(status) {
            const labels = {
                pending: 'Menunggu',
                valid: 'Terverifikasi',
                invalid: 'Berkas Tidak Sesuai',
                interview_scheduled: 'Dijadwalkan',
                interviewed: 'Sudah Wawancara',
                scored: 'Sudah Dinilai',
            };

            const classes = {
                pending: 'bg-yellow-100 text-yellow-700',
                valid: 'bg-green-100 text-green-700',
                invalid: 'bg-red-100 text-red-700',
                interview_scheduled: 'bg-blue-100 text-blue-700',
                interviewed: 'bg-indigo-100 text-indigo-700',
                scored: 'bg-purple-100 text-purple-700',
            };

            return `
                <span class="rounded-full px-3 py-1 text-xs font-bold ${classes[status] ?? 'bg-slate-100 text-slate-600'}">
                    ${labels[status] ?? status ?? '-'}
                </span>
            `;
        }

        function avatarClass(status) {
            const classes = {
                pending: 'bg-yellow-100 text-yellow-700',
                valid: 'bg-green-100 text-green-700',
                invalid: 'bg-red-100 text-red-700',
                interview_scheduled: 'bg-blue-100 text-blue-700',
                interviewed: 'bg-indigo-100 text-indigo-700',
                scored: 'bg-purple-100 text-purple-700',
            };

            return classes[status] ?? 'bg-slate-100 text-slate-700';
        }

        function formatDate(dateString) {
            if (!dateString) return '-';

            const date = new Date(dateString);

            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
            });
        }

        function escapeHtml(value) {
            return String(value)
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }
    </script>
</body>
</html>
