@extends('layouts.jury')

@section('content')
    <section class="mb-7">
        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
            <div>
                <h1 class="text-[34px] font-extrabold leading-tight tracking-tight text-[#00288E]">
                    Riwayat Penilaian
                </h1>

                <p class="mt-2 text-sm font-medium text-slate-500">
                    Lihat rekap peserta yang sudah kamu nilai.
                </p>
            </div>

            <div class="flex items-end gap-2">
                <x-period-select width="w-40" />

                <button
                    type="button"
                    onclick="loadHistory()"
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
            <p class="text-sm font-semibold text-slate-700">Peserta Dinilai</p>
            <h2 id="scoredCandidateCount" class="mt-2 text-3xl font-extrabold text-slate-900">0</h2>
        </x-card>

        <x-card>
            <p class="text-sm font-semibold text-slate-700">Total Nilai Masuk</p>
            <h2 id="scoreRecordsCount" class="mt-2 text-3xl font-extrabold text-[#00288E]">0</h2>
        </x-card>

        <x-card>
            <p class="text-sm font-semibold text-slate-700">Kriteria Saya</p>
            <h2 id="assignedCriteriaCount" class="mt-2 text-3xl font-extrabold text-slate-900">0</h2>
        </x-card>

        <x-card>
            <p class="text-sm font-semibold text-slate-700">Status Nilai</p>
            <h2 id="lockStatus" class="mt-2 text-2xl font-extrabold text-green-600">Terbuka</h2>
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
                            Status Kelengkapan
                        </label>

                        <select
                            id="statusFilter"
                            class="h-11 w-full rounded-md border border-slate-300 bg-white px-4 text-sm outline-none focus:border-[#00288E] focus:ring-2 focus:ring-blue-100"
                        >
                            <option value="">Semua Status</option>
                            <option value="complete">Lengkap</option>
                            <option value="incomplete">Belum Lengkap</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button
                            type="button"
                            onclick="renderHistoryTable()"
                            class="h-11 w-full rounded-md border border-slate-300 bg-white px-4 text-sm font-bold text-slate-700 hover:bg-slate-50"
                        >
                            Filter
                        </button>
                    </div>
                </div>
            </div>

            <x-table
                :headers="['No', 'Peserta', 'NIM', 'Program Studi', 'Kriteria Dinilai', 'Rata-rata', 'Terakhir Diubah', 'Status', 'Aksi']"
                tbody-id="historyTableBody"
                class="rounded-none border-0 shadow-none"
            >
                <tr>
                    <td colspan="9" class="px-6 py-8 text-center text-slate-500">
                        Memuat riwayat penilaian...
                    </td>
                </tr>
            </x-table>
        </x-card>
    </section>
@endsection

@push('scripts')
    <script>
        let histories = [];
        let historyMeta = null;

        document.addEventListener('DOMContentLoaded', async function () {
            const params = new URLSearchParams(window.location.search);
            const periodId = params.get('period_id') || '1';

            await loadPeriodOptions('periodIdInput', periodId);

            loadHistory();

            document.getElementById('periodIdInput')?.addEventListener('change', function () {
                loadHistory();
            });

            document.getElementById('searchInput')?.addEventListener('input', debounce(function () {
                renderHistoryTable();
            }, 300));

            document.getElementById('statusFilter')?.addEventListener('change', function () {
                renderHistoryTable();
            });
        });

        async function loadHistory() {
            document.getElementById('historyTableBody').innerHTML = emptyRow(9, 'Memuat riwayat penilaian...');

            try {
                const params = new URLSearchParams({
                    period_id: getPeriodId(),
                });

                const result = await DutaJury.request(`/jury/scoring-history?${params.toString()}`);
                const data = result?.data || {};

                historyMeta = data;
                histories = data.histories || [];

                renderSummary(data);
                renderHistoryTable();
            } catch (error) {
                showAlert('danger', getErrorMessage(error));
                document.getElementById('historyTableBody').innerHTML = errorRow(9, getErrorMessage(error));
            }
        }

        function renderSummary(data) {
            const summary = data?.summary || {};

            setText('scoredCandidateCount', summary.scored_candidate_count || 0);
            setText('scoreRecordsCount', summary.score_records_count || 0);
            setText('assignedCriteriaCount', summary.assigned_criteria_count || 0);

            const lock = document.getElementById('lockStatus');

            if (data?.is_result_published) {
                lock.textContent = 'Terkunci';
                lock.className = 'mt-2 text-2xl font-extrabold text-red-600';
            } else {
                lock.textContent = 'Terbuka';
                lock.className = 'mt-2 text-2xl font-extrabold text-green-600';
            }
        }

        function renderHistoryTable() {
            const tableBody = document.getElementById('historyTableBody');
            const keyword = (document.getElementById('searchInput')?.value || '').toLowerCase();
            const status = document.getElementById('statusFilter')?.value || '';

            const filtered = histories.filter(item => {
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
                tableBody.innerHTML = emptyRow(9, 'Riwayat penilaian tidak ditemukan.');
                return;
            }

            tableBody.innerHTML = filtered.map((item, index) => `
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 text-slate-600">
                        ${index + 1}
                    </td>

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
                        <p class="font-semibold text-slate-800">
                            ${item.scored_criteria_count || 0}/${item.assigned_criteria_count || 0}
                        </p>
                        <p class="mt-1 text-xs text-slate-500">
                            ${formatNumber(item.completion_percentage || 0)}%
                        </p>
                    </td>

                    <td class="px-6 py-4 font-semibold text-slate-700">
                        ${formatNumber(item.average_score)}
                    </td>

                    <td class="px-6 py-4 text-slate-700">
                        ${formatDateTime(item.last_updated_at)}
                    </td>

                    <td class="px-6 py-4">
                        ${renderCompleteBadge(item.is_complete)}
                    </td>

                    <td class="px-6 py-4">
                        <a
                            href="/jury/history/${item.candidate_id}?period_id=${getPeriodId()}"
                            class="rounded-md border border-slate-300 bg-white px-3 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50"
                        >
                            Lihat Detail
                        </a>
                    </td>
                </tr>
            `).join('');
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
                    Belum Lengkap
                </span>
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
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
            });
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