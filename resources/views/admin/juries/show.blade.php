@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <section class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <div class="mb-3 flex items-center gap-2 text-sm font-bold text-slate-500">
                <a href="{{ route('admin.juries.index') }}" class="hover:text-blue-900">
                    Akun Juri
                </a>

                <span>/</span>

                <span class="text-blue-900">
                    Detail Juri
                </span>
            </div>

            <h1 class="text-4xl font-extrabold tracking-tight text-blue-900">
                Detail Akun Juri
            </h1>

            <p class="mt-2 text-sm font-semibold leading-6 text-slate-500">
                Lihat informasi akun juri, status akses, dan kriteria penilaian yang ditugaskan.
            </p>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row">
            <a
                href="{{ route('admin.juries.index') }}"
                class="inline-flex h-12 items-center justify-center rounded-xl border border-slate-300 bg-white px-5 text-sm font-extrabold text-slate-700 transition hover:bg-slate-50"
            >
                Kembali
            </a>

            <a
                href="{{ route('admin.juries.edit', $juryId) }}"
                class="inline-flex h-12 items-center justify-center rounded-xl bg-blue-900 px-5 text-sm font-extrabold text-white shadow-sm transition hover:bg-blue-800"
            >
                Edit Juri
            </a>
        </div>
    </section>

    {{-- Alert --}}
    <div id="pageAlert" class="hidden rounded-xl border px-4 py-3 text-sm font-semibold"></div>

    {{-- Loading --}}
    <section id="loadingState" class="rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm">
        <div class="mx-auto h-10 w-10 animate-spin rounded-full border-4 border-slate-200 border-t-blue-900"></div>

        <p class="mt-4 text-sm font-semibold text-slate-500">
            Memuat detail akun juri...
        </p>
    </section>

    {{-- Detail --}}
    <section id="detailContent" class="hidden space-y-6">
        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-semibold text-slate-500">
                    Status Akun
                </p>

                <div id="juryStatus" class="mt-3">
                    -
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-semibold text-slate-500">
                    Total Kriteria
                </p>

                <h2 id="criteriaCount" class="mt-2 text-4xl font-extrabold text-blue-900">
                    0
                </h2>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-semibold text-slate-500">
                    Role
                </p>

                <h2 id="juryRole" class="mt-2 text-4xl font-extrabold text-slate-900">
                    -
                </h2>
            </div>
        </div>

        {{-- Profile --}}
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <aside class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col items-center text-center">
                    <div id="juryAvatar" class="flex h-24 w-24 items-center justify-center rounded-2xl bg-blue-100 text-3xl font-extrabold text-blue-900">
                        -
                    </div>

                    <h2 id="juryNameTitle" class="mt-5 text-2xl font-extrabold text-slate-900">
                        -
                    </h2>

                    <p id="juryEmailTitle" class="mt-1 text-sm font-semibold text-slate-500">
                        -
                    </p>
                </div>

                <div class="mt-6 border-t border-slate-100 pt-5">
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-wide text-slate-400">
                                ID Juri
                            </p>

                            <p id="juryIdText" class="mt-1 text-sm font-bold text-slate-800">
                                -
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-wide text-slate-400">
                                Dibuat Pada
                            </p>

                            <p id="juryCreatedAt" class="mt-1 text-sm font-bold text-slate-800">
                                -
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-wide text-slate-400">
                                Terakhir Diperbarui
                            </p>

                            <p id="juryUpdatedAt" class="mt-1 text-sm font-bold text-slate-800">
                                -
                            </p>
                        </div>
                    </div>
                </div>
            </aside>

            <section class="space-y-6 xl:col-span-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="border-b border-slate-200 pb-4">
                        <h2 class="text-xl font-extrabold text-slate-900">
                            Informasi Akun
                        </h2>

                        <p class="mt-1 text-sm leading-6 text-slate-500">
                            Data akun yang digunakan juri untuk login dan mengakses halaman penilaian.
                        </p>
                    </div>

                    <div class="mt-5 grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-wide text-slate-400">
                                Nama Juri
                            </p>

                            <p id="juryName" class="mt-1 text-base font-extrabold text-slate-900">
                                -
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-wide text-slate-400">
                                Email
                            </p>

                            <p id="juryEmail" class="mt-1 text-base font-semibold text-slate-700">
                                -
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-wide text-slate-400">
                                Nomor HP
                            </p>

                            <p id="juryPhone" class="mt-1 text-base font-semibold text-slate-700">
                                -
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-wide text-slate-400">
                                Hak Akses
                            </p>

                            <p id="juryRoleText" class="mt-1 text-base font-semibold text-slate-700">
                                -
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-col gap-3 border-b border-slate-200 pb-4 md:flex-row md:items-start md:justify-between">
                        <div>
                            <h2 class="text-xl font-extrabold text-slate-900">
                                Ringkasan Penugasan
                            </h2>

                            <p class="mt-1 text-sm leading-6 text-slate-500">
                                Ringkasan kriteria yang dapat dinilai oleh juri ini.
                            </p>
                        </div>

                        <div id="assignmentStatusBadge"></div>
                    </div>

                    <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs font-extrabold uppercase tracking-wide text-slate-400">
                                Kriteria Aktif
                            </p>

                            <p id="activeCriteriaCount" class="mt-2 text-2xl font-extrabold text-slate-900">
                                0
                            </p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs font-extrabold uppercase tracking-wide text-slate-400">
                                Benefit
                            </p>

                            <p id="benefitCriteriaCount" class="mt-2 text-2xl font-extrabold text-slate-900">
                                0
                            </p>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs font-extrabold uppercase tracking-wide text-slate-400">
                                Cost
                            </p>

                            <p id="costCriteriaCount" class="mt-2 text-2xl font-extrabold text-slate-900">
                                0
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        {{-- Criteria Table --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-xl font-extrabold text-slate-900">
                    Kriteria yang Ditugaskan
                </h2>

                <p class="mt-1 text-sm leading-6 text-slate-500">
                    Daftar kriteria penilaian yang dapat diisi oleh juri ini.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-4 text-left font-extrabold uppercase tracking-wide text-slate-500">
                                Kode
                            </th>
                            <th class="px-5 py-4 text-left font-extrabold uppercase tracking-wide text-slate-500">
                                Nama Kriteria
                            </th>
                            <th class="px-5 py-4 text-left font-extrabold uppercase tracking-wide text-slate-500">
                                Bobot
                            </th>
                            <th class="px-5 py-4 text-left font-extrabold uppercase tracking-wide text-slate-500">
                                Tipe
                            </th>
                            <th class="px-5 py-4 text-left font-extrabold uppercase tracking-wide text-slate-500">
                                Rentang Nilai
                            </th>
                            <th class="px-5 py-4 text-left font-extrabold uppercase tracking-wide text-slate-500">
                                Status
                            </th>
                        </tr>
                    </thead>

                    <tbody id="criteriaTableBody" class="divide-y divide-slate-100 bg-white">
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-500">
                                Memuat data kriteria...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </section>
</div>
@endsection

@push('scripts')
<script>
    const juryId = @json($juryId);

    const pageParams = new URLSearchParams(window.location.search);
    const selectedPeriodId = pageParams.get('period_id') || '1';

    document.addEventListener('DOMContentLoaded', function () {
        loadJuryDetail();
    });

    async function loadJuryDetail() {
        showLoading();

        try {
            const result = await DutaAdmin.request(`/juries/${juryId}?period_id=${selectedPeriodId}`);
            const jury = normalizeJury(result);

            if (!jury) {
                throw new Error('Data juri tidak ditemukan.');
            }

            renderJuryDetail(jury);
            hideLoading();
        } catch (error) {
            console.error('Gagal memuat detail juri:', error);
            renderErrorState(getErrorMessage(error, 'Detail akun juri gagal dimuat.'));
        }
    }

    function normalizeJury(result) {
        if (!result) {
            return null;
        }

        if (result?.data?.jury) {
            const jury = result.data.jury;

            if (!Array.isArray(jury.criteria) && Array.isArray(result.data.criteria)) {
                jury.criteria = result.data.criteria;
            }

            return jury;
        }

        if (result?.jury) {
            const jury = result.jury;

            if (!Array.isArray(jury.criteria) && Array.isArray(result.criteria)) {
                jury.criteria = result.criteria;
            }

            return jury;
        }

        if (result?.data) {
            return result.data;
        }

        return result;
    }

    function getCriteria(jury) {
        if (Array.isArray(jury?.criteria)) {
            return jury.criteria;
        }

        if (Array.isArray(jury?.assigned_criteria)) {
            return jury.assigned_criteria;
        }

        if (Array.isArray(jury?.criteria_data)) {
            return jury.criteria_data;
        }

        if (Array.isArray(jury?.jury_criteria)) {
            return jury.jury_criteria.map(function (item) {
                return item.criterion || item.criteria || item;
            });
        }

        return [];
    }

    function renderJuryDetail(jury) {
        const criteria = getCriteria(jury);
        const activeCriteria = criteria.filter(item => isActiveValue(item.is_active));
        const benefitCriteria = criteria.filter(item => String(item.type || '').toLowerCase() === 'benefit');
        const costCriteria = criteria.filter(item => String(item.type || '').toLowerCase() === 'cost');

        setText('juryName', jury.name || '-');
        setText('juryNameTitle', jury.name || '-');
        setText('juryEmail', jury.email || '-');
        setText('juryEmailTitle', jury.email || '-');
        setText('juryPhone', jury.phone || '-');
        setText('juryIdText', jury.id || '-');
        setText('juryRole', formatRole(jury.role));
        setText('juryRoleText', formatRole(jury.role));
        setText('criteriaCount', formatNumber(criteria.length));
        setText('activeCriteriaCount', formatNumber(activeCriteria.length));
        setText('benefitCriteriaCount', formatNumber(benefitCriteria.length));
        setText('costCriteriaCount', formatNumber(costCriteria.length));
        setText('juryCreatedAt', formatDateTime(jury.created_at));
        setText('juryUpdatedAt', formatDateTime(jury.updated_at));

        setText('juryAvatar', getInitials(jury.name || 'Juri'));

        setHtml('juryStatus', renderStatusBadge(jury.is_active));
        setHtml('assignmentStatusBadge', renderAssignmentBadge(criteria.length));

        renderCriteriaTable(criteria);
    }

    function renderCriteriaTable(criteria) {
        const tableBody = document.getElementById('criteriaTableBody');

        if (!tableBody) {
            console.warn('Element criteriaTableBody tidak ditemukan.');
            return;
        }

        if (!criteria.length) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-5 py-8 text-center text-slate-500">
                        Belum ada kriteria yang ditugaskan kepada juri ini pada periode ini.
                    </td>
                </tr>
            `;
            return;
        }

        tableBody.innerHTML = criteria.map(function (item) {
            return `
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-4">
                        <span class="inline-flex rounded-lg bg-blue-100 px-3 py-1 text-xs font-extrabold text-blue-900">
                            ${escapeHtml(item.code || '-')}
                        </span>
                    </td>

                    <td class="px-5 py-4">
                        <p class="font-extrabold text-slate-900">
                            ${escapeHtml(item.name || '-')}
                        </p>
                    </td>

                    <td class="px-5 py-4 font-semibold text-slate-700">
                        ${formatWeight(item.weight)}
                    </td>

                    <td class="px-5 py-4">
                        ${renderTypeBadge(item.type)}
                    </td>

                    <td class="px-5 py-4 font-semibold text-slate-700">
                        ${escapeHtml(item.min_score ?? '0')} sampai ${escapeHtml(item.max_score ?? '100')}
                    </td>

                    <td class="px-5 py-4">
                        ${renderActiveBadge(item.is_active)}
                    </td>
                </tr>
            `;
        }).join('');
    }

    function showLoading() {
        document.getElementById('loadingState')?.classList.remove('hidden');
        document.getElementById('detailContent')?.classList.add('hidden');
    }

    function hideLoading() {
        document.getElementById('loadingState')?.classList.add('hidden');
        document.getElementById('detailContent')?.classList.remove('hidden');
    }

    function renderErrorState(message) {
        const loadingState = document.getElementById('loadingState');

        if (!loadingState) {
            return;
        }

        loadingState.innerHTML = `
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-red-600">
                !
            </div>

            <p class="mt-4 text-sm font-semibold text-red-600">
                ${escapeHtml(message)}
            </p>

            <a
                href="{{ route('admin.juries.index') }}"
                class="mt-5 inline-flex h-11 items-center justify-center rounded-lg border border-slate-300 bg-white px-4 text-sm font-bold text-slate-700 hover:bg-slate-50"
            >
                Kembali ke Akun Juri
            </a>
        `;

        loadingState.className = 'rounded-2xl border border-red-200 bg-white p-8 text-center shadow-sm';
    }

    function renderStatusBadge(isActive) {
        return isActiveValue(isActive)
            ? `<span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-extrabold text-green-700">Aktif</span>`
            : `<span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-extrabold text-red-700">Nonaktif</span>`;
    }

    function renderAssignmentBadge(count) {
        if (Number(count || 0) > 0) {
            return `
                <span class="inline-flex rounded-full border border-green-200 bg-green-100 px-3 py-1 text-xs font-extrabold text-green-700">
                    Sudah Ditugaskan
                </span>
            `;
        }

        return `
            <span class="inline-flex rounded-full border border-amber-200 bg-amber-100 px-3 py-1 text-xs font-extrabold text-amber-700">
                Belum Ada Kriteria
            </span>
        `;
    }

    function renderActiveBadge(isActive) {
        return isActiveValue(isActive)
            ? `<span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-extrabold text-green-700">Aktif</span>`
            : `<span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-extrabold text-slate-600">Nonaktif</span>`;
    }

    function renderTypeBadge(type) {
        const value = String(type || '').toLowerCase();

        if (value === 'benefit') {
            return `<span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-extrabold text-blue-700">Benefit</span>`;
        }

        if (value === 'cost') {
            return `<span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-extrabold text-amber-700">Cost</span>`;
        }

        return `<span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-extrabold text-slate-600">-</span>`;
    }

    function formatWeight(value) {
        const number = Number(value || 0);

        if (number <= 1) {
            return `${formatNumber(number * 100)}%`;
        }

        return `${formatNumber(number)}%`;
    }

    function formatRole(role) {
        const value = String(role || 'juri').toLowerCase();

        if (value === 'juri') {
            return 'Juri';
        }

        if (value === 'admin') {
            return 'Admin';
        }

        return value || '-';
    }

    function isActiveValue(value) {
        return value === true || value === 1 || value === '1';
    }

    function formatNumber(value) {
        return new Intl.NumberFormat('id-ID', {
            maximumFractionDigits: 2,
        }).format(Number(value || 0));
    }

    function formatDateTime(value) {
        if (!value) {
            return '-';
        }

        const date = new Date(value);

        if (Number.isNaN(date.getTime())) {
            return value;
        }

        return date.toLocaleString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        });
    }

    function getInitials(name) {
        return String(name || 'J')
            .trim()
            .split(/\s+/)
            .slice(0, 2)
            .map(function (word) {
                return word.charAt(0).toUpperCase();
            })
            .join('');
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

    function setText(id, value) {
        const element = document.getElementById(id);

        if (element) {
            element.textContent = value ?? '-';
        }
    }

    function setHtml(id, value) {
        const element = document.getElementById(id);

        if (element) {
            element.innerHTML = value;
        }
    }

    function escapeHtml(value) {
        return String(value ?? '-')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }
</script>
@endpush