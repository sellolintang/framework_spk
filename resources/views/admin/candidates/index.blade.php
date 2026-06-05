@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Data Pendaftar</h1>
        <p class="mt-2 text-sm text-gray-500">
            Kelola dan verifikasi seluruh calon pendaftar seleksi Duta PNJ 2024.
        </p>
    </div>

    {{-- Filter + Total Card --}}
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm lg:col-span-3">
            <div class="grid grid-cols-1 gap-3 md:grid-cols-12">
                <div class="md:col-span-7">
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                            </svg>
                        </span>
                        <input
                            id="keywordInput"
                            type="text"
                            placeholder="Cari Nama atau NIM..."
                            class="w-full rounded-lg border border-gray-300 bg-gray-50 py-3 pl-10 pr-4 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100"
                        >
                    </div>
                </div>

                <div class="md:col-span-3">
                    <select
                        id="statusInput"
                        class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100"
                    >
                        <option value="">Semua Status</option>
                        <option value="pending">Menunggu Verifikasi</option>
                        <option value="valid">Diterima</option>
                        <option value="invalid">Ditolak</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <button
                        id="filterBtn"
                        type="button"
                        class="flex w-full items-center justify-center gap-2 rounded-lg bg-blue-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-800"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 4a1 1 0 011-1h16a1 1 0 01.8 1.6l-6.4 8.53V19a1 1 0 01-.45.83l-4 2.67A1 1 0 018 21.67v-8.54L1.6 4.6A1 1 0 013 4z" />
                        </svg>
                        Filter
                    </button>
                </div>
            </div>
        </div>

        <div class="rounded-xl bg-blue-800 p-5 text-white shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-blue-100">Total Pendaftar</p>
                    <h2 id="totalCandidates" class="mt-1 text-3xl font-bold">0</h2>
                    <span class="mt-3 inline-flex rounded bg-blue-700 px-2 py-1 text-xs font-semibold">
                        +12 Hari Ini
                    </span>
                </div>

                <div class="rounded-full bg-white/10 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-100" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-5.13a4 4 0 11-8 0 4 4 0 018 0zm6 2v6m3-3h-6" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-4 text-left font-semibold text-gray-600">No</th>
                        <th class="px-5 py-4 text-left font-semibold text-gray-600">Nama & NIM</th>
                        <th class="px-5 py-4 text-left font-semibold text-gray-600">Program Studi</th>
                        <th class="px-5 py-4 text-left font-semibold text-gray-600">Status</th>
                        <th class="px-5 py-4 text-left font-semibold text-gray-600">Tanggal Daftar</th>
                        <th class="px-5 py-4 text-center font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>

                <tbody id="candidateTableBody" class="divide-y divide-gray-100 bg-white">
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-gray-500">
                            Memuat data pendaftar...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Footer pagination --}}
        <div class="flex flex-col gap-3 border-t border-gray-200 bg-gray-50 px-5 py-4 md:flex-row md:items-center md:justify-between">
            <p id="paginationInfo" class="text-sm text-gray-600">
                Menampilkan 0 data
            </p>

            <div id="paginationWrapper" class="flex items-center gap-2"></div>
        </div>
    </div>
</div>

{{-- Modal Detail Pendaftar --}}
<div id="detailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm">
    <div class="w-full max-w-3xl overflow-hidden rounded-2xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50 px-6 py-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Detail Pendaftar</h2>
                <p class="text-sm text-gray-500">Informasi lengkap calon pendaftar Duta PNJ.</p>
            </div>

            <button type="button" onclick="closeDetailModal()"
                    class="rounded-full p-2 text-gray-400 hover:bg-gray-200 hover:text-gray-700">
                ✕
            </button>
        </div>

        <div id="detailContent" class="max-h-[70vh] overflow-y-auto px-6 py-5">
            <div class="py-10 text-center text-sm text-gray-500">Memuat detail pendaftar...</div>
        </div>

        <div class="flex justify-end border-t border-gray-100 bg-gray-50 px-6 py-4">
            <button type="button" onclick="closeDetailModal()"
                    class="rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- Modal Terima Calon --}}
