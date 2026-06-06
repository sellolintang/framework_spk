@extends('layouts.admin')

@section('content')
    <section class="mb-7">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-[34px] font-extrabold leading-none tracking-tight text-[#00288E]">
                    Jadwal Wawancara
                </h1>

                <p class="mt-2 text-sm font-medium text-slate-500">
                    Kelola daftar jadwal wawancara calon Duta PNJ.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <button
                    type="button"
                    onclick="openResetModal()"
                    class="rounded-md border border-red-200 bg-white px-4 py-2 text-sm font-bold text-red-600 hover:bg-red-50"
                >
                    Reset Jadwal
                </button>

                <x-button href="{{ route('admin.interviews.create') }}">
                    Generate Jadwal
                </x-button>
            </div>
        </div>
    </section>

    <div id="pageAlert" class="mb-5 hidden rounded-md border px-4 py-3 text-sm"></div>

    <section class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <x-card>
            <p class="text-sm font-semibold text-slate-700">Total Jadwal</p>
            <h2 id="totalInterviews" class="mt-2 text-3xl font-extrabold text-slate-900">0</h2>
        </x-card>

        <x-card>
            <p class="text-sm font-semibold text-slate-700">Terjadwal</p>
            <h2 id="scheduledInterviews" class="mt-2 text-3xl font-extrabold text-slate-900">0</h2>
        </x-card>

        <x-card>
            <p class="text-sm font-semibold text-slate-700">Selesai</p>
            <h2 id="completedInterviews" class="mt-2 text-3xl font-extrabold text-slate-900">0</h2>
        </x-card>

        <x-card>
            <p class="text-sm font-semibold text-slate-700">Batal / Tidak Hadir</p>
            <h2 id="problemInterviews" class="mt-2 text-3xl font-extrabold text-slate-900">0</h2>
        </x-card>
    </section>

    <section class="mt-7">
        <x-card padding="p-0">
            <div class="border-b border-slate-200 px-5 py-4">
                <div class="grid grid-cols-1 gap-3 md:grid-cols-[160px_1fr_180px_140px]">
                    <x-period-select width="w-40" height="h-11" />

                    <div>
                        <label for="searchInput" class="mb-1 block text-xs font-bold text-slate-600">
                            Pencarian
                        </label>
                        <input
                            id="searchInput"
                            type="text"
                            placeholder="Cari nama, NIM, nomor pendaftaran, atau program studi..."
                            class="h-11 w-full rounded-md border border-slate-300 bg-white px-4 text-sm outline-none focus:border-[#00288E] focus:ring-2 focus:ring-blue-100"
                        >
                    </div>

                    <div>
                        <label for="statusFilter" class="mb-1 block text-xs font-bold text-slate-600">
                            Status
                        </label>
                        <select
                            id="statusFilter"
                            class="h-11 w-full rounded-md border border-slate-300 bg-white px-4 text-sm outline-none focus:border-[#00288E] focus:ring-2 focus:ring-blue-100"
                        >
                            <option value="">Semua Status</option>
                            <option value="scheduled">Terjadwal</option>
                            <option value="completed">Selesai</option>
                            <option value="absent">Tidak Hadir</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button
                            type="button"
                            onclick="loadInterviews()"
                            class="h-11 w-full rounded-md border border-slate-300 bg-white px-4 text-sm font-bold text-slate-700 hover:bg-slate-50"
                        >
                            Muat Ulang
                        </button>
                    </div>
                </div>
            </div>

            <x-table
                :headers="['No', 'Calon', 'NIM', 'Jadwal', 'Lokasi', 'Status', 'Aksi']"
                tbody-id="interviewsTableBody"
                class="rounded-none border-0 shadow-none"
            >
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                        Memuat data jadwal wawancara...
                    </td>
                </tr>
            </x-table>

            <div class="flex items-center justify-between border-t border-slate-200 bg-slate-50 px-6 py-4 text-sm text-slate-600">
                <span id="tableInfo">Menampilkan data jadwal wawancara</span>

                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        onclick="changePage(-1)"
                        class="flex h-8 w-8 items-center justify-center rounded-md border border-slate-300 bg-white text-slate-600 hover:bg-slate-50"
                    >
                        ‹
                    </button>

                    <span id="pageInfo" class="rounded-md bg-[#00288E] px-3 py-1.5 text-sm font-bold text-white">
                        1
                    </span>

                    <button
                        type="button"
                        onclick="changePage(1)"
                        class="flex h-8 w-8 items-center justify-center rounded-md border border-slate-300 bg-white text-slate-600 hover:bg-slate-50"
                    >
                        ›
                    </button>
                </div>
            </div>
        </x-card>
    </section>

    {{-- Modal Hapus --}}
    <div id="deleteModal" class="fixed inset-0 z-[90] hidden">
        <div class="absolute inset-0 bg-slate-900/50" onclick="closeDeleteModal()"></div>

        <div class="relative mx-auto mt-28 w-[92%] max-w-md">
            <div class="overflow-hidden rounded-xl bg-white shadow-xl">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-lg font-extrabold text-slate-900">
                        Hapus Jadwal Wawancara
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Data jadwal yang dihapus tidak dapat dikembalikan.
                    </p>
                </div>

                <div class="px-5 py-5">
                    <input type="hidden" id="deleteInterviewId">

                    <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                        Apakah kamu yakin ingin menghapus jadwal wawancara ini?
                    </div>
                </div>

                <div class="flex justify-end gap-2 border-t border-slate-200 bg-slate-50 px-5 py-4">
                    <x-button type="button" variant="secondary" onclick="closeDeleteModal()">
                        Batal
                    </x-button>

                    <x-button type="button" variant="danger" onclick="confirmDeleteInterview()">
                        Hapus
                    </x-button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Reset Jadwal --}}
    <div id="resetModal" class="fixed inset-0 z-[90] hidden">
        <div class="absolute inset-0 bg-slate-900/50" onclick="closeResetModal()"></div>

        <div class="relative mx-auto mt-28 w-[92%] max-w-md">
            <div class="overflow-hidden rounded-xl bg-white shadow-xl">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-lg font-extrabold text-slate-900">
                        Reset Jadwal Wawancara
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Semua jadwal wawancara pada periode yang dipilih akan dihapus.
                    </p>
                </div>

                <div class="px-5 py-5">
                    <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                        Setelah jadwal direset, status calon akan dikembalikan agar jadwal bisa digenerate ulang.
                    </div>

                    <p class="mt-4 text-sm text-slate-600">
                        Periode ID yang akan direset:
                        <span id="resetPeriodText" class="font-extrabold text-slate-900">-</span>
                    </p>
                </div>

                <div class="flex justify-end gap-2 border-t border-slate-200 bg-slate-50 px-5 py-4">
                    <x-button type="button" variant="secondary" onclick="closeResetModal()">
                        Batal
                    </x-button>

                    <button
                        type="button"
                        onclick="confirmResetSchedule()"
                        class="rounded-md bg-red-600 px-4 py-2 text-sm font-bold text-white hover:bg-red-700"
                    >
                        Reset Jadwal
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let interviewsData = [];
        let pagination = {
            current_page: 1,
            last_page: 1,
            total: 0,
            per_page: 10,
        };

        document.addEventListener('DOMContentLoaded', async function () {
            const params = new URLSearchParams(window.location.search);
            const periodId = params.get('period_id') || '1';

            await loadPeriodOptions('periodIdInput', periodId);

            loadInterviews();

            document.getElementById('searchInput')?.addEventListener('input', debounce(function () {
                pagination.current_page = 1;
                loadInterviews();
            }, 400));

            document.getElementById('statusFilter')?.addEventListener('change', function () {
                pagination.current_page = 1;
                loadInterviews();
            });

            document.getElementById('periodIdInput')?.addEventListener('change', function () {
                pagination.current_page = 1;
                loadInterviews();
            });
        });

        async function loadInterviews() {
            const tableBody = document.getElementById('interviewsTableBody');

            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                        Memuat data jadwal wawancara...
                    </td>
                </tr>
            `;

            try {
                const params = new URLSearchParams({
                    period_id: getPeriodId(),
                    page: pagination.current_page,
                    per_page: pagination.per_page,
                });

                const search = document.getElementById('searchInput')?.value || '';
                const status = document.getElementById('statusFilter')?.value || '';

                if (search) params.append('search', search);
                if (status) params.append('status', status);

                const result = await DutaAdmin.request(`/interviews?${params.toString()}`);
                const payload = result?.data || {};

                interviewsData = payload.data || [];

                pagination.current_page = payload.current_page || 1;
                pagination.last_page = payload.last_page || 1;
                pagination.total = payload.total || interviewsData.length;
                pagination.per_page = payload.per_page || 10;

                renderStats();
                renderTable();
                renderPaginationInfo();
            } catch (error) {
                console.error(error);

                tableBody.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-red-600">
                            ${escapeHtml(getErrorMessage(error))}
                        </td>
                    </tr>
                `;
            }
        }

        function renderStats() {
            const scheduled = interviewsData.filter(item => item.status === 'scheduled').length;
            const completed = interviewsData.filter(item => item.status === 'completed').length;
            const problem = interviewsData.filter(item => ['absent', 'cancelled'].includes(item.status)).length;

            setText('totalInterviews', pagination.total);
            setText('scheduledInterviews', scheduled);
            setText('completedInterviews', completed);
            setText('problemInterviews', problem);
        }

        function renderTable() {
            const tableBody = document.getElementById('interviewsTableBody');

            if (!interviewsData.length) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                            Belum ada data jadwal wawancara.
                        </td>
                    </tr>
                `;
                return;
            }

            tableBody.innerHTML = interviewsData.map((item, index) => {
                const rowNumber = ((pagination.current_page - 1) * pagination.per_page) + index + 1;

                return `
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-slate-600">${rowNumber}</td>

                        <td class="px-6 py-4">
                            <p class="font-extrabold text-slate-900">${escapeHtml(item.full_name || '-')}</p>
                            <p class="mt-1 text-xs text-slate-500">${escapeHtml(item.registration_number || '-')}</p>
                        </td>

                        <td class="px-6 py-4 text-slate-600">${escapeHtml(item.student_number || '-')}</td>

                        <td class="px-6 py-4 font-semibold text-slate-700">${formatDateTime(item.scheduled_at)}</td>

                        <td class="px-6 py-4 text-slate-600">${escapeHtml(item.location || '-')}</td>

                        <td class="px-6 py-4">${renderStatusBadge(item.status)}</td>

                        <td class="px-6 py-4">
                            <div class="flex flex-wrap items-center gap-2">
                                <a
                                    href="/admin/interviews/${item.id}"
                                    class="rounded-md border border-slate-300 px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-50"
                                >
                                    Detail
                                </a>

                                <a
                                    href="/admin/interviews/${item.id}/edit"
                                    class="rounded-md border border-slate-300 px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-50"
                                >
                                    Edit
                                </a>

                                <button
                                    type="button"
                                    onclick="openDeleteModal(${item.id})"
                                    class="rounded-md border border-red-200 px-3 py-1.5 text-xs font-bold text-red-600 hover:bg-red-50"
                                >
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function renderPaginationInfo() {
            const start = pagination.total === 0
                ? 0
                : ((pagination.current_page - 1) * pagination.per_page) + 1;

            const end = Math.min(pagination.current_page * pagination.per_page, pagination.total);

            setText('tableInfo', `Menampilkan ${start} sampai ${end} dari ${pagination.total} jadwal`);
            setText('pageInfo', pagination.current_page);
        }

        function changePage(direction) {
            const nextPage = pagination.current_page + direction;

            if (nextPage < 1 || nextPage > pagination.last_page) {
                return;
            }

            pagination.current_page = nextPage;
            loadInterviews();
        }

        function openResetModal() {
            const periodId = getPeriodId();

            document.getElementById('resetPeriodText').textContent = periodId;
            document.getElementById('resetModal').classList.remove('hidden');
        }

        function closeResetModal() {
            document.getElementById('resetModal').classList.add('hidden');
        }

        async function confirmResetSchedule() {
            const periodId = getPeriodId();

            try {
                const result = await DutaAdmin.request('/interviews/reset', {
                    method: 'POST',
                    body: JSON.stringify({
                        period_id: Number(periodId),
                    }),
                });

                closeResetModal();

                const deletedCount = result?.data?.deleted_count || 0;
                const restoredCount = result?.data?.restored_candidates_count || 0;

                showAlert(
                    'success',
                    `${result.message || 'Jadwal berhasil direset.'} Terhapus: ${deletedCount}, calon dikembalikan: ${restoredCount}.`
                );

                pagination.current_page = 1;
                loadInterviews();
            } catch (error) {
                showAlert('danger', getErrorMessage(error));
            }
        }

        function openDeleteModal(id) {
            document.getElementById('deleteInterviewId').value = id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteInterviewId').value = '';
            document.getElementById('deleteModal').classList.add('hidden');
        }

        async function confirmDeleteInterview() {
            const id = document.getElementById('deleteInterviewId').value;

            if (!id) {
                showAlert('danger', 'ID jadwal tidak ditemukan.');
                return;
            }

            try {
                const result = await DutaAdmin.request(`/interviews/${id}`, {
                    method: 'DELETE',
                });

                closeDeleteModal();
                showAlert('success', result.message || 'Jadwal wawancara berhasil dihapus.');
                loadInterviews();
            } catch (error) {
                showAlert('danger', getErrorMessage(error));
            }
        }

        function renderStatusBadge(status) {
            const labels = {
                scheduled: 'Terjadwal',
                completed: 'Selesai',
                absent: 'Tidak Hadir',
                cancelled: 'Dibatalkan',
            };

            const classes = {
                scheduled: 'bg-blue-100 text-blue-700',
                completed: 'bg-green-100 text-green-700',
                absent: 'bg-yellow-100 text-yellow-700',
                cancelled: 'bg-red-100 text-red-700',
            };

            return `
                <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold ${classes[status] || 'bg-slate-100 text-slate-700'}">
                    ${labels[status] || status || '-'}
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
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
            });
        }

        function getPeriodId() {
            return document.getElementById('periodIdInput')?.value || 1;
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

            setTimeout(() => alert.classList.add('hidden'), 4000);
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

        function debounce(callback, delay = 400) {
            let timer;

            return function (...args) {
                clearTimeout(timer);
                timer = setTimeout(() => callback.apply(this, args), delay);
            };
        }
    </script>
@endpush