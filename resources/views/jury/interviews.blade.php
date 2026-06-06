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
        <main class="min-h-screen p-4 md:p-6 lg:p-8" style="margin-left: 300px; width: calc(100% - 300px); padding-top: 86px;">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-blue-800">Peserta Wawancara</h1>
                <p class="text-sm text-slate-600 mt-1">
                    Lihat daftar calon peserta yang dijadwalkan dan mulai proses penilaian.
                </p>
            </div>

            <button id="exportButton"
                    type="button"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl border border-blue-700 text-blue-700 bg-white hover:bg-blue-50 text-sm font-semibold">
                <span>⇩</span>
                Export Jadwal
            </button>
        </div>

        {{-- Statistic Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
            <div class="bg-white rounded-xl shadow p-5 flex items-center gap-5">
                <div class="w-14 h-14 rounded-full bg-blue-100 text-blue-800 flex items-center justify-center text-xl">
                    👥
                </div>
                <div>
                    <p class="text-xs text-slate-500">Total Peserta</p>
                    <h2 id="totalParticipants" class="text-3xl font-bold">0</h2>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-5 flex items-center gap-5">
                <div class="w-14 h-14 rounded-full bg-yellow-100 text-yellow-700 flex items-center justify-center text-xl">
                    📋
                </div>
                <div>
                    <p class="text-xs text-slate-500">Belum Dinilai</p>
                    <h2 id="notScoredParticipants" class="text-3xl font-bold">0</h2>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-5 flex items-center gap-5">
                <div class="w-14 h-14 rounded-full bg-green-100 text-green-700 flex items-center justify-center text-xl">
                    ✓
                </div>
                <div>
                    <p class="text-xs text-slate-500">Sudah Dinilai</p>
                    <h2 id="scoredParticipants" class="text-3xl font-bold">0</h2>
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-5 py-5 border-b border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold">Daftar Jadwal Wawancara</h2>
                    <p class="text-sm text-slate-500">Peserta yang ditugaskan kepada juri untuk dinilai.</p>
                </div>

                <div class="inline-flex bg-slate-100 rounded-xl p-1 text-sm font-semibold">
                    <button type="button"
                            data-filter="all"
                            class="filter-button px-4 py-2 rounded-lg bg-white text-blue-800 shadow">
                        Semua
                    </button>

                    <button type="button"
                            data-filter="not_scored"
                            class="filter-button px-4 py-2 rounded-lg text-slate-600">
                        Belum Dinilai
                    </button>

                    <button type="button"
                            data-filter="scored"
                            class="filter-button px-4 py-2 rounded-lg text-slate-600">
                        Sudah Dinilai
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-5 py-4 text-left font-semibold">No</th>
                        <th class="px-5 py-4 text-left font-semibold">Nama Calon</th>
                        <th class="px-5 py-4 text-left font-semibold">NIM</th>
                        <th class="px-5 py-4 text-left font-semibold">No. Pendaftaran</th>
                        <th class="px-5 py-4 text-left font-semibold">Program Studi</th>
                        <th class="px-5 py-4 text-left font-semibold">Jadwal Wawancara</th>
                        <th class="px-5 py-4 text-left font-semibold">Status</th>
                        <th class="px-5 py-4 text-left font-semibold">Aksi</th>
                    </tr>
                    </thead>

                    <tbody id="interviewRows" class="divide-y divide-slate-100">
                    <tr>
                        <td colspan="8" class="px-5 py-10 text-center text-slate-500">
                            Memuat data jadwal wawancara...
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-4 border-t border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <p id="tableInfo" class="text-sm text-slate-600">
                    Menampilkan 0 dari 0 peserta
                </p>

                <div class="flex items-center gap-2">
                    <button id="prevPage"
                            type="button"
                            class="w-9 h-9 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50">
                        ‹
                    </button>

                    <div id="paginationNumbers" class="flex items-center gap-2"></div>

                    <button id="nextPage"
                            type="button"
                            class="w-9 h-9 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50">
                        ›
                    </button>
                </div>
            </div>
        </div>
    </main>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', async function () {
    const API_BASE_URL = '/api';
    const perPage = 4;

    let currentPage = 1;
    let activeFilter = 'all';
    let interviews = [];
    let scoredCandidateIds = new Set();

    const token =
        localStorage.getItem('duta_kampus_token') ||
        localStorage.getItem('auth_token') ||
        localStorage.getItem('token') ||
        localStorage.getItem('access_token') ||
        sessionStorage.getItem('duta_kampus_token') ||
        sessionStorage.getItem('auth_token') ||
        sessionStorage.getItem('token') ||
        sessionStorage.getItem('access_token');

    const elements = {
        juryInitial: document.getElementById('juryInitial'),
        juryName: document.getElementById('juryName'),
        juryEmail: document.getElementById('juryEmail'),
        totalParticipants: document.getElementById('totalParticipants'),
        notScoredParticipants: document.getElementById('notScoredParticipants'),
        scoredParticipants: document.getElementById('scoredParticipants'),
        interviewRows: document.getElementById('interviewRows'),
        tableInfo: document.getElementById('tableInfo'),
        paginationNumbers: document.getElementById('paginationNumbers'),
        prevPage: document.getElementById('prevPage'),
        nextPage: document.getElementById('nextPage'),
        exportButton: document.getElementById('exportButton'),
        filterButtons: document.querySelectorAll('.filter-button'),
    };

    if (!token) {
        alert('Token tidak ditemukan. Silakan login ulang.');
        window.location.href = '/login';
        return;
    }

    function escapeHtml(value) {
        if (value === null || value === undefined || value === '') return '-';

        return String(value)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function setText(element, value) {
        if (element) {
            element.textContent = value;
        }
    }

    function getInitial(name) {
        if (!name) return 'JU';

        const words = name.trim().split(/\s+/);

        if (words.length === 1) {
            return words[0].substring(0, 2).toUpperCase();
        }

        return `${words[0][0]}${words[1][0]}`.toUpperCase();
    }

    function getCandidate(interview) {
        return interview.candidate || interview.candidate_data || interview;
    }

    function getCandidateId(interview) {
        const candidate = getCandidate(interview);

        return Number(
            interview.candidate_id ||
            candidate.id ||
            0
        );
    }

    function getCandidateName(interview) {
        const candidate = getCandidate(interview);

        return candidate.full_name ||
            candidate.name ||
            interview.full_name ||
            interview.name ||
            '-';
    }

    function getStudentNumber(interview) {
        const candidate = getCandidate(interview);

        return candidate.nim ||
            candidate.student_number ||
            candidate.student_id_number ||
            interview.nim ||
            interview.student_number ||
            '-';
    }

    function getRegistrationNumber(interview) {
        const candidate = getCandidate(interview);

        return candidate.registration_number ||
            interview.registration_number ||
            '-';
    }

    function getStudyProgram(interview) {
        const candidate = getCandidate(interview);

        return candidate.study_program ||
            candidate.major ||
            interview.study_program ||
            '-';
    }

    function getScheduleDate(interview) {
        return interview.scheduled_at ||
            interview.interview_date ||
            interview.schedule_at ||
            interview.datetime ||
            null;
    }

    function formatSchedule(value) {
        if (!value) return '-';

        const date = new Date(value);

        if (Number.isNaN(date.getTime())) {
            return value;
        }

        const dateText = date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
        });

        const timeText = date.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
        });

        return `${dateText}<br><span class="font-semibold">${timeText}</span>`;
    }

    function isScored(interview) {
        const candidateId = getCandidateId(interview);

        return scoredCandidateIds.has(candidateId);
    }

    async function apiGet(endpoint) {
        const response = await fetch(`${API_BASE_URL}${endpoint}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`,
            },
        });

        let result = null;

        try {
            result = await response.json();
        } catch (error) {
            result = null;
        }

        if (response.status === 401) {
            localStorage.removeItem('duta_kampus_token');
            localStorage.removeItem('duta_kampus_user');

            alert('Sesi login berakhir. Silakan login ulang.');
            window.location.href = '/login';
            return;
        }

        if (!response.ok) {
            throw {
                status: response.status,
                message: result?.message || 'Gagal mengambil data.',
                result,
            };
        }

        return result;
    }

    async function loadProfile() {
        try {
            const response = await apiGet('/me');
            const user = response?.data?.user || response?.data || null;

            if (!user) return;

            setText(elements.juryInitial, getInitial(user.name));
            setText(elements.juryName, user.name || '-');
            setText(elements.juryEmail, user.email || '-');
        } catch (error) {
            console.error(error);
        }
    }

    async function loadScores() {
        try {
            const response = await apiGet('/my-scores');
            const scores = Array.isArray(response?.data?.data)
                ? response.data.data
                : Array.isArray(response?.data)
                    ? response.data
                    : [];

            scores.forEach(score => {
                const candidateId = Number(score.candidate_id || score.candidate?.id || 0);

                if (candidateId) {
                    scoredCandidateIds.add(candidateId);
                }
            });
        } catch (error) {
            console.error(error);
        }
    }

    async function loadInterviews() {
        elements.interviewRows.innerHTML = `
            <tr>
                <td colspan="8" class="px-5 py-10 text-center text-slate-500">
                    Memuat data jadwal wawancara...
                </td>
            </tr>
        `;

        try {
            const response = await apiGet('/interviews');

            interviews = Array.isArray(response?.data?.data)
                ? response.data.data
                : Array.isArray(response?.data)
                    ? response.data
                    : [];

            currentPage = 1;

            renderStatistics();
            renderTable();
        } catch (error) {
            console.error(error);

            let message = 'Gagal memuat jadwal wawancara.';

            if (error.status === 403) {
                message = 'API jadwal wawancara masih dibatasi untuk admin. Nanti perlu dibuat endpoint khusus juri.';
            }

            elements.interviewRows.innerHTML = `
                <tr>
                    <td colspan="8" class="px-5 py-10 text-center text-slate-500">
                        ${escapeHtml(message)}
                    </td>
                </tr>
            `;
        }
    }

    function getFilteredInterviews() {
        if (activeFilter === 'scored') {
            return interviews.filter(item => isScored(item));
        }

        if (activeFilter === 'not_scored') {
            return interviews.filter(item => !isScored(item));
        }

        return interviews;
    }

    function renderStatistics() {
        const scored = interviews.filter(item => isScored(item)).length;
        const notScored = interviews.length - scored;

        setText(elements.totalParticipants, interviews.length);
        setText(elements.scoredParticipants, scored);
        setText(elements.notScoredParticipants, notScored);
    }

    function renderTable() {
        const filtered = getFilteredInterviews();
        const totalPages = Math.max(Math.ceil(filtered.length / perPage), 1);

        if (currentPage > totalPages) {
            currentPage = totalPages;
        }

        const startIndex = (currentPage - 1) * perPage;
        const pageRows = filtered.slice(startIndex, startIndex + perPage);

        if (pageRows.length === 0) {
            elements.interviewRows.innerHTML = `
                <tr>
                    <td colspan="8" class="px-5 py-10 text-center text-slate-500">
                        Tidak ada data peserta wawancara.
                    </td>
                </tr>
            `;

            setText(elements.tableInfo, `Menampilkan 0 dari ${filtered.length} peserta`);
            renderPagination(totalPages);
            return;
        }

        elements.interviewRows.innerHTML = pageRows.map((item, index) => {
            const candidateId = getCandidateId(item);
            const scored = isScored(item);
            const name = getCandidateName(item);
            const initial = getInitial(name);

            const badgeClass = scored
                ? 'bg-green-100 text-green-700'
                : 'bg-yellow-100 text-yellow-700';

            const statusText = scored ? 'Sudah Dinilai' : 'Belum Dinilai';

            const actionText = scored ? 'Lihat Penilaian' : 'Mulai Penilaian';

            const actionClass = scored
                ? 'border border-blue-700 text-blue-700 bg-white hover:bg-blue-50'
                : 'bg-blue-800 text-white hover:bg-blue-900';

            return `
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-4 text-slate-700">
                        ${startIndex + index + 1}
                    </td>

                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-800 flex items-center justify-center text-xs font-bold">
                                ${escapeHtml(initial)}
                            </div>
                            <div class="font-bold text-slate-900 leading-tight">
                                ${escapeHtml(name)}
                            </div>
                        </div>
                    </td>

                    <td class="px-5 py-4 text-slate-700">
                        ${escapeHtml(getStudentNumber(item))}
                    </td>

                    <td class="px-5 py-4 text-slate-700">
                        ${escapeHtml(getRegistrationNumber(item))}
                    </td>

                    <td class="px-5 py-4 text-slate-700">
                        ${escapeHtml(getStudyProgram(item))}
                    </td>

                    <td class="px-5 py-4 text-blue-800 font-medium">
                        ${formatSchedule(getScheduleDate(item))}
                    </td>

                    <td class="px-5 py-4">
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold ${badgeClass}">
                            <span>•</span>
                            ${statusText}
                        </span>
                    </td>

                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <a href="/jury/candidates/${candidateId}"
                               class="inline-flex items-center justify-center px-3 py-2 rounded-md border border-slate-400 text-slate-700 text-xs font-semibold hover:bg-slate-50">
                                Detail Calon
                            </a>

                            <a href="/jury/scoring/${candidateId}"
                               class="inline-flex items-center justify-center px-3 py-2 rounded-md text-xs font-semibold ${actionClass}">
                                ${actionText}
                            </a>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');

        const showingStart = filtered.length === 0 ? 0 : startIndex + 1;
        const showingEnd = Math.min(startIndex + perPage, filtered.length);

        setText(
            elements.tableInfo,
            `Menampilkan ${showingStart}-${showingEnd} dari ${filtered.length} peserta`
        );

        renderPagination(totalPages);
    }

    function renderPagination(totalPages) {
        elements.paginationNumbers.innerHTML = '';

        for (let page = 1; page <= totalPages; page++) {
            const button = document.createElement('button');
            button.type = 'button';
            button.textContent = page;
            button.className = page === currentPage
                ? 'w-9 h-9 rounded-lg bg-blue-800 text-white text-sm font-semibold'
                : 'w-9 h-9 rounded-lg border border-slate-200 text-slate-700 text-sm font-semibold hover:bg-slate-50';

            button.addEventListener('click', function () {
                currentPage = page;
                renderTable();
            });

            elements.paginationNumbers.appendChild(button);
        }

        elements.prevPage.disabled = currentPage === 1;
        elements.nextPage.disabled = currentPage === totalPages;

        elements.prevPage.classList.toggle('opacity-50', currentPage === 1);
        elements.nextPage.classList.toggle('opacity-50', currentPage === totalPages);
    }

    function setActiveFilter(filter) {
        activeFilter = filter;
        currentPage = 1;

        elements.filterButtons.forEach(button => {
            const isActive = button.dataset.filter === filter;

            button.className = isActive
                ? 'filter-button px-4 py-2 rounded-lg bg-white text-blue-800 shadow'
                : 'filter-button px-4 py-2 rounded-lg text-slate-600';
        });

        renderTable();
    }

    function exportCsv() {
        const filtered = getFilteredInterviews();

        const rows = [
            ['No', 'Nama Calon', 'NIM', 'No Pendaftaran', 'Program Studi', 'Jadwal Wawancara', 'Status'],
            ...filtered.map((item, index) => [
                index + 1,
                getCandidateName(item),
                getStudentNumber(item),
                getRegistrationNumber(item),
                getStudyProgram(item),
                getScheduleDate(item) || '-',
                isScored(item) ? 'Sudah Dinilai' : 'Belum Dinilai',
            ]),
        ];

        const csvContent = rows
            .map(row => row.map(value => `"${String(value).replaceAll('"', '""')}"`).join(','))
            .join('\n');

        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);

        const link = document.createElement('a');
        link.href = url;
        link.download = 'jadwal-wawancara-juri.csv';
        link.click();

        URL.revokeObjectURL(url);
    }

    elements.filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            setActiveFilter(this.dataset.filter);
        });
    });

    elements.prevPage.addEventListener('click', function () {
        if (currentPage > 1) {
            currentPage--;
            renderTable();
        }
    });

    elements.nextPage.addEventListener('click', function () {
        const totalPages = Math.max(Math.ceil(getFilteredInterviews().length / perPage), 1);

        if (currentPage < totalPages) {
            currentPage++;
            renderTable();
        }
    });

    elements.exportButton.addEventListener('click', exportCsv);

    await loadProfile();
    await loadScores();
    await loadInterviews();
});
</script>
</body>
</html>
