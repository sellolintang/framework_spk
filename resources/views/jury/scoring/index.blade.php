@extends('layouts.jury')

@section('content')
    <section class="mb-7">
        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
            <div>
                <h1 class="text-[34px] font-extrabold leading-tight tracking-tight text-[#00288E]">
                    Penilaian Peserta
                </h1>

                <p class="mt-2 text-sm font-medium text-slate-500">
                    Pilih peserta dan isi nilai berdasarkan kriteria yang ditugaskan kepadamu.
                </p>
            </div>

            <div class="flex items-end gap-2">
                <div>
                    <label for="periodIdInput" class="mb-1 block text-xs font-bold text-slate-600">
                        Periode ID
                    </label>

                    <input
                        id="periodIdInput"
                        type="number"
                        min="1"
                        value="1"
                        class="h-10 w-32 rounded-md border border-slate-300 bg-white px-4 text-sm outline-none focus:border-[#00288E] focus:ring-2 focus:ring-blue-100"
                    >
                </div>

                <button
                    type="button"
                    onclick="loadScoringCandidates()"
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
            <p class="text-sm font-semibold text-slate-700">Total Peserta</p>
            <h2 id="totalCandidates" class="mt-2 text-3xl font-extrabold text-slate-900">0</h2>
        </x-card>

        <x-card>
            <p class="text-sm font-semibold text-slate-700">Sudah Dinilai</p>
            <h2 id="completeCandidates" class="mt-2 text-3xl font-extrabold text-green-600">0</h2>
        </x-card>

        <x-card>
            <p class="text-sm font-semibold text-slate-700">Perlu Dinilai</p>
            <h2 id="incompleteCandidates" class="mt-2 text-3xl font-extrabold text-red-600">0</h2>
        </x-card>

        <x-card>
            <p class="text-sm font-semibold text-slate-700">Kriteria Saya</p>
            <h2 id="assignedCriteriaCount" class="mt-2 text-3xl font-extrabold text-[#00288E]">0</h2>
        </x-card>
    </section>

    <section class="mt-7">
        <x-card padding="p-0">
            <div class="border-b border-slate-200 px-5 py-4">
                <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                    <div>
                        <label for="searchInput" class="mb-1 block text-xs font-bold text-slate-600">
                            Pencarian
                        </label>

                        <input
                            id="searchInput"
                            type="text"
                            placeholder="Cari nama, NIM, atau program studi..."
                            class="h-11 w-full rounded-md border border-slate-300 bg-white px-4 text-sm outline-none focus:border-[#00288E] focus:ring-2 focus:ring-blue-100"
                        >
                    </div>

                    <div>
                        <label for="statusFilter" class="mb-1 block text-xs font-bold text-slate-600">
                            Status Penilaian
                        </label>

                        <select
                            id="statusFilter"
                            class="h-11 w-full rounded-md border border-slate-300 bg-white px-4 text-sm outline-none focus:border-[#00288E] focus:ring-2 focus:ring-blue-100"
                        >
                            <option value="">Semua Status</option>
                            <option value="complete">Sudah Dinilai</option>
                            <option value="incomplete">Perlu Dinilai</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button
                            type="button"
                            onclick="renderCandidates()"
                            class="h-11 w-full rounded-md border border-slate-300 bg-white px-4 text-sm font-bold text-slate-700 hover:bg-slate-50"
                        >
                            Filter
                        </button>
                    </div>
                </div>
            </div>

            <x-table
                :headers="['No', 'Peserta', 'NIM', 'Jadwal', 'Progress', 'Status', 'Aksi']"
                tbody-id="candidateTableBody"
                class="rounded-none border-0 shadow-none"
            >
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                        Memuat data peserta...
                    </td>
                </tr>
            </x-table>
        </x-card>
    </section>
@endsection

