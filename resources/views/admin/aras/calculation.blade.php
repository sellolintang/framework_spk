@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Detail Perhitungan ARAS</h1>
            <p class="mt-1 text-sm text-slate-600">
                Menampilkan proses perhitungan ARAS step by step seperti tabel perhitungan manual.
            </p>
        </div>

        <div class="flex flex-col gap-2 sm:flex-row">
            <select id="periodSelect" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                <option value="">Memuat periode...</option>
            </select>

            <button id="reloadBtn" type="button" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
                Muat Ulang
            </button>

            <a href="{{ route('admin.aras.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-center text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                Kembali
            </a>
        </div>
    </div>

    <div id="alertBox" class="hidden rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700"></div>

    <div id="loadingBox" class="rounded-xl border border-slate-200 bg-white p-6 text-center text-sm text-slate-500 shadow-sm">
        Memuat detail perhitungan ARAS...
    </div>

    <div id="contentBox" class="hidden space-y-6">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Periode</p>
                <p id="summaryPeriod" class="mt-2 text-lg font-bold text-slate-900">-</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Jumlah Kandidat</p>
                <p id="summaryCandidate" class="mt-2 text-lg font-bold text-slate-900">0</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Jumlah Kriteria</p>
                <p id="summaryCriteria" class="mt-2 text-lg font-bold text-slate-900">0</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Bobot</p>
                <p id="summaryWeight" class="mt-2 text-lg font-bold text-slate-900">0</p>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Si A0</p>
                <p id="summaryIdeal" class="mt-2 text-lg font-bold text-slate-900">0</p>
            </div>
        </div>

        <x-aras-step title="1. Bobot Kriteria" description="Daftar kriteria, bobot awal, bobot normalisasi, dan tipe kriteria.">
            <div id="criteriaTable"></div>
        </x-aras-step>

        <x-aras-step title="2. Matriks Keputusan X" description="Nilai awal hasil rata-rata penilaian juri untuk setiap kandidat dan kriteria.">
            <div id="decisionMatrixTable"></div>
        </x-aras-step>

        <x-aras-step title="3. Alternatif Ideal A0" description="Nilai optimum target untuk setiap kriteria. Untuk kriteria benefit diambil nilai terbesar.">
            <div id="idealTable"></div>
        </x-aras-step>

        <x-aras-step title="4. Total Kolom" description="Total setiap kolom setelah baris A0 dimasukkan ke matriks keputusan.">
            <div id="columnTotalTable"></div>
        </x-aras-step>

        <x-aras-step title="5. Matriks Normalisasi" description="Setiap nilai dibagi total kolom pada kriteria yang sama.">
            <div id="normalizedMatrixTable"></div>
        </x-aras-step>

        <x-aras-step title="6. Matriks Terbobot" description="Matriks normalisasi dikalikan bobot kriteria. Bagian ini juga menampilkan nilai Si dan Ki.">
            <div id="weightedMatrixTable"></div>
        </x-aras-step>

        <x-aras-step title="7. Ranking Akhir" description="Ranking akhir diurutkan berdasarkan nilai Ki terbesar.">
            <div id="rankingTable"></div>
        </x-aras-step>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const state = {
        periods: [],
        periodId: new URLSearchParams(window.location.search).get('period_id') || null,
        criteria: [],
        detail: null,
    };

    const elements = {
        periodSelect: document.getElementById('periodSelect'),
        reloadBtn: document.getElementById('reloadBtn'),
        alertBox: document.getElementById('alertBox'),
        loadingBox: document.getElementById('loadingBox'),
        contentBox: document.getElementById('contentBox'),
        summaryPeriod: document.getElementById('summaryPeriod'),
        summaryCandidate: document.getElementById('summaryCandidate'),
        summaryCriteria: document.getElementById('summaryCriteria'),
        summaryWeight: document.getElementById('summaryWeight'),
        summaryIdeal: document.getElementById('summaryIdeal'),
        criteriaTable: document.getElementById('criteriaTable'),
        decisionMatrixTable: document.getElementById('decisionMatrixTable'),
        idealTable: document.getElementById('idealTable'),
        columnTotalTable: document.getElementById('columnTotalTable'),
        normalizedMatrixTable: document.getElementById('normalizedMatrixTable'),
        weightedMatrixTable: document.getElementById('weightedMatrixTable'),
        rankingTable: document.getElementById('rankingTable'),
    };

    init();

    async function init() {
        bindEvents();

        try {
            await loadPeriods();

            if (!state.periodId && state.periods.length > 0) {
                const activePeriod = state.periods.find(period => ['scoring', 'finished', 'active'].includes(String(period.status).toLowerCase()));
                state.periodId = String((activePeriod || state.periods[0]).id);
            }

            elements.periodSelect.value = state.periodId || '';

            if (state.periodId) {
                await loadCalculationDetail();
            } else {
                showError('Periode belum tersedia.');
            }
        } catch (error) {
            showError(error.message || 'Gagal memuat halaman.');
        }
    }

    function bindEvents() {
        elements.reloadBtn.addEventListener('click', () => {
            state.periodId = elements.periodSelect.value;
            loadCalculationDetail();
        });

        elements.periodSelect.addEventListener('change', () => {
            state.periodId = elements.periodSelect.value;
            loadCalculationDetail();
        });
    }

    async function loadPeriods() {
        const response = await fetch('/api/periods', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        const payload = await response.json();

        if (!response.ok || payload.success === false) {
            throw new Error(payload.message || 'Gagal mengambil data periode.');
        }

        state.periods = normalizeList(payload.data);

        elements.periodSelect.innerHTML = state.periods.map(period => {
            const label = period.election_year
                ? `Periode ${period.election_year} (${period.status})`
                : `Periode #${period.id}`;

            return `<option value="${escapeHtml(period.id)}">${escapeHtml(label)}</option>`;
        }).join('');

        if (state.periods.length === 0) {
            elements.periodSelect.innerHTML = '<option value="">Tidak ada periode</option>';
        }
    }

    async function loadCalculationDetail() {
        if (!state.periodId) {
            showError('Pilih periode terlebih dahulu.');
            return;
        }

        showLoading();

        const response = await fetch(`/api/aras-results/calculation-detail?period_id=${encodeURIComponent(state.periodId)}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        const payload = await response.json();

        if (!response.ok || payload.success === false) {
            const detail = payload.errors ? formatErrors(payload.errors) : '';
            showError((payload.message || 'Gagal mengambil detail perhitungan ARAS.') + detail);
            return;
        }

        state.detail = payload.data;
        state.criteria = payload.data.criteria || [];

        renderPage();
        showContent();
    }

    function renderPage() {
        const data = state.detail;
        const period = data.period || {};

        elements.summaryPeriod.textContent = period.election_year ? `Periode ${period.election_year}` : `Periode #${data.period_id}`;
        elements.summaryCandidate.textContent = data.candidate_count || 0;
        elements.summaryCriteria.textContent = data.criteria_count || 0;
        elements.summaryWeight.textContent = formatNumber(data.weight_total, 6);
        elements.summaryIdeal.textContent = formatNumber(data.ideal_score, 6);

        renderCriteriaTable(data.steps.criteria_weights || []);
        renderMatrixTable(elements.decisionMatrixTable, data.steps.decision_matrix || [], {
            showNumber: true,
            showTotalScore: false,
            showUtilityScore: false,
            precision: 3,
        });

        renderSingleRowTable(elements.idealTable, data.steps.ideal_row, 'A0 (Optimum Target)', 3);
        renderSingleRowTable(elements.columnTotalTable, data.steps.column_totals, 'Total Kolom', 3);

        renderMatrixTable(elements.normalizedMatrixTable, data.steps.normalized_matrix || [], {
            showNumber: false,
            showTotalScore: false,
            showUtilityScore: false,
            precision: 6,
        });

        renderMatrixTable(elements.weightedMatrixTable, data.steps.weighted_matrix || [], {
            showNumber: false,
            showTotalScore: true,
            showUtilityScore: true,
            precision: 6,
        });

        renderRankingTable(data.steps.ranking || []);
    }

    function renderCriteriaTable(criteria) {
        const rows = criteria.map(item => `
            <tr class="border-b border-slate-100 hover:bg-slate-50">
                <td class="whitespace-nowrap px-3 py-3 text-sm font-semibold text-slate-900">${escapeHtml(item.code)}</td>
                <td class="min-w-72 px-3 py-3 text-sm text-slate-700">${escapeHtml(item.name)}</td>
                <td class="whitespace-nowrap px-3 py-3 text-right text-sm text-slate-700">${formatNumber(item.weight, 6)}</td>
                <td class="whitespace-nowrap px-3 py-3 text-right text-sm text-slate-700">${formatNumber(item.normalized_weight, 6)}</td>
                <td class="whitespace-nowrap px-3 py-3 text-sm text-slate-700">${escapeHtml(item.type)}</td>
            </tr>
        `).join('');

        elements.criteriaTable.innerHTML = tableWrapper(`
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-left">
                        <th class="px-3 py-3 text-xs font-bold uppercase text-slate-500">Kode</th>
                        <th class="px-3 py-3 text-xs font-bold uppercase text-slate-500">Nama Kriteria</th>
                        <th class="px-3 py-3 text-right text-xs font-bold uppercase text-slate-500">Bobot</th>
                        <th class="px-3 py-3 text-right text-xs font-bold uppercase text-slate-500">Bobot Normalisasi</th>
                        <th class="px-3 py-3 text-xs font-bold uppercase text-slate-500">Tipe</th>
                    </tr>
                </thead>
                <tbody>${rows}</tbody>
            </table>
        `);
    }

    function renderMatrixTable(target, rows, options = {}) {
        const criteriaCodes = state.criteria.map(item => item.code);

        const header = `
            <tr class="border-b border-slate-200 bg-slate-50">
                ${options.showNumber ? '<th class="sticky left-0 z-20 min-w-24 bg-slate-50 px-3 py-3 text-left text-xs font-bold uppercase text-slate-500">No</th>' : ''}
                <th class="sticky ${options.showNumber ? 'left-24' : 'left-0'} z-20 min-w-72 bg-slate-50 px-3 py-3 text-left text-xs font-bold uppercase text-slate-500">Kandidat</th>
                ${criteriaCodes.map(code => `<th class="min-w-28 px-3 py-3 text-right text-xs font-bold uppercase text-slate-500">${escapeHtml(code)}</th>`).join('')}
                ${options.showTotalScore ? '<th class="min-w-28 px-3 py-3 text-right text-xs font-bold uppercase text-slate-500">Si</th>' : ''}
                ${options.showUtilityScore ? '<th class="min-w-28 px-3 py-3 text-right text-xs font-bold uppercase text-slate-500">Ki</th>' : ''}
            </tr>
        `;

        const body = rows.map((row, index) => {
            const isIdeal = row.type === 'ideal';

            return `
                <tr class="border-b border-slate-100 ${isIdeal ? 'bg-blue-50' : 'hover:bg-slate-50'}">
                    ${options.showNumber ? `<td class="sticky left-0 z-10 bg-inherit px-3 py-3 text-sm text-slate-700">${index + 1}</td>` : ''}
                    <td class="sticky ${options.showNumber ? 'left-24' : 'left-0'} z-10 min-w-72 bg-inherit px-3 py-3 text-sm font-semibold text-slate-900">
                        ${escapeHtml(row.candidate_name || row.label || '-')}
                        ${row.registration_number ? `<div class="text-xs font-normal text-slate-500">${escapeHtml(row.registration_number)}</div>` : ''}
                    </td>
                    ${criteriaCodes.map(code => `<td class="px-3 py-3 text-right text-sm text-slate-700">${formatNumber(row.values?.[code], options.precision || 6)}</td>`).join('')}
                    ${options.showTotalScore ? `<td class="px-3 py-3 text-right text-sm font-semibold text-slate-900">${formatNumber(row.total_score, 6)}</td>` : ''}
                    ${options.showUtilityScore ? `<td class="px-3 py-3 text-right text-sm font-semibold text-slate-900">${formatNumber(row.utility_score, 6)}</td>` : ''}
                </tr>
            `;
        }).join('');

        target.innerHTML = tableWrapper(`
            <table class="min-w-full border-collapse">
                <thead>${header}</thead>
                <tbody>${body}</tbody>
            </table>
        `);
    }

    function renderSingleRowTable(target, row, label, precision = 6) {
        const criteriaCodes = state.criteria.map(item => item.code);
        const values = row?.values || {};

        const header = `
            <tr class="border-b border-slate-200 bg-slate-50">
                <th class="sticky left-0 z-20 min-w-72 bg-slate-50 px-3 py-3 text-left text-xs font-bold uppercase text-slate-500">Label</th>
                ${criteriaCodes.map(code => `<th class="min-w-28 px-3 py-3 text-right text-xs font-bold uppercase text-slate-500">${escapeHtml(code)}</th>`).join('')}
            </tr>
        `;

        const body = `
            <tr class="border-b border-slate-100 bg-blue-50">
                <td class="sticky left-0 z-10 min-w-72 bg-blue-50 px-3 py-3 text-sm font-semibold text-slate-900">${escapeHtml(label)}</td>
                ${criteriaCodes.map(code => `<td class="px-3 py-3 text-right text-sm text-slate-700">${formatNumber(values[code], precision)}</td>`).join('')}
            </tr>
        `;

        target.innerHTML = tableWrapper(`
            <table class="min-w-full border-collapse">
                <thead>${header}</thead>
                <tbody>${body}</tbody>
            </table>
        `);
    }

    function renderRankingTable(rows) {
        const body = rows.map(row => `
            <tr class="border-b border-slate-100 hover:bg-slate-50">
                <td class="whitespace-nowrap px-3 py-3 text-sm font-bold text-slate-900">${escapeHtml(row.final_rank)}</td>
                <td class="min-w-72 px-3 py-3 text-sm font-semibold text-slate-900">
                    ${escapeHtml(row.candidate_name)}
                    ${row.registration_number ? `<div class="text-xs font-normal text-slate-500">${escapeHtml(row.registration_number)}</div>` : ''}
                </td>
                <td class="whitespace-nowrap px-3 py-3 text-right text-sm text-slate-700">${formatNumber(row.total_score, 6)}</td>
                <td class="whitespace-nowrap px-3 py-3 text-right text-sm font-semibold text-slate-900">${formatNumber(row.utility_score, 6)}</td>
            </tr>
        `).join('');

        elements.rankingTable.innerHTML = tableWrapper(`
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="px-3 py-3 text-left text-xs font-bold uppercase text-slate-500">Ranking</th>
                        <th class="px-3 py-3 text-left text-xs font-bold uppercase text-slate-500">Kandidat</th>
                        <th class="px-3 py-3 text-right text-xs font-bold uppercase text-slate-500">Si</th>
                        <th class="px-3 py-3 text-right text-xs font-bold uppercase text-slate-500">Ki</th>
                    </tr>
                </thead>
                <tbody>${body}</tbody>
            </table>
        `);
    }

    function tableWrapper(tableHtml) {
        return `
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                <div class="max-h-[520px] overflow-auto">
                    ${tableHtml}
                </div>
            </div>
        `;
    }

    function normalizeList(data) {
        if (Array.isArray(data)) {
            return data;
        }

        if (Array.isArray(data?.data)) {
            return data.data;
        }

        return [];
    }

    function formatNumber(value, precision = 6) {
        if (value === null || value === undefined || value === '') {
            return '-';
        }

        const number = Number(value);

        if (Number.isNaN(number)) {
            return '-';
        }

        return number.toFixed(precision);
    }

    function formatErrors(errors) {
        if (typeof errors === 'string') {
            return ` ${errors}`;
        }

        if (errors?.missing_count) {
            return ` Total data kosong: ${errors.missing_count}.`;
        }

        return '';
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function showLoading() {
        elements.alertBox.classList.add('hidden');
        elements.contentBox.classList.add('hidden');
        elements.loadingBox.classList.remove('hidden');
    }

    function showContent() {
        elements.loadingBox.classList.add('hidden');
        elements.alertBox.classList.add('hidden');
        elements.contentBox.classList.remove('hidden');
    }

    function showError(message) {
        elements.loadingBox.classList.add('hidden');
        elements.contentBox.classList.add('hidden');
        elements.alertBox.textContent = message;
        elements.alertBox.classList.remove('hidden');
    }
});
</script>
@endpush