<div id="acceptModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm">
    <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl">
        <div class="bg-green-50 px-6 py-5">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-green-100 text-green-700">
                    ✓
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Terima Calon?</h2>
                    <p class="text-sm text-gray-500">Status calon akan berubah menjadi diterima.</p>
                </div>
            </div>
        </div>

        <div class="px-6 py-5">
            <p class="text-sm text-gray-600">
                Anda akan menerima pendaftar berikut:
            </p>

            <div class="mt-4 rounded-xl border border-gray-200 bg-gray-50 p-4">
                <p id="acceptCandidateName" class="font-bold text-gray-900">-</p>
                <p id="acceptCandidateMeta" class="mt-1 text-sm text-gray-500">-</p>
            </div>

            <p class="mt-4 text-sm text-gray-500">
                Setelah diterima, sistem akan mengirim email pemberitahuan ke calon melalui Mailpit.
            </p>
        </div>

        <div class="flex justify-end gap-3 border-t border-gray-100 bg-gray-50 px-6 py-4">
            <button type="button" onclick="closeAcceptModal()"
                    class="rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 ring-1 ring-gray-200 hover:bg-gray-100">
                Batal
            </button>

            <button id="confirmAcceptBtn" type="button" onclick="submitAcceptCandidate()"
                    class="rounded-xl bg-green-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-700">
                Ya, Terima
            </button>
        </div>
    </div>
</div>

{{-- Modal Tolak Calon --}}
<div id="rejectModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm">
    <div class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-2xl">
        <div class="bg-red-50 px-6 py-5">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-red-100 text-red-700">
                    !
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Tolak Calon?</h2>
                    <p class="text-sm text-gray-500">Berikan alasan penolakan dengan jelas.</p>
                </div>
            </div>
        </div>

        <div class="px-6 py-5">
            <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                <p id="rejectCandidateName" class="font-bold text-gray-900">-</p>
                <p id="rejectCandidateMeta" class="mt-1 text-sm text-gray-500">-</p>
            </div>

            <label for="rejectReasonInput" class="mt-5 block text-sm font-semibold text-gray-700">
                Alasan Penolakan
            </label>

            <textarea id="rejectReasonInput"
                      rows="4"
                      placeholder="Contoh: Berkas CV belum sesuai format atau data pendaftaran belum lengkap."
                      class="mt-2 w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-100"></textarea>

            <p id="rejectReasonError" class="mt-2 hidden text-sm text-red-600">
                Alasan penolakan wajib diisi.
            </p>

            <p class="mt-4 text-sm text-gray-500">
                Setelah ditolak, sistem akan mengirim email pemberitahuan beserta alasan penolakan.
            </p>
        </div>

        <div class="flex justify-end gap-3 border-t border-gray-100 bg-gray-50 px-6 py-4">
            <button type="button" onclick="closeRejectModal()"
                    class="rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 ring-1 ring-gray-200 hover:bg-gray-100">
                Batal
            </button>

            <button id="confirmRejectBtn" type="button" onclick="submitRejectCandidate()"
                    class="rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-700">
                Ya, Tolak
            </button>
        </div>
    </div>
</div>

{{-- Toast --}}
<div id="toast"
     class="fixed right-5 top-5 z-[60] hidden max-w-sm rounded-xl border border-gray-200 bg-white px-5 py-4 text-sm shadow-xl">
    <p id="toastTitle" class="font-bold text-gray-900"></p>
    <p id="toastMessage" class="mt-1 text-gray-500"></p>
</div>
@endsection

