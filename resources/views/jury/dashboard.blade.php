@extends('layouts.jury')

@section('content')
    <section class="mb-7">
        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
            <div>
                <h1 class="text-[34px] font-extrabold leading-tight tracking-tight text-[#00288E]">
                    Dashboard Juri
                </h1>

                <p id="welcomeText" class="mt-2 text-sm font-medium text-slate-500">
                    Selamat datang. Pantau ringkasan penilaian dan lanjutkan penilaian peserta.
                </p>
            </div>

            <div class="flex items-end gap-2">
                <x-period-select width="w-40" />

                <a
                    href="{{ route('jury.scoring.index') }}"
                    class="flex h-10 items-center rounded-md bg-[#00288E] px-5 text-sm font-bold text-white hover:bg-[#001F73]"
                >
                    Mulai Penilaian
                </a>
            </div>
        </div>
    </section>

    <div id="pageAlert" class="mb-5 hidden rounded-md border px-4 py-3 text-sm"></div>

    <section class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <x-card>
            <p class="text-sm font-semibold text-slate-700">Kriteria Ditugaskan</p>
            <h2 id="assignedCriteriaCount" class="mt-2 text-3xl font-extrabold text-slate-900">0</h2>
        </x-card>

        <x-card>
            <p class="text-sm font-semibold text-slate-700">Peserta Dinilai</p>
            <h2 id="completedCandidateCount" class="mt-2 text-3xl font-extrabold text-green-600">0</h2>
        </x-card>

        <x-card>
            <p class="text-sm font-semibold text-slate-700">Belum Lengkap</p>
            <h2 id="incompleteCandidateCount" class="mt-2 text-3xl font-extrabold text-red-600">0</h2>
        </x-card>

        <x-card>
            <p class="text-sm font-semibold text-slate-700">Progress Saya</p>
            <h2 id="completionPercentage" class="mt-2 text-3xl font-extrabold text-[#00288E]">0%</h2>
        </x-card>
    </section>

    <section class="mt-7 grid grid-cols-1 gap-5 xl:grid-cols-3">
        <div class="space-y-5 xl:col-span-2">
            <x-card padding="p-0">
                <div class="flex items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">
                            Progress Penilaian Peserta
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Peserta dianggap selesai jika semua kriteria yang ditugaskan kepadamu sudah dinilai.
                        </p>
                    </div>

                    <a
                        href="{{ route('jury.scoring.index') }}"
                        class="text-sm font-bold text-[#00288E] hover:underline"
                    >
                        Lihat Semua
                    </a>
                </div>

                <x-table
                    :headers="['Peserta', 'NIM', 'Progress', 'Rata-rata', 'Status']"
                    tbody-id="candidateProgressBody"
                    class="rounded-none border-0 shadow-none"
                >
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                            Memuat progress peserta...
                        </td>
                    </tr>
                </x-table>
            </x-card>

            <x-card>
                <h2 class="text-lg font-extrabold text-slate-900">
                    Panduan Penilaian
                </h2>

                <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="rounded-lg border border-blue-100 bg-blue-50 px-4 py-3">
                        <p class="text-sm font-extrabold text-[#00288E]">
                            Nilai berdasarkan kriteria
                        </p>
                        <p class="mt-1 text-sm leading-relaxed text-slate-600">
                            Berikan nilai sesuai kriteria yang muncul di form. Setiap kriteria punya rentang nilai sendiri, misalnya 0 sampai 100.
                        </p>
                    </div>

                    <div class="rounded-lg border border-blue-100 bg-blue-50 px-4 py-3">
                        <p class="text-sm font-extrabold text-[#00288E]">
                            Gunakan observasi wawancara
                        </p>
                        <p class="mt-1 text-sm leading-relaxed text-slate-600">
                            Nilai harus mencerminkan jawaban, sikap, kemampuan komunikasi, dan performa peserta saat wawancara.
                        </p>
                    </div>

                    <div class="rounded-lg border border-blue-100 bg-blue-50 px-4 py-3">
                        <p class="text-sm font-extrabold text-[#00288E]">
                            Hindari penilaian personal
                        </p>
                        <p class="mt-1 text-sm leading-relaxed text-slate-600">
                            Jangan menilai berdasarkan kedekatan, asal program studi, penampilan di luar kriteria, atau preferensi pribadi.
                        </p>
                    </div>

                    <div class="rounded-lg border border-blue-100 bg-blue-50 px-4 py-3">
                        <p class="text-sm font-extrabold text-[#00288E]">
                            Cek sebelum simpan
                        </p>
                        <p class="mt-1 text-sm leading-relaxed text-slate-600">
                            Pastikan nilai sudah sesuai sebelum disimpan. Nilai yang kamu isi akan digunakan dalam perhitungan hasil akhir.
                        </p>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="space-y-5">
            <x-card padding="p-0">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-lg font-extrabold text-slate-900">
                        Kriteria Saya
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Kriteria yang menjadi tanggung jawab penilaianmu.
                    </p>
                </div>

                <div id="assignedCriteriaList" class="p-5">
                    <p class="text-sm text-slate-500">
                        Memuat kriteria...
                    </p>
                </div>
            </x-card>

            <x-card padding="p-0">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-lg font-extrabold text-slate-900">
                        Aktivitas Terakhir
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Nilai terakhir yang kamu input.
                    </p>
                </div>

                <div id="recentScoresList" class="p-5">
                    <p class="text-sm text-slate-500">
                        Memuat aktivitas...
                    </p>
                </div>
            </x-card>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        let dashboardData = null;

        document.addEventListener('DOMContentLoaded', async function () {
            const user = DutaJury.user();

            if (user?.name) {
                document.getElementById('welcomeText').textContent =
                    `Selamat datang, ${user.name}. Pantau ringkasan penilaian dan lanjutkan penilaian peserta.`;
            }

            const params = new URLSearchParams(window.location.search);
            const periodId = params.get('period_id') || '1';

            await loadPeriodOptions('periodIdInput', periodId);

            loadDashboard();

            document.getElementById('periodIdInput')?.addEventListener('change', function () {
                loadDashboard();
            });
        });

        async function loadDashboard() {
            const periodId = getPeriodId();

            setLoadingState();

            try {
                const params = new URLSearchParams({
                    period_id: periodId,
                });

                const result = await DutaJury.request(`/jury/dashboard-summary?${params.toString()}`);

                dashboardData = result?.data;

                renderSummary();
                renderCandidateProgress();
                renderAssignedCriteria();
                renderRecentScores();
            } catch (error) {
                showAlert('danger', getErrorMessage(error));
                renderErrorState(getErrorMessage(error));
            }
        }

        function renderSummary() {
            const summary = dashboardData?.summary || {};

            setText('assignedCriteriaCount', summary.assigned_criteria_count || 0);
            setText('completedCandidateCount', summary.completed_candidate_count || 0);
            setText('incompleteCandidateCount', summary.incomplete_candidate_count || 0);
            setText('completionPercentage', `${formatNumber(summary.completion_percentage || 0)}%`);
        }

        function renderCandidateProgress() {
            const tableBody = document.getElementById('candidateProgressBody');
            const candidates = dashboardData?.candidates || [];

            if (!candidates.length) {
                tableBody.innerHTML = emptyRow(5, 'Belum ada peserta yang dapat dinilai.');
                return;
            }

            tableBody.innerHTML = candidates.slice(0, 8).map(item => `
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4">
                        <p class="font-extrabold text-slate-900">${escapeHtml(item.full_name || '-')}</p>
                        <p class="mt-1 text-xs text-slate-500">${escapeHtml(item.registration_number || '-')}</p>
                    </td>

                    <td class="px-6 py-4 text-slate-700">
                        ${escapeHtml(item.student_number || '-')}
                    </td>

                    <td class="px-6 py-4">
                        ${renderProgress(item.completion_percentage)}
                        <p class="mt-1 text-xs text-slate-500">
                            ${item.scored_criteria_count || 0}/${item.assigned_criteria_count || 0} kriteria
                        </p>
                    </td>

                    <td class="px-6 py-4 font-semibold text-slate-700">
                        ${item.average_score === null ? '-' : formatNumber(item.average_score)}
                    </td>

                    <td class="px-6 py-4">
                        ${renderCompleteBadge(item.is_complete)}
                    </td>
                </tr>
            `).join('');
        }

        function renderAssignedCriteria() {
            const target = document.getElementById('assignedCriteriaList');
            const criteria = dashboardData?.assigned_criteria || [];

            if (!criteria.length) {
                target.innerHTML = `
                    <div class="rounded-md border border-yellow-200 bg-yellow-50 px-4 py-3 text-sm font-semibold text-yellow-800">
                        Belum ada kriteria yang ditugaskan kepadamu.
                    </div>
                `;
                return;
            }

            target.innerHTML = `
                <div class="space-y-3">
                    ${criteria.map(item => `
                        <div class="rounded-lg border border-slate-200 px-4 py-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-extrabold text-[#00288E]">
                                        ${escapeHtml(item.code || '-')}
                                    </p>
                                    <p class="mt-1 text-sm font-semibold text-slate-900">
                                        ${escapeHtml(item.name || '-')}
                                    </p>
                                </div>

                                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-blue-700">
                                    ${escapeHtml(item.type || 'benefit')}
                                </span>
                            </div>

                            <p class="mt-2 text-xs text-slate-500">
                                Rentang nilai: ${escapeHtml(item.min_score ?? 0)} - ${escapeHtml(item.max_score ?? 100)}
                            </p>
                        </div>
                    `).join('')}
                </div>
            `;
        }

        function renderRecentScores() {
            const target = document.getElementById('recentScoresList');
            const scores = dashboardData?.recent_scores || [];

            if (!scores.length) {
                target.innerHTML = `
                    <p class="text-sm text-slate-500">
                        Belum ada aktivitas penilaian.
                    </p>
                `;
                return;
            }

            target.innerHTML = `
                <div class="space-y-3">
                    ${scores.map(item => `
                        <div class="rounded-lg border border-slate-200 px-4 py-3">
                            <p class="text-sm font-extrabold text-slate-900">
                                ${escapeHtml(item.candidate_name || '-')}
                            </p>

                            <p class="mt-1 text-xs text-slate-500">
                                ${escapeHtml(item.criterion_code || '-')} - ${escapeHtml(item.criterion_name || '-')}
                            </p>

                            <div class="mt-2 flex items-center justify-between gap-3">
                                <span class="text-sm font-bold text-[#00288E]">
                                    Nilai: ${formatNumber(item.score)}
                                </span>

                                <span class="text-xs text-slate-500">
                                    ${formatDateTime(item.updated_at)}
                                </span>
                            </div>
                        </div>
                    `).join('')}
                </div>
            `;
        }

        function renderProgress(value) {
            const percent = Number(value || 0);

            return `
                <div class="h-2 w-32 overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full rounded-full bg-[#00288E]" style="width: ${Math.min(percent, 100)}%"></div>
                </div>
                <p class="mt-1 text-xs font-bold text-slate-700">${formatNumber(percent)}%</p>
            `;
        }

        function renderCompleteBadge(isComplete) {
            if (isComplete) {
                return `
                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                        Lengkap
                    </span>
                `;
            }

            return `
                <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-bold text-yellow-700">
                    Perlu Dinilai
                </span>
            `;
        }

        function setLoadingState() {
            document.getElementById('candidateProgressBody').innerHTML = emptyRow(5, 'Memuat progress peserta...');
            document.getElementById('assignedCriteriaList').innerHTML = `<p class="text-sm text-slate-500">Memuat kriteria...</p>`;
            document.getElementById('recentScoresList').innerHTML = `<p class="text-sm text-slate-500">Memuat aktivitas...</p>`;
        }

        function renderErrorState(message) {
            document.getElementById('candidateProgressBody').innerHTML = errorRow(5, message);
            document.getElementById('assignedCriteriaList').innerHTML = `
                <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                    ${escapeHtml(message)}
                </div>
            `;
            document.getElementById('recentScoresList').innerHTML = `
                <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                    ${escapeHtml(message)}
                </div>
            `;
        }

        function emptyRow(colspan, message) {
            return `
                <tr>
                    <td colspan="${colspan}" class="px-6 py-8 text-center text-slate-500">
                        ${escapeHtml(message)}
                    </td>
                </tr>
            `;
        }

        function errorRow(colspan, message) {
            return `
                <tr>
                    <td colspan="${colspan}" class="px-6 py-8 text-center text-red-600">
                        ${escapeHtml(message)}
                    </td>
                </tr>
            `;
        }

        function getPeriodId() {
            return document.getElementById('periodIdInput')?.value || 1;
        }

        function formatNumber(value) {
            return new Intl.NumberFormat('id-ID', {
                maximumFractionDigits: 2,
            }).format(Number(value || 0));
        }

        function formatDateTime(value) {
            if (!value) return '-';

            const date = new Date(value);

            if (Number.isNaN(date.getTime())) return value;

            return date.toLocaleString('id-ID', {
                day: '2-digit',
                month: 'short',
                hour: '2-digit',
                minute: '2-digit',
            });
        }

        function showAlert(type, message) {
            const alert = document.getElementById('pageAlert');

            const classes = {
                success: 'border-green-200 bg-green-50 text-green-800',
                danger: 'border-red-200 bg-red-50 text-red-800',
                info: 'border-blue-200 bg-blue-50 text-blue-800',
            };

            alert.className = `mb-5 rounded-md border px-4 py-3 text-sm ${classes[type] || classes.info}`;
            alert.textContent = message;
            alert.classList.remove('hidden');

            setTimeout(() => alert.classList.add('hidden'), 5000);
        }

        function getErrorMessage(error) {
            return error?.message || 'Terjadi kesalahan.';
        }

        function setText(id, value) {
            const element = document.getElementById(id);

            if (element) {
                element.textContent = value;
            }
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
@endpush