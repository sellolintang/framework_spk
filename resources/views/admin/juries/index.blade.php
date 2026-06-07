@extends('layouts.admin')

@section('content')
    <section class="mb-7">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-[34px] font-extrabold leading-none tracking-tight text-[#00288E]">
                    Manajemen Akun Juri
                </h1>

                <p class="mt-2 text-sm font-medium text-slate-500">
                    Kelola akun juri, status akses, dan kriteria yang ditugaskan.
                </p>
            </div>

            <x-button href="{{ route('admin.juries.create') }}">
                Tambah Juri Baru
            </x-button>
        </div>
    </section>

    <div id="pageAlert" class="mb-5 hidden rounded-md border px-4 py-3 text-sm"></div>

    <section class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <x-card>
            <p class="text-sm font-semibold text-slate-700">Total Juri</p>
            <h2 id="totalJuries" class="mt-2 text-3xl font-extrabold text-slate-900">0</h2>
        </x-card>

        <x-card>
            <p class="text-sm font-semibold text-slate-700">Juri Aktif</p>
            <h2 id="activeJuries" class="mt-2 text-3xl font-extrabold text-slate-900">0</h2>
        </x-card>

        <x-card>
            <p class="text-sm font-semibold text-slate-700">Nonaktif</p>
            <h2 id="inactiveJuries" class="mt-2 text-3xl font-extrabold text-slate-900">0</h2>
        </x-card>
    </section>

    <section class="mt-7">
        <x-card padding="p-0">
            <div class="border-b border-slate-200 px-5 py-4">
                <div class="grid grid-cols-1 gap-3 md:grid-cols-[160px_1fr_180px_140px]">
                    <div>
                        <label for="periodIdInput" class="mb-1 block text-xs font-bold text-slate-600">
                            Periode ID
                        </label>
                        <input
                            id="periodIdInput"
                            type="number"
                            min="1"
                            value="1"
                            class="h-11 w-full rounded-md border border-slate-300 bg-white px-4 text-sm outline-none focus:border-[#00288E] focus:ring-2 focus:ring-blue-100"
                        >
                    </div>

                    <div>
                        <label for="searchInput" class="mb-1 block text-xs font-bold text-slate-600">
                            Pencarian
                        </label>
                        <input
                            id="searchInput"
                            type="text"
                            placeholder="Cari nama, email, atau nomor HP juri..."
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
                            <option value="active">Aktif</option>
                            <option value="inactive">Nonaktif</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button
                            type="button"
                            onclick="loadJuries()"
                            class="h-11 w-full rounded-md border border-slate-300 bg-white px-4 text-sm font-bold text-slate-700 hover:bg-slate-50"
                        >
                            Muat Ulang
                        </button>
                    </div>
                </div>
            </div>

            <x-table
                :headers="['No', 'Nama Juri', 'Email', 'Nomor HP', 'Kriteria Ditugaskan', 'Status', 'Aksi']"
                tbody-id="juriesTableBody"
                class="rounded-none border-0 shadow-none"
            >
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                        Memuat data akun juri...
                    </td>
                </tr>
            </x-table>

            <div class="flex items-center justify-between border-t border-slate-200 bg-slate-50 px-6 py-4 text-sm text-slate-600">
                <span id="tableInfo">
                    Menampilkan data akun juri
                </span>

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

    {{-- Modal Reset Password --}}
    <div id="resetPasswordModal" class="fixed inset-0 z-90 hidden">
        <div class="absolute inset-0 bg-slate-900/50" onclick="closeResetPasswordModal()"></div>

        <div class="relative mx-auto mt-24 w-[92%] max-w-md">
            <div class="overflow-hidden rounded-xl bg-white shadow-xl">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-lg font-extrabold text-slate-900">
                        Reset Password Juri
                    </h2>
                    <p id="resetPasswordJuryName" class="mt-1 text-sm text-slate-500">
                        -
                    </p>
                </div>

                <form id="resetPasswordForm" class="space-y-4 px-5 py-5">
                    <input type="hidden" id="resetPasswordJuryId">

                    <div>
                        <label for="newPassword" class="mb-2 block text-sm font-bold text-slate-700">
                            Password Baru
                        </label>
                        <input
                            id="newPassword"
                            type="password"
                            minlength="6"
                            required
                            class="h-11 w-full rounded-md border border-slate-300 px-4 text-sm outline-none focus:border-[#00288E] focus:ring-2 focus:ring-blue-100"
                        >
                    </div>

                    <div>
                        <label for="newPasswordConfirmation" class="mb-2 block text-sm font-bold text-slate-700">
                            Konfirmasi Password Baru
                        </label>
                        <input
                            id="newPasswordConfirmation"
                            type="password"
                            minlength="6"
                            required
                            class="h-11 w-full rounded-md border border-slate-300 px-4 text-sm outline-none focus:border-[#00288E] focus:ring-2 focus:ring-blue-100"
                        >
                    </div>

                    <div class="flex justify-end gap-2 border-t border-slate-200 pt-4">
                        <x-button type="button" variant="secondary" onclick="closeResetPasswordModal()">
                            Batal
                        </x-button>

                        <x-button type="submit">
                            Simpan Password
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let juriesData = [];
        let pagination = {
            current_page: 1,
            last_page: 1,
            total: 0,
            per_page: 10,
        };

        document.addEventListener('DOMContentLoaded', function () {
            loadJuries();

            document.getElementById('searchInput')?.addEventListener('input', debounce(function () {
                pagination.current_page = 1;
                loadJuries();
            }, 400));

            document.getElementById('statusFilter')?.addEventListener('change', function () {
                pagination.current_page = 1;
                loadJuries();
            });

            document.getElementById('periodIdInput')?.addEventListener('change', function () {
                pagination.current_page = 1;
                loadJuries();
            });

            document.getElementById('resetPasswordForm')?.addEventListener('submit', submitResetPassword);
        });

        async function loadJuries() {
            const tableBody = document.getElementById('juriesTableBody');

            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                        Memuat data akun juri...
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

                const result = await DutaAdmin.request(`/juries?${params.toString()}`);

                const payload = result?.data || {};
                juriesData = payload.data || [];

                pagination.current_page = payload.current_page || 1;
                pagination.last_page = payload.last_page || 1;
                pagination.total = payload.total || juriesData.length;
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
            const total = pagination.total;
            const active = juriesData.filter(item => isActive(item.is_active)).length;
            const inactive = juriesData.filter(item => !isActive(item.is_active)).length;

            setText('totalJuries', total);
            setText('activeJuries', active);
            setText('inactiveJuries', inactive);
        }

        function renderTable() {
            const tableBody = document.getElementById('juriesTableBody');

            if (!juriesData.length) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                            Belum ada data akun juri.
                        </td>
                    </tr>
                `;
                return;
            }

            tableBody.innerHTML = juriesData.map((jury, index) => {
                const rowNumber = ((pagination.current_page - 1) * pagination.per_page) + index + 1;
                const initial = getInitial(jury.name);
                const criteriaBadges = renderCriteriaBadges(jury.criteria_codes || []);
                const statusBadge = renderStatusBadge(jury.is_active);

                return `
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-slate-600">
                            ${rowNumber}
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-xs font-extrabold text-[#00288E]">
                                    ${escapeHtml(initial)}
                                </div>

                                <div>
                                    <p class="font-extrabold text-slate-900">
                                        ${escapeHtml(jury.name || '-')}
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500">
                                        ID Juri: ${escapeHtml(jury.id)}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-slate-600">
                            ${escapeHtml(jury.email || '-')}
                        </td>

                        <td class="px-6 py-4 text-slate-600">
                            ${escapeHtml(jury.phone || '-')}
                        </td>

                        <td class="px-6 py-4">
                            ${criteriaBadges}
                        </td>

                        <td class="px-6 py-4">
                            ${statusBadge}
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex flex-wrap items-center gap-2">
                                <a
                                    href="/admin/juries/${jury.id}?period_id=${getPeriodId()}"
                                    class="rounded-md border border-slate-300 px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-50"
                                >
                                    Detail
                                </a>

                                <a
                                    href="/admin/juries/${jury.id}/edit"
                                    class="rounded-md border border-slate-300 px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-50"
                                >
                                    Edit
                                </a>

                                <button
                                    type="button"
                                    onclick="openResetPasswordModal(${jury.id})"
                                    class="rounded-md border border-yellow-200 px-3 py-1.5 text-xs font-bold text-yellow-700 hover:bg-yellow-50"
                                >
                                    Reset
                                </button>

                                <button
                                    type="button"
                                    onclick="toggleJuryStatus(${jury.id})"
                                    class="rounded-md border border-blue-200 px-3 py-1.5 text-xs font-bold text-blue-700 hover:bg-blue-50"
                                >
                                    ${isActive(jury.is_active) ? 'Nonaktifkan' : 'Aktifkan'}
                                </button>

                                <button
                                    type="button"
                                    onclick="deleteJury(${jury.id})"
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

            setText('tableInfo', `Menampilkan ${start} sampai ${end} dari ${pagination.total} juri`);
            setText('pageInfo', pagination.current_page);
        }

        function changePage(direction) {
            const nextPage = pagination.current_page + direction;

            if (nextPage < 1 || nextPage > pagination.last_page) {
                return;
            }

            pagination.current_page = nextPage;
            loadJuries();
        }

        function openResetPasswordModal(id) {
            const jury = juriesData.find(item => Number(item.id) === Number(id));

            if (!jury) {
                showAlert('danger', 'Data juri tidak ditemukan.');
                return;
            }

            document.getElementById('resetPasswordJuryId').value = jury.id;
            document.getElementById('resetPasswordJuryName').textContent = jury.name || '-';
            document.getElementById('newPassword').value = '';
            document.getElementById('newPasswordConfirmation').value = '';

            document.getElementById('resetPasswordModal').classList.remove('hidden');
        }

        function closeResetPasswordModal() {
            document.getElementById('resetPasswordModal').classList.add('hidden');
        }

        async function submitResetPassword(event) {
            event.preventDefault();

            const juryId = document.getElementById('resetPasswordJuryId').value;
            const password = document.getElementById('newPassword').value;
            const passwordConfirmation = document.getElementById('newPasswordConfirmation').value;

            if (password !== passwordConfirmation) {
                showAlert('danger', 'Konfirmasi password tidak sama.');
                return;
            }

            try {
                await DutaAdmin.request(`/juries/${juryId}/reset-password`, {
                    method: 'POST',
                    body: JSON.stringify({
                        password: password,
                        password_confirmation: passwordConfirmation,
                    }),
                });

                closeResetPasswordModal();
                showAlert('success', 'Password juri berhasil direset.');
            } catch (error) {
                console.error(error);
                showAlert('danger', getErrorMessage(error));
            }
        }

        async function toggleJuryStatus(id) {
            const jury = juriesData.find(item => Number(item.id) === Number(id));

            if (!jury) {
                showAlert('danger', 'Data juri tidak ditemukan.');
                return;
            }

            const message = isActive(jury.is_active)
                ? 'Yakin ingin menonaktifkan akun juri ini?'
                : 'Yakin ingin mengaktifkan akun juri ini?';

            if (!confirm(message)) return;

            try {
                await DutaAdmin.request(`/juries/${id}/toggle-status`, {
                    method: 'PATCH',
                });

                showAlert('success', 'Status akun juri berhasil diperbarui.');
                await loadJuries();
            } catch (error) {
                console.error(error);
                showAlert('danger', getErrorMessage(error));
            }
        }

        async function deleteJury(id) {
            if (!confirm('Yakin ingin menghapus akun juri ini? Jika sudah memiliki nilai, akun akan dinonaktifkan.')) {
                return;
            }

            try {
                await DutaAdmin.request(`/juries/${id}`, {
                    method: 'DELETE',
                });

                showAlert('success', 'Akun juri berhasil diproses.');
                await loadJuries();
            } catch (error) {
                console.error(error);
                showAlert('danger', getErrorMessage(error));
            }
        }

        function renderCriteriaBadges(codes) {
            if (!codes.length) {
                return `<span class="text-xs font-semibold text-red-600">Belum ditugaskan</span>`;
            }

            return `
                <div class="flex flex-wrap gap-1.5">
                    ${codes.map(code => `
                        <span class="rounded-md bg-blue-100 px-2 py-1 text-xs font-extrabold text-[#00288E]">
                            ${escapeHtml(code)}
                        </span>
                    `).join('')}
                </div>
            `;
        }

        function renderStatusBadge(value) {
            if (isActive(value)) {
                return `<span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-extrabold text-yellow-800">AKTIF</span>`;
            }

            return `<span class="rounded-full bg-slate-200 px-3 py-1 text-xs font-extrabold text-slate-600">NONAKTIF</span>`;
        }

        function getInitial(name) {
            if (!name) return '-';

            return String(name)
                .split(' ')
                .filter(Boolean)
                .slice(0, 2)
                .map(word => word.charAt(0))
                .join('')
                .toUpperCase();
        }

        function getPeriodId() {
            return Number(document.getElementById('periodIdInput')?.value || 1);
        }

        function showAlert(type, message) {
            const alert = document.getElementById('pageAlert');

            const classes = {
                success: 'border-green-200 bg-green-50 text-green-800',
                danger: 'border-red-200 bg-red-50 text-red-800',
                warning: 'border-yellow-200 bg-yellow-50 text-yellow-800',
                info: 'border-blue-200 bg-blue-50 text-blue-800',
            };

            alert.className = `mb-5 rounded-md border px-4 py-3 text-sm ${classes[type] || classes.info}`;
            alert.textContent = message;
            alert.classList.remove('hidden');

            setTimeout(() => {
                alert.classList.add('hidden');
            }, 5000);
        }

        function getErrorMessage(error) {
            if (error?.errors) {
                return Object.values(error.errors).flat().join(' ');
            }

            return error?.message || 'Terjadi kesalahan.';
        }

        function isActive(value) {
            return value === true || value === 1 || value === '1';
        }

        function setText(id, value) {
            const element = document.getElementById(id);

            if (element) {
                element.textContent = value;
            }
        }

        function formatNumber(value) {
            return Number(value || 0).toLocaleString('id-ID', {
                maximumFractionDigits: 2,
            });
        }

        function escapeHtml(value) {
            return String(value ?? '')
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