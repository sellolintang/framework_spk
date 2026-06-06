@extends('layouts.admin')

@section('content')
    <section class="mb-7">
        <div class="flex items-start justify-between gap-5">
            <div>
                <h1 class="text-[34px] font-extrabold leading-tight tracking-tight text-[#00288E]">
                    Hasil Perhitungan ARAS
                </h1>

                <p class="mt-2 max-w-2xl text-sm font-medium leading-relaxed text-slate-500">
                    Keputusan seleksi akhir berdasarkan metode ARAS
                    <span class="font-semibold">(Additive Ratio Assessment)</span>.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button
                    type="button"
                    onclick="loadArasResults()"
                    class="h-11 rounded-md border border-slate-300 bg-white px-4 text-sm font-bold text-slate-700 hover:bg-slate-50"
                >
                    Muat Hasil
                </button>

                <button
                    type="button"
                    onclick="calculateAras()"
                    class="h-11 rounded-md bg-[#00288E] px-5 text-sm font-bold text-white hover:bg-[#001F73]"
                >
                    Hitung ARAS
                </button>
            </div>
        </div>
    </section>

    <div id="pageAlert" class="mb-5 hidden rounded-md border px-4 py-3 text-sm"></div>

    <section class="mb-7">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <x-card>
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-100 text-yellow-700">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>

                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-wide text-slate-500">Total Kandidat</p>
                        <h2 id="totalCandidates" class="mt-1 text-3xl font-extrabold text-slate-900">0</h2>
                        <p class="text-xs font-medium text-slate-500">Kandidat masuk ranking</p>
                    </div>
                </div>
            </x-card>

            <x-card>
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-[#00288E]">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none">
                            <path d="M12 2l2.9 6.7L22 9.3l-5.3 4.8 1.6 7.1L12 17.4l-6.3 3.8 1.6-7.1L2 9.3l7.1-.6L12 2z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        </svg>
                    </div>

                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-wide text-slate-500">Peringkat Teratas</p>
                        <h2 id="topCandidate" class="mt-1 text-xl font-extrabold text-slate-900">-</h2>
                        <p class="text-xs font-medium text-slate-500">Kandidat terbaik</p>
                    </div>
                </div>
            </x-card>

            <x-card>
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 text-green-700">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none">
                            <path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </div>

                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-wide text-slate-500">Utility Tertinggi</p>
                        <h2 id="topUtility" class="mt-1 text-3xl font-extrabold text-slate-900">0</h2>
                        <p class="text-xs font-medium text-slate-500">Nilai Ki terbaik</p>
                    </div>
                </div>
            </x-card>

            <div class="overflow-hidden rounded-xl bg-[#00288E] p-5 text-white shadow-sm">
                <p class="text-xs font-extrabold uppercase tracking-wide text-blue-100">
                    Status Perhitungan
                </p>

                <h2 id="calculationStatus" class="mt-2 text-2xl font-extrabold">
                    Belum Dimuat
                </h2>

                <p id="lastCalculatedText" class="mt-2 text-sm font-medium text-blue-100">
                    Muat hasil atau hitung ARAS untuk melihat status terbaru.
                </p>
            </div>
        </div>
    </section>

    <section>
        <x-card padding="p-0">
            <div class="border-b border-slate-200 px-5 py-4">
                <div class="mb-4">
                    <h2 class="text-xl font-extrabold text-[#00288E]">
                        Tabel Ranking ARAS
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Ranking disusun berdasarkan nilai utility score atau Ki terbesar.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-3 md:grid-cols-[160px_140px]">
                    <x-period-select width="w-40" height="h-10" />

                    <div class="flex items-end">
                        <button
                            type="button"
                            onclick="loadArasResults()"
                            class="h-10 w-full rounded-md border border-slate-300 bg-white px-4 text-sm font-bold text-slate-700 hover:bg-slate-50"
                        >
                            Filter
                        </button>
                    </div>
                </div>
            </div>

            <x-table
                :headers="['Ranking', 'Nama Lengkap', 'NIM', 'Program Studi', 'Nilai Si', 'Nilai Ki', 'Status']"
                tbody-id="arasTableBody"
                class="rounded-none border-0 shadow-none"
            >
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                        Memuat data hasil ARAS...
                    </td>
                </tr>
            </x-table>

            <div class="flex items-center justify-between border-t border-slate-200 bg-slate-50 px-6 py-4 text-sm text-slate-600">
                <span id="tableInfo">
                    Menampilkan 0 hasil ranking
                </span>

                <span class="rounded-md bg-white px-3 py-1.5 text-xs font-bold text-slate-500 ring-1 ring-slate-200">
                    ARAS Ranking
                </span>
            </div>
        </x-card>
    </section>

    <section class="mt-7">
        <div class="rounded-xl border border-yellow-200 bg-white px-5 py-4 shadow-sm">
            <div class="flex gap-3">
                <div class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-yellow-100 text-sm font-extrabold text-yellow-700">
                    i
                </div>

                <div>
                    <h3 class="text-sm font-extrabold text-[#00288E]">
                        Catatan Metodologi
                    </h3>

                    <p class="mt-1 text-sm leading-relaxed text-slate-600">
                        Hasil ARAS dihitung dengan merata-ratakan nilai juri pada kriteria yang sama,
                        membentuk alternatif ideal, melakukan normalisasi, mengalikan nilai dengan bobot kriteria,
                        lalu menghitung nilai Si dan Ki. Ranking akhir diurutkan berdasarkan Ki terbesar.
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        let arasResults = [];

        document.addEventListener('DOMContentLoaded', async function () {
            const params = new URLSearchParams(window.location.search);
            const periodId = params.get('period_id') || '1';

            await loadPeriodOptions('periodIdInput', periodId);

            loadArasResults();

            document.getElementById('periodIdInput')?.addEventListener('change', function () {
                loadArasResults();
            });
        });

        async function loadArasResults() {
            const periodId = getPeriodId();
            const tableBody = document.getElementById('arasTableBody');

            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                        Memuat data hasil ARAS...
                    </td>
                </tr>
            `;

            try {
                const params = new URLSearchParams({
                    period_id: periodId,
                });

                const result = await DutaAdmin.request(`/aras-results?${params.toString()}`);

                arasResults = result?.data || [];

                renderStats();
                renderTable();
            } catch (error) {
                console.error(error);

                arasResults = [];
                renderStats();

                tableBody.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-red-600">
                            ${escapeHtml(getErrorMessage(error))}
                        </td>
                    </tr>
                `;
            }
        }

        async function calculateAras() {
            const periodId = getPeriodId();

            try {
                const result = await DutaAdmin.request('/aras-results/calculate', {
                    method: 'POST',
                    body: JSON.stringify({
                        period_id: Number(periodId),
                    }),
                });

                const count = result?.data?.candidate_count || 0;

                showAlert('success', `${result.message || 'Perhitungan ARAS berhasil.'} Total kandidat: ${count}.`);

                await loadArasResults();
            } catch (error) {
                console.error(error);
                showAlert('danger', getErrorMessage(error));
            }
        }

        function renderStats() {
            const sorted = [...arasResults].sort((a, b) => Number(a.final_rank) - Number(b.final_rank));
            const top = sorted[0];

            setText('totalCandidates', arasResults.length);
            setText('topCandidate', top?.candidate?.full_name || '-');
            setText('topUtility', top ? formatDecimal(top.utility_score) : '0');

            if (arasResults.length) {
                setText('calculationStatus', 'Data Valid & Final');
                setText('lastCalculatedText', `Terakhir dihitung: ${formatDateTime(top?.calculated_at)}`);
            } else {
                setText('calculationStatus', 'Belum Ada Hasil');
                setText('lastCalculatedText', 'Klik Hitung ARAS untuk membuat ranking periode ini.');
            }
        }

        function renderTable() {
            const tableBody = document.getElementById('arasTableBody');

            if (!arasResults.length) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                            Belum ada hasil ARAS. Klik tombol Hitung ARAS untuk membuat ranking.
                        </td>
                    </tr>
                `;

                setText('tableInfo', 'Menampilkan 0 hasil ranking');
                return;
            }

            const sorted = [...arasResults].sort((a, b) => Number(a.final_rank) - Number(b.final_rank));

            tableBody.innerHTML = sorted.map(item => `
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4">
                        ${renderRankBadge(item.final_rank)}
                    </td>

                    <td class="px-6 py-4">
                        <p class="font-extrabold text-[#00288E]">${escapeHtml(item.candidate?.full_name || '-')}</p>
                        <p class="mt-1 text-xs text-slate-500">${escapeHtml(item.candidate?.registration_number || '-')}</p>
                    </td>

                    <td class="px-6 py-4 text-slate-700">
                        ${escapeHtml(item.candidate?.student_number || '-')}
                    </td>

                    <td class="px-6 py-4 text-slate-700">
                        ${escapeHtml(item.candidate?.study_program || '-')}
                    </td>

                    <td class="px-6 py-4 font-semibold text-slate-700">
                        ${formatDecimal(item.total_score)}
                    </td>

                    <td class="px-6 py-4 font-extrabold text-[#00288E]">
                        ${formatDecimal(item.utility_score)}
                    </td>

                    <td class="px-6 py-4">
                        ${renderResultStatus(item.final_rank)}
                    </td>
                </tr>
            `).join('');

            setText('tableInfo', `Menampilkan ${sorted.length} hasil ranking`);
        }

        function renderRankBadge(rank) {
            const number = Number(rank);

            if (number === 1) {
                return `
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-yellow-500 text-sm font-extrabold text-white">
                        1
                    </span>
                `;
            }

            if (number === 2) {
                return `
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-yellow-300 text-sm font-extrabold text-slate-900">
                        2
                    </span>
                `;
            }

            if (number === 3) {
                return `
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-yellow-100 text-sm font-extrabold text-yellow-700">
                        3
                    </span>
                `;
            }

            return `
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-sm font-bold text-slate-700">
                    ${escapeHtml(rank || '-')}
                </span>
            `;
        }

        function renderResultStatus(rank) {
            const number = Number(rank);

            if (number === 1) {
                return `
                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                        Terbaik
                    </span>
                `;
            }

            return `
                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                    Masuk Ranking
                </span>
            `;
        }

        function getPeriodId() {
            return document.getElementById('periodIdInput')?.value || 1;
        }

        function formatDecimal(value) {
            const number = Number(value || 0);

            return new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 6,
            }).format(number);
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