@push('scripts')
    <script>
        let candidatesData = [];
        let assignedCriteria = [];

        document.addEventListener('DOMContentLoaded', function () {
            const params = new URLSearchParams(window.location.search);
            const periodId = params.get('period_id');

            if (periodId) {
                document.getElementById('periodIdInput').value = periodId;
            }

            loadScoringCandidates();

            document.getElementById('periodIdInput')?.addEventListener('change', function () {
                loadScoringCandidates();
            });

            document.getElementById('searchInput')?.addEventListener('input', debounce(function () {
                renderCandidates();
            }, 300));

            document.getElementById('statusFilter')?.addEventListener('change', function () {
                renderCandidates();
            });
        });

        async function loadScoringCandidates() {
            const periodId = getPeriodId();

            document.getElementById('candidateTableBody').innerHTML = emptyRow(7, 'Memuat data peserta...');

            try {
                const params = new URLSearchParams({
                    period_id: periodId,
                });

                const result = await DutaJury.request(`/jury/scoring-candidates?${params.toString()}`);
                const data = result?.data || {};

                candidatesData = data.candidates || [];
                assignedCriteria = data.assigned_criteria || [];

                renderSummary();
                renderCandidates();
            } catch (error) {
                showAlert('danger', getErrorMessage(error));
                document.getElementById('candidateTableBody').innerHTML = errorRow(7, getErrorMessage(error));
            }
        }

        function renderSummary() {
            const total = candidatesData.length;
            const complete = candidatesData.filter(item => item.is_complete).length;
            const incomplete = total - complete;

            setText('totalCandidates', total);
            setText('completeCandidates', complete);
            setText('incompleteCandidates', incomplete);
            setText('assignedCriteriaCount', assignedCriteria.length);
        }

        function renderCandidates() {
            const tableBody = document.getElementById('candidateTableBody');
            const keyword = (document.getElementById('searchInput')?.value || '').toLowerCase();
            const status = document.getElementById('statusFilter')?.value || '';

            let filtered = candidatesData.filter(item => {
                const matchesKeyword = [
                    item.full_name,
                    item.student_number,
                    item.study_program,
                    item.registration_number,
                ].some(value => String(value || '').toLowerCase().includes(keyword));

                const matchesStatus =
                    !status ||
                    (status === 'complete' && item.is_complete) ||
                    (status === 'incomplete' && !item.is_complete);

                return matchesKeyword && matchesStatus;
            });

            if (!filtered.length) {
                tableBody.innerHTML = emptyRow(7, 'Data peserta tidak ditemukan.');
                return;
            }

            tableBody.innerHTML = filtered.map((item, index) => `
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
                        <p class="font-semibold">${formatDateTime(item.scheduled_at)}</p>
                        <p class="mt-1 text-xs text-slate-500">${escapeHtml(item.location || '-')}</p>
                    </td>

                    <td class="px-6 py-4">
                        ${renderProgress(item.completion_percentage)}
                        <p class="mt-1 text-xs text-slate-500">
                            ${item.scored_criteria_count || 0}/${item.assigned_criteria_count || 0} kriteria
                        </p>
                    </td>

                    <td class="px-6 py-4">
                        ${renderCompleteBadge(item.is_complete)}
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-2">
                            <a
                                href="/jury/scoring/${item.id}?period_id=${getPeriodId()}"
                                class="rounded-md border border-slate-300 bg-white px-3 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50"
                            >
                                Detail
                            </a>

                            <a
                                href="/jury/scoring/${item.id}/form?period_id=${getPeriodId()}"
                                class="rounded-md bg-[#00288E] px-3 py-2 text-xs font-bold text-white hover:bg-[#001F73]"
                            >
                                ${item.is_complete ? 'Edit Nilai' : 'Nilai'}
                            </a>
                        </div>
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
                        Sudah Dinilai
                    </span>
                `;
            }

            return `
                <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-bold text-yellow-700">
                    Perlu Dinilai
                </span>
            `;
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

        function getPeriodId() {
            return document.getElementById('periodIdInput')?.value || 1;
        }

        function formatNumber(value) {
            return new Intl.NumberFormat('id-ID', {
                maximumFractionDigits: 2,
            }).format(Number(value || 0));
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
            if (element) element.textContent = value;
        }

        function escapeHtml(value) {
            return String(value)
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        function debounce(callback, delay = 300) {
            let timer;

            return function (...args) {
                clearTimeout(timer);
                timer = setTimeout(() => callback.apply(this, args), delay);
            };
        }
    </script>
@endpush