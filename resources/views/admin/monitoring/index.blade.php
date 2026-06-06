@extends('layouts.admin')

@section('content')
    <section class="mb-7">
        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
            <div>
                <h1 class="text-[34px] font-extrabold leading-tight tracking-tight text-[#00288E]">
                    Monitoring Penilaian
                </h1>

                <p class="mt-2 text-sm font-medium text-slate-500">
                    Pantau kelengkapan nilai kandidat sebelum perhitungan ARAS dan publikasi pengumuman.
                </p>
            </div>

            <div class="flex items-end gap-2">
                <x-period-select width="w-40" height="h-10" />

                <button
                    type="button"
                    onclick="loadMonitoring()"
                    class="h-10 rounded-md border border-slate-300 bg-white px-4 text-sm font-bold text-slate-700 hover:bg-slate-50"
                >
                    Muat Ulang
                </button>
            </div>
        </div>
    </section>

    <div id="pageAlert" class="mb-5 hidden rounded-md border px-4 py-3 text-sm"></div>

    <section class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <x-card>
            <p class="text-sm font-semibold text-slate-700">Total Kandidat</p>
            <h2 id="totalCandidates" class="mt-2 text-3xl font-extrabold text-slate-900">0</h2>
        </x-card>

        <x-card>
            <p class="text-sm font-semibold text-slate-700">Sudah Lengkap</p>
            <h2 id="completeCandidates" class="mt-2 text-3xl font-extrabold text-green-600">0</h2>
        </x-card>

        <x-card>
            <p class="text-sm font-semibold text-slate-700">Belum Lengkap</p>
            <h2 id="incompleteCandidates" class="mt-2 text-3xl font-extrabold text-red-600">0</h2>
        </x-card>

        <x-card>
            <p class="text-sm font-semibold text-slate-700">Progress Lengkap</p>
            <h2 id="completionPercentage" class="mt-2 text-3xl font-extrabold text-[#00288E]">0%</h2>
        </x-card>
    </section>

    <section class="mt-7">
        <x-card padding="p-0">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-lg font-extrabold text-[#00288E]">
                    Kelengkapan Nilai per Kandidat
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Kandidat dinyatakan lengkap jika sudah memiliki nilai untuk semua kriteria aktif.
                </p>
            </div>

            <x-table
                :headers="['No', 'Kandidat', 'NIM', 'Program Studi', 'Progress', 'Rata-rata', 'Status', 'Kurang']"
                tbody-id="candidateTableBody"
                class="rounded-none border-0 shadow-none"
            >
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-slate-500">
                        Memuat data monitoring...
                    </td>
                </tr>
            </x-table>
        </x-card>
    </section>

    <section class="mt-7 grid grid-cols-1 gap-5 xl:grid-cols-2">
        <x-card padding="p-0">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-lg font-extrabold text-[#00288E]">
                    Progress per Kriteria
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Melihat kriteria mana yang sudah atau belum dinilai untuk seluruh kandidat.
                </p>
            </div>

            <x-table
                :headers="['Kode', 'Nama Kriteria', 'Progress', 'Kurang']"
                tbody-id="criteriaTableBody"
                class="rounded-none border-0 shadow-none"
            >
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                        Memuat data kriteria...
                    </td>
                </tr>
            </x-table>
        </x-card>

        <x-card padding="p-0">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-lg font-extrabold text-[#00288E]">
                    Aktivitas Juri
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Ringkasan tugas kriteria dan jumlah nilai yang sudah diinput juri.
                </p>
            </div>

            <x-table
                :headers="['Juri', 'Kriteria', 'Nilai Masuk', 'Status']"
                tbody-id="juryTableBody"
                class="rounded-none border-0 shadow-none"
            >
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                        Memuat data juri...
                    </td>
                </tr>
            </x-table>
        </x-card>
    </section>
@endsection

@push('scripts')
    <script>
        let monitoringData = null;

        document.addEventListener('DOMContentLoaded', async function () {
            const params = new URLSearchParams(window.location.search);
            const periodId = params.get('period_id') || '1';

            await loadPeriodOptions('periodIdInput', periodId);

            loadMonitoring();

            document.getElementById('periodIdInput')?.addEventListener('change', function () {
                loadMonitoring();
            });
        });

        async function loadMonitoring() {
            const periodId = getPeriodId();

            setLoadingState();

            try {
                const params = new URLSearchParams({
                    period_id: periodId,
                });

                const result = await DutaAdmin.request(`/monitoring/scores?${params.toString()}`);

                monitoringData = result?.data;

                renderSummary();
                renderCandidates();
                renderCriteria();
                renderJuries();
            } catch (error) {
                showAlert('danger', getErrorMessage(error));
                renderErrorState(getErrorMessage(error));
            }
        }

        function renderSummary() {
            const summary = monitoringData?.summary || {};

            setText('totalCandidates', summary.total_candidates || 0);
            setText('completeCandidates', summary.complete_candidates || 0);
            setText('incompleteCandidates', summary.incomplete_candidates || 0);
            setText('completionPercentage', `${formatNumber(summary.completion_percentage || 0)}%`);
        }

        function renderCandidates() {
            const tableBody = document.getElementById('candidateTableBody');
            const candidates = monitoringData?.candidates || [];

            if (!candidates.length) {
                tableBody.innerHTML = emptyRow(8, 'Belum ada kandidat yang dapat dimonitor.');
                return;
            }

            tableBody.innerHTML = candidates.map((item, index) => `
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 text-slate-600">${index + 1}</td>

                    <td class="px-6 py-4">
                        <p class="font-extrabold text-slate-900">${escapeHtml(item.full_name || '-')}</p>
                        <p class="mt-1 text-xs text-slate-500">${escapeHtml(item.registration_number || '-')}</p>
                    </td>

                    <td class="px-6 py-4 text-slate-700">
                        ${escapeHtml(item.student_number || '-')}
                    </td>

                    <td class="px-6 py-4 text-slate-700">
                        ${escapeHtml(item.study_program || '-')}
                    </td>

                    <td class="px-6 py-4">
                        ${renderProgress(item.completion_percentage)}
                        <p class="mt-1 text-xs text-slate-500">
                            ${item.scored_criteria_count || 0}/${item.criteria_count || 0} kriteria
                        </p>
                    </td>

                    <td class="px-6 py-4 font-semibold text-slate-700">
                        ${item.average_score === null ? '-' : formatNumber(item.average_score)}
                    </td>

                    <td class="px-6 py-4">
                        ${renderCompleteBadge(item.is_complete)}
                    </td>

                    <td class="px-6 py-4 text-sm text-slate-600">
                        ${renderMissingCriteria(item.missing_criteria)}
                    </td>
                </tr>
            `).join('');
        }

        function renderCriteria() {
            const tableBody = document.getElementById('criteriaTableBody');
            const criteria = monitoringData?.criteria || [];

            if (!criteria.length) {
                tableBody.innerHTML = emptyRow(4, 'Belum ada kriteria aktif.');
                return;
            }

            tableBody.innerHTML = criteria.map(item => `
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 font-extrabold text-[#00288E]">
                        ${escapeHtml(item.code || '-')}
                    </td>

                    <td class="px-6 py-4">
                        <p class="font-semibold text-slate-900">${escapeHtml(item.name || '-')}</p>
                    </td>

                    <td class="px-6 py-4">
                        ${renderProgress(item.completion_percentage)}
                        <p class="mt-1 text-xs text-slate-500">
                            ${item.scored_candidate_count || 0}/${item.candidate_count || 0} kandidat
                        </p>
                    </td>

                    <td class="px-6 py-4 font-semibold ${item.missing_candidate_count > 0 ? 'text-red-600' : 'text-green-600'}">
                        ${item.missing_candidate_count || 0}
                    </td>
                </tr>
            `).join('');
        }

        function renderJuries() {
            const tableBody = document.getElementById('juryTableBody');
            const juries = monitoringData?.juries || [];

            if (!juries.length) {
                tableBody.innerHTML = emptyRow(4, 'Belum ada akun juri.');
                return;
            }

            tableBody.innerHTML = juries.map(item => `
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4">
                        <p class="font-extrabold text-slate-900">${escapeHtml(item.name || '-')}</p>
                        <p class="mt-1 text-xs text-slate-500">${escapeHtml(item.email || '-')}</p>
                    </td>

                    <td class="px-6 py-4 font-semibold text-slate-700">
                        ${item.assigned_criteria_count || 0}
                    </td>

                    <td class="px-6 py-4 font-semibold text-slate-700">
                        ${item.score_count || 0}
                    </td>

                    <td class="px-6 py-4">
                        ${renderActiveBadge(item.is_active)}
                    </td>
                </tr>
            `).join('');
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
                <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700">
                    Belum Lengkap
                </span>
            `;
        }

        function renderActiveBadge(value) {
            const active = value === true || value === 1 || value === '1';

            if (active) {
                return `
                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                        Aktif
                    </span>
                `;
            }

            return `
                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                    Nonaktif
                </span>
            `;
        }

        function renderMissingCriteria(items) {
            const list = items || [];

            if (!list.length) {
                return '-';
            }

            return `
                <div class="flex max-w-md flex-wrap gap-1">
                    ${list.slice(0, 6).map(item => `
                        <span class="rounded-md bg-red-50 px-2 py-1 text-xs font-bold text-red-600">
                            ${escapeHtml(item.criterion_code || '-')}
                        </span>
                    `).join('')}
                    ${list.length > 6 ? `
                        <span class="rounded-md bg-slate-100 px-2 py-1 text-xs font-bold text-slate-600">
                            +${list.length - 6}
                        </span>
                    ` : ''}
                </div>
            `;
        }

        function setLoadingState() {
            document.getElementById('candidateTableBody').innerHTML = emptyRow(8, 'Memuat data kandidat...');
            document.getElementById('criteriaTableBody').innerHTML = emptyRow(4, 'Memuat data kriteria...');
            document.getElementById('juryTableBody').innerHTML = emptyRow(4, 'Memuat data juri...');
        }

        function renderErrorState(message) {
            document.getElementById('candidateTableBody').innerHTML = errorRow(8, message);
            document.getElementById('criteriaTableBody').innerHTML = errorRow(4, message);
            document.getElementById('juryTableBody').innerHTML = errorRow(4, message);
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