@push('scripts')
<script>
    let currentPage = 1;
    let lastPage = 1;
    let perPage = 10;
    let selectedCandidate = null;

    const token = localStorage.getItem('duta_kampus_token')
        || localStorage.getItem('auth_token')
        || localStorage.getItem('access_token')
        || localStorage.getItem('token');

    document.addEventListener('DOMContentLoaded', function () {
        if (!token) {
            alert('Token login tidak ditemukan. Silakan login ulang.');
            window.location.href = '/login';
            return;
        }

        loadCandidates();

        document.getElementById('filterBtn').addEventListener('click', function () {
            currentPage = 1;
            loadCandidates();
        });

        document.getElementById('keywordInput').addEventListener('keyup', function (event) {
            if (event.key === 'Enter') {
                currentPage = 1;
                loadCandidates();
            }
        });

        document.getElementById('statusInput').addEventListener('change', function () {
            currentPage = 1;
            loadCandidates();
        });
    });

    async function apiFetch(url, options = {}) {
        const response = await fetch(url, {
            ...options,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                ...(options.headers || {}),
            },
        });

        const result = await response.json().catch(() => ({}));

        if (response.status === 401) {
            alert('Sesi login habis. Silakan login ulang.');
            localStorage.removeItem('duta_kampus_token');
            localStorage.removeItem('duta_kampus_user');
            localStorage.removeItem('auth_token');
            localStorage.removeItem('access_token');
            localStorage.removeItem('token');
            window.location.href = '/login';
            return;
        }

        if (!response.ok) {
            throw new Error(result.message || 'Terjadi kesalahan.');
        }

        return result;
    }

    async function loadCandidates() {
        const tbody = document.getElementById('candidateTableBody');
        const keyword = document.getElementById('keywordInput').value;
        const status = document.getElementById('statusInput').value;

        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="px-5 py-8 text-center text-gray-500">
                    Memuat data pendaftar...
                </td>
            </tr>
        `;

        try {
            const params = new URLSearchParams({
                page: currentPage,
                per_page: perPage,
            });

            if (keyword) params.append('keyword', keyword);
            if (status) params.append('status', status);

            const result = await apiFetch(`/api/candidates?${params.toString()}`);
            const paginator = result.data;
            const candidates = paginator.data || [];

            currentPage = paginator.current_page || 1;
            lastPage = paginator.last_page || 1;

            document.getElementById('totalCandidates').innerText = formatNumber(paginator.total || 0);
            renderTable(candidates, paginator);
            renderPagination(paginator);
        } catch (error) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-5 py-8 text-center text-red-500">
                        ${error.message}
                    </td>
                </tr>
            `;
        }
    }

    function renderTable(candidates, paginator) {
        const tbody = document.getElementById('candidateTableBody');

        if (!candidates.length) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-5 py-8 text-center text-gray-500">
                        Tidak ada data pendaftar.
                    </td>
                </tr>
            `;

            document.getElementById('paginationInfo').innerText = 'Menampilkan 0 data';
            return;
        }

        tbody.innerHTML = candidates.map((candidate, index) => {
            const number = (paginator.from || 1) + index;
            const statusBadge = getStatusBadge(candidate.status);
            const registeredDate = formatDate(candidate.created_at);

            return `
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-4 text-gray-700">${number}</td>

                    <td class="px-5 py-4">
                        <div class="font-semibold text-gray-900">${escapeHtml(candidate.full_name || '-')}</div>
                        <div class="text-xs text-gray-500">${escapeHtml(candidate.student_number || '-')}</div>
                    </td>

                    <td class="px-5 py-4">
                        <div class="font-semibold text-gray-900">${escapeHtml(candidate.study_program || '-')}</div>
                        <div class="text-xs text-gray-500">${escapeHtml(candidate.faculty || '-')}</div>
                    </td>

                    <td class="px-5 py-4">
                        ${statusBadge}
                    </td>

                    <td class="px-5 py-4 text-gray-700">
                        ${registeredDate}
                    </td>

                    <td class="px-5 py-4">
                        <div class="flex items-center justify-center gap-3">
                            <button
                                type="button"
                                onclick="showCandidateDetail(${candidate.id})"
                                title="Lihat detail"
                                class="rounded-lg p-2 text-blue-800 transition hover:bg-blue-50 hover:text-blue-600"
                            >
                                ${eyeIcon()}
                            </button>

                            <button
                                type="button"
                                onclick='openAcceptModal(${JSON.stringify(candidate)})'
                                title="Terima"
                                class="${candidate.status === 'pending' ? 'rounded-lg p-2 text-green-600 transition hover:bg-green-50 hover:text-green-500' : 'cursor-not-allowed rounded-lg p-2 text-green-200'}"
                                ${candidate.status !== 'pending' ? 'disabled' : ''}
                            >
                                ${checkIcon()}
                            </button>

                            <button
                                type="button"
                                onclick='openRejectModal(${JSON.stringify(candidate)})'
                                title="Tolak"
                                class="${candidate.status === 'pending' ? 'rounded-lg p-2 text-red-600 transition hover:bg-red-50 hover:text-red-500' : 'cursor-not-allowed rounded-lg p-2 text-red-200'}"
                                ${candidate.status !== 'pending' ? 'disabled' : ''}
                            >
                                ${xIcon()}
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');

        document.getElementById('paginationInfo').innerText =
            `Menampilkan ${paginator.from || 0} - ${paginator.to || 0} dari ${formatNumber(paginator.total || 0)} data`;
    }

    function renderPagination(paginator) {
        const wrapper = document.getElementById('paginationWrapper');
        const pages = [];

        const start = Math.max(1, currentPage - 2);
        const end = Math.min(lastPage, currentPage + 2);

        pages.push(`
            <button
                type="button"
                onclick="changePage(${currentPage - 1})"
                class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-40"
                ${currentPage <= 1 ? 'disabled' : ''}
            >
                ‹
            </button>
        `);

        if (start > 1) {
            pages.push(pageButton(1));
            if (start > 2) {
                pages.push(`<span class="px-2 text-gray-400">...</span>`);
            }
        }

        for (let page = start; page <= end; page++) {
            pages.push(pageButton(page));
        }

        if (end < lastPage) {
            if (end < lastPage - 1) {
                pages.push(`<span class="px-2 text-gray-400">...</span>`);
            }
            pages.push(pageButton(lastPage));
        }

        pages.push(`
            <button
                type="button"
                onclick="changePage(${currentPage + 1})"
                class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-40"
                ${currentPage >= lastPage ? 'disabled' : ''}
            >
                ›
            </button>
        `);

        wrapper.innerHTML = pages.join('');
    }

    function pageButton(page) {
        const activeClass = page === currentPage
            ? 'bg-blue-900 text-white border-blue-900'
            : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100';

        return `
            <button
                type="button"
                onclick="changePage(${page})"
                class="rounded-lg border px-3 py-2 text-sm ${activeClass}"
            >
                ${page}
            </button>
        `;
    }

    function changePage(page) {
        if (page < 1 || page > lastPage || page === currentPage) return;
        currentPage = page;
        loadCandidates();
    }

    function openAcceptModal(candidate) {
    selectedCandidate = candidate;

    document.getElementById('acceptCandidateName').innerText = candidate.full_name || '-';
    document.getElementById('acceptCandidateMeta').innerText =
        `${candidate.student_number || '-'} • ${candidate.study_program || '-'}`;

    const modal = document.getElementById('acceptModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeAcceptModal() {
    selectedCandidate = null;

    const modal = document.getElementById('acceptModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

async function submitAcceptCandidate() {
    if (!selectedCandidate) return;

    const button = document.getElementById('confirmAcceptBtn');
    button.disabled = true;
    button.innerText = 'Memproses...';

    try {
        const result = await apiFetch(`/api/candidates/${selectedCandidate.id}/validate`, {
            method: 'PATCH',
        });

        closeAcceptModal();

        const emailInfo = result.data?.email_sent
            ? 'Email penerimaan berhasil dikirim.'
            : 'Status berhasil diubah, tetapi email gagal dikirim. Cek log Laravel.';

        showToast('Calon diterima', emailInfo);
        loadCandidates();
    } catch (error) {
        showToast('Gagal menerima calon', error.message, 'error');
    } finally {
        button.disabled = false;
        button.innerText = 'Ya, Terima';
    }
}

function openRejectModal(candidate) {
    selectedCandidate = candidate;

    document.getElementById('rejectCandidateName').innerText = candidate.full_name || '-';
    document.getElementById('rejectCandidateMeta').innerText =
        `${candidate.student_number || '-'} • ${candidate.study_program || '-'}`;

    document.getElementById('rejectReasonInput').value = '';
    document.getElementById('rejectReasonError').classList.add('hidden');

    const modal = document.getElementById('rejectModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeRejectModal() {
    selectedCandidate = null;

    const modal = document.getElementById('rejectModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

async function submitRejectCandidate() {
    if (!selectedCandidate) return;

    const reasonInput = document.getElementById('rejectReasonInput');
    const reasonError = document.getElementById('rejectReasonError');
    const reason = reasonInput.value.trim();

    if (!reason) {
        reasonError.classList.remove('hidden');
        reasonInput.focus();
        return;
    }

    reasonError.classList.add('hidden');

    const button = document.getElementById('confirmRejectBtn');
    button.disabled = true;
    button.innerText = 'Memproses...';

    try {
        const result = await apiFetch(`/api/candidates/${selectedCandidate.id}/reject`, {
            method: 'PATCH',
            body: JSON.stringify({
                rejection_reason: reason,
            }),
        });

        closeRejectModal();

        const emailInfo = result.data?.email_sent
            ? 'Email penolakan berhasil dikirim.'
            : 'Status berhasil diubah, tetapi email gagal dikirim. Cek log Laravel.';

        showToast('Calon ditolak', emailInfo);
        loadCandidates();
    } catch (error) {
        showToast('Gagal menolak calon', error.message, 'error');
    } finally {
        button.disabled = false;
        button.innerText = 'Ya, Tolak';
    }
}

    async function showCandidateDetail(id) {
        const modal = document.getElementById('detailModal');
        const content = document.getElementById('detailContent');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        content.innerHTML = `
            <div class="py-10 text-center text-sm text-gray-500">
                Memuat detail pendaftar...
            </div>
        `;

        try {
            const result = await apiFetch(`/api/candidates/${id}`);
            const candidate = result.data.candidate;

            const photoUrl = candidate.photo_file ? `/storage/${candidate.photo_file}` : null;
            const cvUrl = candidate.cv_file ? `/storage/${candidate.cv_file}` : null;

            content.innerHTML = `
                <div class="flex flex-col gap-5 md:flex-row">
                    <div class="md:w-48">
                        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-gray-100">
                            ${
                                photoUrl
                                    ? `<img src="${photoUrl}" alt="Foto pendaftar" class="h-52 w-full object-cover">`
                                    : `<div class="flex h-52 items-center justify-center text-sm text-gray-400">Tidak ada foto</div>`
                            }
                        </div>

                        <div class="mt-3">
                            ${getStatusBadge(candidate.status)}
                        </div>
                    </div>

                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-900">${escapeHtml(candidate.full_name || '-')}</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            ${escapeHtml(candidate.registration_number || '-')} • ${escapeHtml(candidate.student_number || '-')}
                        </p>

                        <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                            ${detailItem('Email', candidate.email)}
                            ${detailItem('No. HP', candidate.phone)}
                            ${detailItem('Fakultas', candidate.faculty)}
                            ${detailItem('Program Studi', candidate.study_program)}
                            ${detailItem('Semester', candidate.semester)}
                            ${detailItem('Tanggal Daftar', formatDate(candidate.created_at))}
                            ${detailItem('Divalidasi Oleh', candidate.validator?.name ?? '-')}
                            ${detailItem('Tanggal Validasi', formatDate(candidate.validated_at))}
                        </div>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                        <p class="font-semibold text-gray-900">Visi</p>
                        <p class="mt-2 whitespace-pre-line text-sm leading-6 text-gray-600">${escapeHtml(candidate.vision || '-')}</p>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                        <p class="font-semibold text-gray-900">Misi</p>
                        <p class="mt-2 whitespace-pre-line text-sm leading-6 text-gray-600">${escapeHtml(candidate.mission || '-')}</p>
                    </div>
                </div>

                <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-4">
                    <p class="font-semibold text-gray-900">Berkas Pendaftaran</p>

                    <div class="mt-3 flex flex-wrap gap-3">
                        ${
                            photoUrl
                                ? `<a href="${photoUrl}" target="_blank" class="rounded-xl bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100">Lihat Foto</a>`
                                : `<span class="rounded-xl bg-gray-100 px-4 py-2 text-sm text-gray-500">Foto tidak tersedia</span>`
                        }

                        ${
                            cvUrl
                                ? `<a href="${cvUrl}" target="_blank" class="rounded-xl bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100">Lihat CV</a>`
                                : `<span class="rounded-xl bg-gray-100 px-4 py-2 text-sm text-gray-500">CV tidak tersedia</span>`
                        }
                    </div>
                </div>

                ${
                    candidate.rejection_reason
                        ? `
                            <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-4">
                                <p class="font-semibold text-red-700">Alasan Penolakan</p>
                                <p class="mt-2 text-sm leading-6 text-red-700">${escapeHtml(candidate.rejection_reason)}</p>
                            </div>
                        `
                        : ''
                }
            `;
        } catch (error) {
            content.innerHTML = `
                <div class="rounded-xl bg-red-50 p-4 text-sm text-red-700">
                    ${error.message}
                </div>
            `;
        }
    }

    function closeDetailModal() {
        const modal = document.getElementById('detailModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function showToast(title, message, type = 'success') {
        const toast = document.getElementById('toast');
        const toastTitle = document.getElementById('toastTitle');
        const toastMessage = document.getElementById('toastMessage');

        toastTitle.innerText = title;
        toastMessage.innerText = message;

        toast.className =
            'fixed right-5 top-5 z-[60] max-w-sm rounded-xl border bg-white px-5 py-4 text-sm shadow-xl';

        if (type === 'error') {
            toast.classList.add('border-red-200');
            toastTitle.className = 'font-bold text-red-700';
        } else {
            toast.classList.add('border-green-200');
            toastTitle.className = 'font-bold text-green-700';
        }

        toast.classList.remove('hidden');

        setTimeout(() => {
            toast.classList.add('hidden');
        }, 3500);
    }

    function detailItem(label, value) {
        return `
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">${label}</p>
                <p class="mt-1 font-medium text-gray-900">${escapeHtml(value ?? '-')}</p>
            </div>
        `;
    }

    function getStatusBadge(status) {
        const config = {
            pending: {
                text: 'Menunggu Verifikasi',
                className: 'bg-blue-100 text-blue-700',
            },
            valid: {
                text: 'Diterima',
                className: 'bg-green-100 text-green-700',
            },
            invalid: {
                text: 'Ditolak',
                className: 'bg-red-100 text-red-700',
            },
        };

        const item = config[status] || {
            text: status || '-',
            className: 'bg-gray-100 text-gray-700',
        };

        return `<span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold ${item.className}">${item.text}</span>`;
    }

    function statusText(status) {
        if (status === 'pending') return 'Menunggu Verifikasi';
        if (status === 'valid') return 'Diterima';
        if (status === 'invalid') return 'Ditolak';
        return status || '-';
    }

    function formatDate(dateString) {
        if (!dateString) return '-';

        return new Intl.DateTimeFormat('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
        }).format(new Date(dateString));
    }

    function formatNumber(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function eyeIcon() {
        return `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M2.46 12C3.73 7.94 7.52 5 12 5s8.27 2.94 9.54 7c-1.27 4.06-5.06 7-9.54 7S3.73 16.06 2.46 12z" />
            </svg>
        `;
    }

    function checkIcon() {
        return `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        `;
    }

    function xIcon() {
        return `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        `;
    }
</script>
@endpush
