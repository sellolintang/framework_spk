<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Juri - Duta PNJ</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#F5F7FB] text-slate-900">
    <div class="min-h-screen">

        <!-- Topbar -->
        <header class="fixed left-0 right-0 top-0 z-40 h-[62px] border-b border-slate-300 bg-white">
            <div class="flex h-full items-center justify-between pl-[300px] pr-6">
                <h1 class="text-[26px] font-extrabold text-[#00288E]">
                    Sistem Seleksi Duta PNJ
                </h1>

                <div class="flex items-center gap-5">
                    <div class="hidden h-11 w-[260px] items-center rounded-full border border-slate-300 bg-slate-50 px-4 md:flex">
                        <svg class="mr-2 h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none">
                            <path d="M21 21L16.65 16.65M19 11C19 15.418 15.418 19 11 19C6.582 19 3 15.418 3 11C3 6.582 6.582 3 11 3C15.418 3 19 6.582 19 11Z"
                                  stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>

                        <input
                            id="searchInput"
                            type="text"
                            placeholder="Cari pendaftar..."
                            class="w-full bg-transparent text-sm outline-none placeholder:text-slate-400"
                        >
                    </div>

                    <button class="text-slate-600 hover:text-[#00288E]" title="Notifikasi">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                            <path d="M18 8A6 6 0 0 0 6 8C6 15 3 17 3 17H21C21 17 18 15 18 8Z"
                                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13.73 21A2 2 0 0 1 10.27 21"
                                  stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>

                    <button class="text-slate-600 hover:text-[#00288E]" title="Bantuan">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                            <path d="M9.75 9A2.25 2.25 0 0 1 14 10.05C14 12 12 12.25 12 14"
                                  stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M12 17H12.01" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                        </svg>
                    </button>

                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-800 text-xs font-bold text-white">
                        J
                    </div>
                </div>
            </div>
        </header>

        <!-- Sidebar -->
        <aside class="fixed bottom-0 left-0 top-0 z-50 w-[280px] border-r border-slate-200 bg-white">
            <div class="flex h-full flex-col justify-between">

                <div>
                    <div class="flex items-start gap-3 border-b border-slate-200 px-4 py-5">
                        <div class="flex h-10 w-10 items-center justify-center rounded-md bg-slate-900 text-white">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                                <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-[24px] font-extrabold leading-tight text-[#00288E]">
                                Seleksi Duta<br>PNJ
                            </h2>
                            <p class="mt-1 text-sm font-medium text-slate-500">Portal Juri</p>
                        </div>
                    </div>

                    <nav class="mt-8 space-y-1 px-3">
                        <a href="{{ url('/jury/dashboard') }}"
                           class="flex items-center gap-3 rounded-md border-r-4 border-[#00288E] bg-slate-100 px-4 py-3 text-sm font-semibold text-[#00288E]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                                <path d="M4 4H10V10H4V4Z" fill="currentColor"/>
                                <path d="M14 4H20V10H14V4Z" fill="currentColor"/>
                                <path d="M4 14H10V20H4V14Z" fill="currentColor"/>
                                <path d="M14 14H20V20H14V14Z" fill="currentColor"/>
                            </svg>
                            Dashboard
                        </a>

                        <a href="{{ route('jury.interviews.index') }}"
                           class="flex items-center gap-3 rounded-md px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 hover:text-[#00288E]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                                <path d="M7 3V6M17 3V6M4 9H20M5 5H19V21H5V5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            Jadwal Wawancara
                        </a>

                        <a href="{{ route('jury.candidates.index') }}" class="flex items-center gap-3 rounded-md px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 hover:text-[#00288E]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                                <path d="M4 7H20M4 12H20M4 17H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M8 7H8.01M8 12H8.01M8 17H8.01" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                            </svg>
                            Daftar Calon
                        </a>

                        <a href="{{ route('jury.scores.index') }}" class="flex items-center gap-3 rounded-md px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 hover:text-[#00288E]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                                <path d="M4 7H14C17.314 7 20 9.686 20 13C20 16.314 17.314 19 14 19H6"
                                      stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M8 15L4 19L8 23" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Riwayat Penilaian
                        </a>
                    </nav>
                </div>

                <div class="px-4 pb-6">
                    <div class="mb-8 h-px bg-slate-200"></div>

                    <div class="mb-8 flex items-center gap-3 rounded-xl bg-white px-2 py-2 shadow-sm">
                        <div id="juryInitial">JU</div>
                        <div>
                            <p id="juryName">Memuat...</p>
                            <p id="juryEmail">Memuat email...</p>
                        </div>
                    </div>

                    <button onclick="logout()" class="flex items-center gap-3 px-2 py-2 text-sm font-semibold text-red-600 hover:text-red-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                            <path d="M15 17L20 12L15 7M20 12H9M11 21H5C4.448 21 4 20.552 4 20V4C4 3.448 4.448 3 5 3H11"
                                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Keluar
                    </button>
                </div>
            </div>
        </aside>

        <!-- Main -->
        <main class="pt-[62px] pl-[280px]">
            <div class="max-w-[1080px] px-8 py-8">

                <section class="mb-7 flex items-start justify-between gap-5">
                    <div>
                        <h2 class="text-[40px] font-extrabold leading-tight tracking-tight text-[#00288E]">
                            Dashboard Juri
                        </h2>
                        <p id="welcomeText">
                            Selamat datang kembali. Mari mulai proses penilaian.
                        </p>
                    </div>

                    <a href="/jury/candidates" class="mt-3 inline-flex items-center gap-3 rounded-lg bg-[#00288E] px-10 py-4 text-sm font-bold text-white shadow-sm transition hover:bg-[#001F73]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M8 5V19L19 12L8 5Z"/>
                        </svg>
                        Mulai Penilaian Sekarang
                    </a>
                </section>

                <!-- Stats -->
                <section class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div class="rounded-xl border border-slate-300 bg-white p-7 shadow-sm">
                        <div class="mb-7 flex items-center gap-5">
                            <div class="flex h-12 w-12 items-center justify-center rounded-md bg-indigo-100 text-[#00288E]">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none">
                                    <path d="M7 3V6M17 3V6M4 9H20M5 5H19V21H5V5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <p class="text-sm font-extrabold uppercase tracking-[0.15em] text-slate-600">
                                Jadwal Hari Ini
                            </p>
                        </div>

                        <div class="flex items-end gap-2">
                            <h3 id="todayScheduleCount" class="text-[40px] font-extrabold leading-none text-[#00288E]"></h3>
                            <p class="mb-1 text-base font-medium text-slate-500">Sesi</p>
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-300 bg-white p-7 shadow-sm">
                        <div class="mb-7 flex items-center gap-5">
                            <div class="flex h-12 w-12 items-center justify-center rounded-md bg-yellow-100 text-yellow-700">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none">
                                    <path d="M16 11C17.657 11 19 9.657 19 8M8 11C9.657 11 11 9.657 11 8C11 6.343 9.657 5 8 5C6.343 5 5 6.343 5 8C5 9.657 6.343 11 8 11ZM8 13C5.239 13 3 15.239 3 18V19H13V18C13 15.239 10.761 13 8 13Z"
                                          stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <p class="text-sm font-extrabold uppercase tracking-[0.15em] text-slate-600">
                                Calon Dinilai
                            </p>
                        </div>

                        <div class="flex items-end gap-2">
                            <h3 id="candidateScoredCount" class="text-[40px] font-extrabold leading-none text-yellow-800"></h3>
                            <p class="mb-1 text-base font-medium text-slate-500">Mahasiswa</p>
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-300 bg-white p-7 shadow-sm">
                        <div class="mb-7 flex items-center gap-5">
                            <div class="flex h-12 w-12 items-center justify-center rounded-md bg-orange-100 text-orange-700">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none">
                                    <path d="M7 4H17V20H7V4Z" stroke="currentColor" stroke-width="2"/>
                                    <path d="M10 9H14M10 13H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M17 15L20 18M20 15L17 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <p class="text-sm font-extrabold uppercase tracking-[0.15em] text-slate-600">
                                Belum Dinilai
                            </p>
                        </div>

                        <div class="flex items-end gap-2">
                            <h3 id="notScoredCount" class="text-[40px] font-extrabold leading-none text-orange-900"></h3>
                            <p class="mb-1 text-base font-medium text-slate-500">Mahasiswa</p>
                        </div>
                    </div>
                </section>

                <!-- Schedule Table -->
                <section class="mt-7 overflow-hidden rounded-xl border border-slate-300 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-300 px-7 py-5">
                        <h3 class="text-[26px] font-extrabold text-slate-800">
                            Jadwal Wawancara Terdekat
                        </h3>

                        <a href="#" class="text-sm font-extrabold text-[#00288E] hover:underline">
                            Lihat Semua Jadwal
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left">
                            <thead class="bg-slate-50">
                                <tr class="text-sm uppercase tracking-[0.12em] text-slate-600">
                                    <th class="px-7 py-4 font-extrabold">Nama Calon</th>
                                    <th class="px-7 py-4 font-extrabold">NIM</th>
                                    <th class="px-7 py-4 font-extrabold">Jam</th>
                                    <th class="px-7 py-4 font-extrabold">Status</th>
                                    <th class="px-7 py-4 font-extrabold">Aksi</th>
                                </tr>
                            </thead>

                            <tbody id="scheduleTableBody" class="divide-y divide-slate-200 text-[15px]">
                                <tr>
                                    <td class="px-7 py-5 font-medium text-slate-800">Aditya Saputra</td>
                                    <td class="px-7 py-5 text-slate-600">2103421045</td>
                                    <td class="px-7 py-5 font-medium text-slate-800">08:00 - 08:15</td>
                                    <td class="px-7 py-5">
                                        <span class="rounded-full bg-yellow-100 px-4 py-1 text-xs font-extrabold text-yellow-800">
                                            Menunggu
                                        </span>
                                    </td>
                                    <td class="px-7 py-5">
                                        <button class="inline-flex items-center gap-2 rounded-md bg-[#00288E] px-5 py-2 text-xs font-extrabold text-white hover:bg-[#001F73]">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                                                <path d="M4 5H20V17H8L4 21V5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                            </svg>
                                            Nilai
                                        </button>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-7 py-5 font-medium text-slate-800">Rina Mutia</td>
                                    <td class="px-7 py-5 text-slate-600">2107411012</td>
                                    <td class="px-7 py-5 font-medium text-slate-800">08:15 - 08:30</td>
                                    <td class="px-7 py-5">
                                        <span class="rounded-full bg-indigo-100 px-4 py-1 text-xs font-extrabold text-indigo-800">
                                            Selesai
                                        </span>
                                    </td>
                                    <td class="px-7 py-5">
                                        <button class="inline-flex items-center gap-2 rounded-md border border-slate-400 bg-white px-5 py-2 text-xs font-extrabold text-[#00288E] hover:bg-slate-50">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                                                <path d="M2 12C4.5 7.5 8 5.5 12 5.5C16 5.5 19.5 7.5 22 12C19.5 16.5 16 18.5 12 18.5C8 18.5 4.5 16.5 2 12Z" stroke="currentColor" stroke-width="2"/>
                                                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
                                            </svg>
                                            Lihat
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Info Cards -->
                <section class="mt-7 grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="rounded-xl bg-[#213EB8] p-7 text-white shadow-sm">
                        <div class="flex gap-5">
                            <svg class="mt-1 h-7 w-7 shrink-0" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                                <path d="M12 8H12.01M11 12H12V17H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>

                            <div>
                                <h4 class="text-base font-extrabold">Panduan Penilaian</h4>
                                <p class="mt-2 max-w-md text-sm leading-relaxed text-blue-100">
                                    Pastikan Anda telah mengunduh kriteria penilaian terbaru untuk kategori Wawancara Kepribadian di modul Kriteria.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-300 bg-slate-100 p-7 shadow-sm">
                        <div class="flex gap-5">
                            <svg class="mt-1 h-7 w-7 shrink-0 text-[#00288E]" viewBox="0 0 24 24" fill="none">
                                <path d="M3 12A9 9 0 1 0 6 5.3M3 5V12H10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            <div>
                                <h4 class="text-base font-extrabold text-slate-800">Aktivitas Terakhir</h4>
                                <p id="lastActivityText">
                                    Memuat aktivitas terakhir...
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </main>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', async function () {
        const API_BASE_URL = '/api';

        const token =
            localStorage.getItem('duta_kampus_token') ||
            localStorage.getItem('auth_token') ||
            localStorage.getItem('token') ||
            localStorage.getItem('access_token') ||
            sessionStorage.getItem('duta_kampus_token') ||
            sessionStorage.getItem('auth_token') ||
            sessionStorage.getItem('token') ||
            sessionStorage.getItem('access_token');

        const user = JSON.parse(
            localStorage.getItem('duta_kampus_user') ||
            sessionStorage.getItem('duta_kampus_user') ||
            'null'
        );

        if (!token) {
            alert('Token tidak ditemukan. Silakan login ulang.');
            window.location.href = '/login';
        }

        if (user && user.role !== 'juri') {
            alert('Akses ditolak. Halaman ini hanya untuk juri.');
            window.location.href = '/login';
        }

        async function apiGet(endpoint) {
            const response = await fetch(`${API_BASE_URL}${endpoint}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`,
                },
            });

            if (response.status === 401) {
                localStorage.removeItem('auth_token');
                localStorage.removeItem('token');
                sessionStorage.removeItem('auth_token');
                sessionStorage.removeItem('token');

                alert('Sesi login berakhir. Silakan login ulang.');
                window.location.href = '/login';
                return;
            }

            const result = await response.json();

            if (!response.ok) {
                throw {
                    status: response.status,
                    message: result.message || 'Gagal mengambil data.',
                    result,
                };
            }

            return result;
        }

        function safeSetText(id, value) {
            const element = document.getElementById(id);
            if (element) {
                element.textContent = value;
            }
        }

        function getInitial(name) {
            if (!name) return 'JU';

            const words = name.trim().split(' ');
            if (words.length === 1) {
                return words[0].substring(0, 2).toUpperCase();
            }

            return `${words[0][0]}${words[1][0]}`.toUpperCase();
        }

        function formatDateTime(dateTime) {
            if (!dateTime) return '-';

            const date = new Date(dateTime);

            return date.toLocaleString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
            });
        }

        function formatTime(dateTime) {
            if (!dateTime) return '-';

            const date = new Date(dateTime);

            return date.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
            });
        }

        function statusBadge(status) {
            const labels = {
                scheduled: 'Menunggu',
                completed: 'Selesai',
                absent: 'Tidak Hadir',
                cancelled: 'Dibatalkan',
            };

            return labels[status] || status || '-';
        }

        async function loadLoggedInJury() {
            try {
                const response = await apiGet('/me');
                const user = response.data?.user;

                if (!user) return;

                safeSetText('juryInitial', getInitial(user.name));
                safeSetText('juryName', user.name || '-');
                safeSetText('juryEmail', user.email || '-');
                safeSetText('welcomeText', `Selamat datang kembali, ${user.name}. Mari mulai proses penilaian.`);
            } catch (error) {
                console.error(error);
                safeSetText('welcomeText', 'Gagal memuat data juri. Silakan muat ulang halaman.');
            }
        }

        async function loadMyScores() {
            try {
                const response = await apiGet('/my-scores');
                const scores = Array.isArray(response.data) ? response.data : [];

                scores.forEach(score => {
                    const candidateId = score.candidate_id || score.candidate?.id;

                    if (candidateId) {
                        scoredCandidateIds.add(Number(candidateId));
                    }
                });

                safeSetText('candidateScoredCount', scoredCandidateIds.size);

                if (scores.length === 0) {
                    safeSetText('lastActivityText', 'Belum ada aktivitas penilaian.');
                    return;
                }

                const sortedScores = scores.sort((a, b) => {
                    const dateA = new Date(a.updated_at || a.created_at || 0);
                    const dateB = new Date(b.updated_at || b.created_at || 0);

                    return dateB - dateA;
                });

                const latest = sortedScores[0];
                const candidateName = latest.candidate?.full_name || 'calon';
                const criterionName = latest.criterion?.name || 'kriteria';
                const scoreValue = latest.score ?? '-';
                const updatedAt = formatDateTime(latest.updated_at || latest.created_at);

                safeSetText(
                    'lastActivityText',
                    `Terakhir memberi nilai ${scoreValue} untuk ${candidateName} pada kriteria ${criterionName}. ${updatedAt}`
                );
            } catch (error) {
                console.error(error);
                safeSetText('candidateScoredCount', '0');
                safeSetText('lastActivityText', 'Gagal memuat riwayat penilaian.');
            }
        }

        async function loadTodayInterviews() {
            const today = new Date().toISOString().slice(0, 10);
            const tableBody = document.getElementById('nearestInterviewRows');

            if (!tableBody) return;

            tableBody.innerHTML = `
            <tbody id="nearestInterviewRows">
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-slate-500">
                        Memuat jadwal wawancara...
                    </td>
                </tr>
            </tbody>
            `;

            try {
                const response = await apiGet(`/interviews?date=${today}&per_page=5`);

                const interviews =
                    response.data?.data ||
                    response.data ||
                    [];

                safeSetText('todayScheduleCount', interviews.length);

                const notScoredCount = interviews.filter(item => {
                    return !scoredCandidateIds.has(Number(item.candidate_id));
                }).length;

                safeSetText('notScoredCount', notScoredCount);

                if (interviews.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-500">
                                Tidak ada jadwal wawancara hari ini.
                            </td>
                        </tr>
                    `;
                    return;
                }

                tableBody.innerHTML = interviews.map(item => {
                    const candidateId = item.candidate_id;
                    const alreadyScored = scoredCandidateIds.has(Number(candidateId));
                    const actionText = alreadyScored ? 'Lihat / Ubah' : 'Nilai';
                    const actionClass = alreadyScored
                        ? 'bg-slate-100 text-slate-700 hover:bg-slate-200'
                        : 'bg-blue-600 text-white hover:bg-blue-700';

                    return `
                        <tr class="border-b border-slate-100">
                            <td class="px-4 py-3 font-medium text-slate-800">
                                ${item.full_name || '-'}
                            </td>
                            <td class="px-4 py-3 text-slate-600">
                                ${item.student_number || '-'}
                            </td>
                            <td class="px-4 py-3 text-slate-600">
                                ${formatTime(item.scheduled_at)}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold">
                                    ${statusBadge(item.status)}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="/jury/scoring/${candidateId}"
                                class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold ${actionClass}">
                                    ${actionText}
                                </a>
                            </td>
                        </tr>
                    `;
                }).join('');
            } catch (error) {
                console.error(error);

                safeSetText('todayScheduleCount', '-');
                safeSetText('notScoredCount', '-');

                tableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-slate-500">
                            Jadwal wawancara belum bisa dimuat untuk akun juri karena endpoint <strong>/api/interviews</strong>
                            saat ini masih dibatasi untuk admin.
                        </td>
                    </tr>
                `;
            }
        }

        await loadLoggedInJury();
        await loadMyScores();
        await loadTodayInterviews();
    });
    </script>
</body>
</html>
