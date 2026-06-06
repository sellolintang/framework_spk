@extends('layouts.admin')

@section('content')
<div class="px-6 py-6 lg:px-8">

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Manajemen Periode Seleksi</h1>
            <p class="mt-1 text-sm text-slate-500">
                Kelola tahun pemilihan, jadwal pendaftaran, jadwal wawancara, dan status proses seleksi.
            </p>
        </div>

        <button
            type="button"
            id="btnOpenForm"
            class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-700">
            Tambah Periode
        </button>
    </div>

    {{-- Alert --}}
    <div id="alertBox" class="mb-5 hidden rounded-xl px-4 py-3 text-sm"></div>

    {{-- Statistik --}}
    <div class="mb-6 grid gap-4 md:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-sm text-slate-500">Total Periode</p>
            <h2 id="totalPeriod" class="mt-2 text-2xl font-bold text-slate-900">0</h2>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-sm text-slate-500">Pendaftaran Aktif</p>
            <h2 id="registrationPeriod" class="mt-2 text-2xl font-bold text-green-600">0</h2>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-sm text-slate-500">Tahap Penilaian</p>
            <h2 id="scoringPeriod" class="mt-2 text-2xl font-bold text-amber-600">0</h2>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-sm text-slate-500">Selesai</p>
            <h2 id="finishedPeriod" class="mt-2 text-2xl font-bold text-slate-700">0</h2>
        </div>
    </div>

    {{-- Form Tambah/Edit --}}
    <div id="formCard" class="mb-6 hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-4 flex items-center justify-between">
            <h2 id="formTitle" class="text-lg font-semibold text-slate-900">Tambah Periode</h2>

            <button
                type="button"
                id="btnCloseForm"
                class="text-sm font-medium text-slate-500 hover:text-slate-800">
                Tutup
            </button>
        </div>

        <form id="periodForm" class="grid gap-4 md:grid-cols-2">
            <input type="hidden" id="periodId">

            <div>
                <label for="electionYear" class="mb-1 block text-sm font-medium text-slate-700">
                    Tahun Pemilihan
                </label>
                <input
                    type="number"
                    id="electionYear"
                    min="2000"
                    max="2100"
                    required
                    placeholder="Contoh: 2026"
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="status" class="mb-1 block text-sm font-medium text-slate-700">
                    Status Periode
                </label>
                <select
                    id="status"
                    required
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="draft">Draft</option>
                    <option value="registration">Registration</option>
                    <option value="interview">Interview</option>
                    <option value="scoring">Scoring</option>
                    <option value="finished">Finished</option>
                </select>
            </div>

            <div>
                <label for="registrationStart" class="mb-1 block text-sm font-medium text-slate-700">
                    Mulai Pendaftaran
                </label>
                <input
                    type="datetime-local"
                    id="registrationStart"
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="registrationEnd" class="mb-1 block text-sm font-medium text-slate-700">
                    Akhir Pendaftaran
                </label>
                <input
                    type="datetime-local"
                    id="registrationEnd"
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="interviewStart" class="mb-1 block text-sm font-medium text-slate-700">
                    Mulai Wawancara
                </label>
                <input
                    type="datetime-local"
                    id="interviewStart"
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="interviewEnd" class="mb-1 block text-sm font-medium text-slate-700">
                    Akhir Wawancara
                </label>
                <input
                    type="datetime-local"
                    id="interviewEnd"
                    class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div class="md:col-span-2 flex justify-end gap-2 pt-2">
                <button
                    type="button"
                    id="btnResetForm"
                    class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Reset
                </button>

                <button
                    type="submit"
                    id="btnSubmitForm"
                    class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                    Simpan Periode
                </button>
            </div>
        </form>

        <div id="formError" class="mt-4 hidden rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700"></div>
    </div>

    {{-- Filter --}}
    <div class="mb-4 grid gap-3 md:grid-cols-3">
        <input
            type="number"
            id="filterYear"
            placeholder="Cari tahun, contoh: 2026"
            class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">

        <select
            id="filterStatus"
            class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">Semua Status</option>
            <option value="draft">Draft</option>
            <option value="registration">Registration</option>
            <option value="interview">Interview</option>
            <option value="scoring">Scoring</option>
            <option value="finished">Finished</option>
        </select>

        <div class="flex gap-2">
            <button
                type="button"
                id="btnFilter"
                class="flex-1 rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Filter
            </button>

            <button
                type="button"
                id="btnResetFilter"
                class="flex-1 rounded-xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200">
                Reset
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Tahun</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Pendaftaran</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Wawancara</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-slate-500">Aksi</th>
                    </tr>
                </thead>

                <tbody id="periodTable" class="divide-y divide-slate-100">
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-sm text-slate-500">
                            Memuat data periode...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let periods = [];

    const elements = {
        alertBox: document.getElementById('alertBox'),

        totalPeriod: document.getElementById('totalPeriod'),
        registrationPeriod: document.getElementById('registrationPeriod'),
        scoringPeriod: document.getElementById('scoringPeriod'),
        finishedPeriod: document.getElementById('finishedPeriod'),

        formCard: document.getElementById('formCard'),
        formTitle: document.getElementById('formTitle'),
        periodForm: document.getElementById('periodForm'),
        formError: document.getElementById('formError'),

        periodId: document.getElementById('periodId'),
        electionYear: document.getElementById('electionYear'),
        status: document.getElementById('status'),
        registrationStart: document.getElementById('registrationStart'),
        registrationEnd: document.getElementById('registrationEnd'),
        interviewStart: document.getElementById('interviewStart'),
        interviewEnd: document.getElementById('interviewEnd'),

        filterYear: document.getElementById('filterYear'),
        filterStatus: document.getElementById('filterStatus'),
        periodTable: document.getElementById('periodTable'),

        btnOpenForm: document.getElementById('btnOpenForm'),
        btnCloseForm: document.getElementById('btnCloseForm'),
        btnResetForm: document.getElementById('btnResetForm'),
        btnSubmitForm: document.getElementById('btnSubmitForm'),
        btnFilter: document.getElementById('btnFilter'),
        btnResetFilter: document.getElementById('btnResetFilter'),
    };

    async function apiRequest(endpoint, options = {}) {
        if (!window.DutaAdmin) {
            throw new Error('Helper request admin tidak ditemukan.');
        }

        const cleanEndpoint = endpoint.startsWith('/api')
            ? endpoint.slice(4)
            : endpoint;

        const result = await DutaAdmin.request(cleanEndpoint, options);

        return result || {};
    }

    function extractPeriodItems(result) {
        if (Array.isArray(result)) {
            return result;
        }

        if (Array.isArray(result?.data?.data)) {
            return result.data.data;
        }

        if (Array.isArray(result?.data)) {
            return result.data;
        }

        if (Array.isArray(result?.items)) {
            return result.items;
        }

        if (Array.isArray(result?.data?.items)) {
            return result.data.items;
        }

        return [];
    }

    function getErrorMessage(error, fallback = 'Terjadi kesalahan.') {
        if (typeof error === 'string') {
            return error;
        }

        if (error?.message) {
            return error.message;
        }

        return fallback;
    }

    function getErrorValidation(error) {
        if (error?.errors) {
            return error.errors;
        }

        if (error?.response?.errors) {
            return error.response.errors;
        }

        return null;
    }

    function showAlert(message, type = 'success') {
        const classes = {
            success: 'bg-green-50 text-green-700 border border-green-200',
            error: 'bg-red-50 text-red-700 border border-red-200',
            info: 'bg-blue-50 text-blue-700 border border-blue-200',
        };

        elements.alertBox.className = 'mb-5 rounded-xl px-4 py-3 text-sm ' + (classes[type] || classes.info);
        elements.alertBox.textContent = message;
        elements.alertBox.classList.remove('hidden');

        setTimeout(function () {
            elements.alertBox.classList.add('hidden');
        }, 3500);
    }

    function showFormError(message, errors = null) {
        let html = message || 'Terjadi kesalahan.';

        if (errors) {
            html += '<ul class="mt-2 list-disc pl-5">';

            Object.values(errors).forEach(function (messages) {
                if (Array.isArray(messages)) {
                    messages.forEach(function (item) {
                        html += `<li>${item}</li>`;
                    });
                    return;
                }

                html += `<li>${messages}</li>`;
            });

            html += '</ul>';
        }

        elements.formError.innerHTML = html;
        elements.formError.classList.remove('hidden');
    }

    function clearFormError() {
        elements.formError.innerHTML = '';
        elements.formError.classList.add('hidden');
    }

    function setLoadingTable(message = 'Memuat data periode...') {
        elements.periodTable.innerHTML = `
            <tr>
                <td colspan="5" class="px-4 py-6 text-center text-sm text-slate-500">
                    ${message}
                </td>
            </tr>
        `;
    }

    function formatDate(value) {
        if (!value) {
            return '-';
        }

        const date = new Date(value);

        if (Number.isNaN(date.getTime())) {
            return '-';
        }

        return date.toLocaleString('id-ID', {
            dateStyle: 'medium',
            timeStyle: 'short',
        });
    }

    function toInputDateTime(value) {
        if (!value) {
            return '';
        }

        const date = new Date(value);

        if (Number.isNaN(date.getTime())) {
            return '';
        }

        const pad = function (number) {
            return String(number).padStart(2, '0');
        };

        return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
    }

    function normalizeDateTime(value) {
        if (!value) {
            return null;
        }

        return value.replace('T', ' ') + ':00';
    }

    function getStatusLabel(status) {
        const labels = {
            draft: 'Draft',
            registration: 'Registration',
            interview: 'Interview',
            scoring: 'Scoring',
            finished: 'Finished',
        };

        return labels[status] || status || '-';
    }

    function getStatusBadge(status) {
        const classes = {
            draft: 'bg-slate-100 text-slate-700',
            registration: 'bg-green-100 text-green-700',
            interview: 'bg-blue-100 text-blue-700',
            scoring: 'bg-amber-100 text-amber-700',
            finished: 'bg-purple-100 text-purple-700',
        };

        return `
            <span class="rounded-full px-3 py-1 text-xs font-semibold ${classes[status] || 'bg-slate-100 text-slate-700'}">
                ${getStatusLabel(status)}
            </span>
        `;
    }

    function renderStats() {
        elements.totalPeriod.textContent = periods.length;
        elements.registrationPeriod.textContent = periods.filter(item => item.status === 'registration').length;
        elements.scoringPeriod.textContent = periods.filter(item => item.status === 'scoring').length;
        elements.finishedPeriod.textContent = periods.filter(item => item.status === 'finished').length;
    }

    function renderTable() {
        if (!periods.length) {
            elements.periodTable.innerHTML = `
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-sm text-slate-500">
                        Belum ada data periode seleksi.
                    </td>
                </tr>
            `;
            return;
        }

        elements.periodTable.innerHTML = periods.map(function (period) {
            return `
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-4 text-sm font-semibold text-slate-900">
                        ${period.election_year || '-'}
                    </td>

                    <td class="px-4 py-4 text-sm text-slate-600">
                        ${formatDate(period.registration_start)}
                        <br>
                        <span class="text-xs text-slate-400">
                            sampai ${formatDate(period.registration_end)}
                        </span>
                    </td>

                    <td class="px-4 py-4 text-sm text-slate-600">
                        ${formatDate(period.interview_start)}
                        <br>
                        <span class="text-xs text-slate-400">
                            sampai ${formatDate(period.interview_end)}
                        </span>
                    </td>

                    <td class="px-4 py-4 text-sm">
                        ${getStatusBadge(period.status)}
                    </td>

                    <td class="px-4 py-4 text-right text-sm">
                        <button
                            type="button"
                            data-action="edit"
                            data-id="${period.id}"
                            class="mr-2 font-semibold text-blue-600 hover:text-blue-800">
                            Edit
                        </button>

                        <button
                            type="button"
                            data-action="delete"
                            data-id="${period.id}"
                            class="font-semibold text-red-600 hover:text-red-800">
                            Hapus
                        </button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    async function loadPeriods() {
        setLoadingTable();

        const params = new URLSearchParams();

        const year = elements.filterYear.value.trim();
        const status = elements.filterStatus.value.trim();

        if (year !== '') {
            params.append('election_year', year);
        }

        if (status !== '') {
            params.append('status', status);
        }

        params.append('per_page', '50');

        try {
            const result = await apiRequest('/periods?' + params.toString(), {
                method: 'GET',
            });

            periods = extractPeriodItems(result);

            renderStats();
            renderTable();
        } catch (error) {
            console.error('Gagal memuat periode:', error);

            periods = [];
            renderStats();

            elements.periodTable.innerHTML = `
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-sm text-red-600">
                        ${getErrorMessage(error, 'Data periode gagal dimuat.')}
                    </td>
                </tr>
            `;
        }
    }

    function openForm() {
        clearFormError();
        elements.formCard.classList.remove('hidden');
        elements.electionYear.focus();
    }

    function closeForm() {
        elements.formCard.classList.add('hidden');
        resetForm();
    }

    function resetForm() {
        elements.periodId.value = '';
        elements.periodForm.reset();
        elements.status.value = 'draft';
        elements.formTitle.textContent = 'Tambah Periode';
        elements.btnSubmitForm.textContent = 'Simpan Periode';
        clearFormError();
    }

    function editPeriod(id) {
        const period = periods.find(function (item) {
            return Number(item.id) === Number(id);
        });

        if (!period) {
            showAlert('Data periode tidak ditemukan pada tabel.', 'error');
            return;
        }

        openForm();

        elements.formTitle.textContent = 'Edit Periode ' + period.election_year;
        elements.btnSubmitForm.textContent = 'Update Periode';

        elements.periodId.value = period.id;
        elements.electionYear.value = period.election_year || '';
        elements.status.value = period.status || 'draft';
        elements.registrationStart.value = toInputDateTime(period.registration_start);
        elements.registrationEnd.value = toInputDateTime(period.registration_end);
        elements.interviewStart.value = toInputDateTime(period.interview_start);
        elements.interviewEnd.value = toInputDateTime(period.interview_end);

        window.scrollTo({
            top: 0,
            behavior: 'smooth',
        });
    }

    async function savePeriod(event) {
        event.preventDefault();
        clearFormError();

        const id = elements.periodId.value;

        const payload = {
            election_year: Number(elements.electionYear.value),
            status: elements.status.value,
            registration_start: normalizeDateTime(elements.registrationStart.value),
            registration_end: normalizeDateTime(elements.registrationEnd.value),
            interview_start: normalizeDateTime(elements.interviewStart.value),
            interview_end: normalizeDateTime(elements.interviewEnd.value),
        };

        const endpoint = id ? '/periods/' + id : '/periods';
        const method = id ? 'PUT' : 'POST';

        elements.btnSubmitForm.disabled = true;
        elements.btnSubmitForm.textContent = id ? 'Mengupdate...' : 'Menyimpan...';

        try {
            await apiRequest(endpoint, {
                method: method,
                body: JSON.stringify(payload),
            });

            showAlert(id ? 'Periode berhasil diperbarui.' : 'Periode berhasil ditambahkan.', 'success');

            closeForm();
            await loadPeriods();
        } catch (error) {
            console.error('Gagal menyimpan periode:', error);

            showFormError(
                getErrorMessage(error, 'Periode gagal disimpan.'),
                getErrorValidation(error)
            );
        } finally {
            elements.btnSubmitForm.disabled = false;
            elements.btnSubmitForm.textContent = id ? 'Update Periode' : 'Simpan Periode';
        }
    }

    async function deletePeriod(id) {
        const confirmed = confirm('Yakin ingin menghapus periode ini? Data terkait periode ini dapat ikut terdampak.');

        if (!confirmed) {
            return;
        }

        try {
            await apiRequest('/periods/' + id, {
                method: 'DELETE',
            });

            showAlert('Periode berhasil dihapus.', 'success');
            await loadPeriods();
        } catch (error) {
            console.error('Gagal menghapus periode:', error);
            showAlert(getErrorMessage(error, 'Periode gagal dihapus.'), 'error');
        }
    }

    function resetFilter() {
        elements.filterYear.value = '';
        elements.filterStatus.value = '';
        loadPeriods();
    }

    elements.btnOpenForm?.addEventListener('click', openForm);
    elements.btnCloseForm?.addEventListener('click', closeForm);
    elements.btnResetForm?.addEventListener('click', resetForm);
    elements.periodForm?.addEventListener('submit', savePeriod);

    elements.btnFilter?.addEventListener('click', loadPeriods);
    elements.btnResetFilter?.addEventListener('click', resetFilter);

    elements.filterStatus?.addEventListener('change', loadPeriods);

    elements.filterYear?.addEventListener('keyup', function (event) {
        if (event.key === 'Enter') {
            loadPeriods();
        }
    });

    elements.periodTable?.addEventListener('click', function (event) {
        const button = event.target.closest('button');

        if (!button) {
            return;
        }

        const action = button.dataset.action;
        const id = button.dataset.id;

        if (action === 'edit') {
            editPeriod(id);
        }

        if (action === 'delete') {
            deletePeriod(id);
        }
    });

    loadPeriods();
});
</script>
@endpush