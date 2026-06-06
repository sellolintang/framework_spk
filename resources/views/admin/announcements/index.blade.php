@extends('layouts.admin')

@section('content')
    <section class="mb-7">
        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
            <div>
                <h1 class="text-[34px] font-extrabold leading-tight tracking-tight text-[#00288E]">
                    Publikasi Pengumuman
                </h1>

                <p class="mt-2 text-sm font-medium text-slate-500">
                    Siapkan dan publikasikan hasil seleksi ke halaman publik.
                </p>
            </div>

            <div id="publishStatusBadge">
                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                    Memuat...
                </span>
            </div>
        </div>
    </section>

    <div id="pageAlert" class="mb-5 hidden rounded-md border px-4 py-3 text-sm"></div>

    <section class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <x-card>
                <div class="rounded-md border border-yellow-200 bg-yellow-50 px-4 py-3">
                    <div class="flex gap-3">
                        <div class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-yellow-100 text-sm font-extrabold text-yellow-700">
                            !
                        </div>

                        <div>
                            <p class="text-sm font-extrabold text-yellow-800">
                                Perhatian Penting
                            </p>

                            <p class="mt-1 text-sm leading-relaxed text-yellow-800">
                                Pastikan hasil perhitungan ARAS dan seluruh data penilaian sudah benar sebelum dipublikasikan.
                                Sistem akan menolak publikasi jika masih ada kandidat yang belum dinilai lengkap.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <x-period-select width="w-full" height="h-10" />
                </div>

                <div class="mt-5">
                    <label for="announcementNote" class="mb-2 block text-sm font-bold text-slate-700">
                        Catatan Pengumuman Resmi
                    </label>

                    <textarea
                        id="announcementNote"
                        rows="6"
                        placeholder="Contoh: Selamat kepada peserta yang lolos. Informasi tahap berikutnya akan diumumkan melalui panitia."
                        class="w-full rounded-md border border-slate-300 bg-white px-4 py-3 text-sm outline-none focus:border-[#00288E] focus:ring-2 focus:ring-blue-100"
                    ></textarea>

                    <p class="mt-1.5 text-xs text-slate-500">
                        Catatan ini akan tampil di hasil publik setelah pengumuman dipublikasikan.
                    </p>
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <button
                        type="button"
                        onclick="checkReadiness()"
                        class="rounded-md border border-slate-300 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50"
                    >
                        Cek Kesiapan
                    </button>

                    <button
                        type="button"
                        onclick="publishAnnouncement()"
                        class="rounded-md bg-[#00288E] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#001F73]"
                    >
                        Publikasikan Sekarang
                    </button>

                    <button
                        type="button"
                        onclick="openUnpublishModal()"
                        class="rounded-md border border-red-200 bg-white px-5 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50"
                    >
                        Batalkan Publikasi
                    </button>
                </div>
            </x-card>

            <x-card padding="p-0">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-lg font-extrabold text-[#00288E]">
                        Status Kesiapan Data
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Ringkasan validasi sebelum hasil dipublikasikan.
                    </p>
                </div>

                <div id="readinessContent" class="p-5">
                    <p class="text-sm text-slate-500">
                        Klik tombol Cek Kesiapan untuk melihat status data.
                    </p>
                </div>
            </x-card>
        </div>

        <x-card padding="p-0">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-lg font-extrabold text-slate-900">
                    Preview Kelulusan
                </h2>
                <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-[#00288E]">
                    Berdasarkan hasil ARAS
                </p>
            </div>

            <div id="previewContent" class="p-5">
                <p class="text-sm text-slate-500">
                    Memuat preview hasil ARAS...
                </p>
            </div>

            <div class="border-t border-slate-200 px-5 py-4">
                <p class="text-xs leading-relaxed text-slate-500">
                    Data preview disinkronkan dari hasil perhitungan ARAS terakhir.
                </p>
            </div>
        </x-card>
    </section>

    {{-- Modal Warning Kesiapan --}}
    <div id="readinessModal" class="fixed inset-0 z-[90] hidden">
        <div class="absolute inset-0 bg-slate-900/60" onclick="closeReadinessModal()"></div>

        <div class="relative z-[91] flex h-screen items-center justify-center p-6">
            <div
                class="flex w-full max-w-3xl flex-col overflow-hidden rounded-xl bg-white shadow-xl"
                style="height: calc(100vh - 48px);"
            >
                <div class="flex shrink-0 items-start justify-between gap-4 border-b border-slate-200 px-5 py-4">
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">
                            Pengumuman Belum Bisa Dipublikasikan
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Lengkapi data berikut sebelum mempublikasikan hasil seleksi.
                        </p>
                    </div>

                    <button
                        type="button"
                        onclick="closeReadinessModal()"
                        class="rounded-md p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-800"
                        aria-label="Tutup modal"
                    >
                        ✕
                    </button>
                </div>

                <div
                    id="readinessModalContent"
                    class="min-h-0 flex-1 overflow-y-auto px-5 py-5"
                >
                    <p class="text-sm text-slate-500">Memuat data...</p>
                </div>

                <div class="shrink-0 border-t border-slate-200 bg-slate-50 px-5 py-4">
                    <div class="flex justify-end">
                        <button
                            type="button"
                            onclick="closeReadinessModal()"
                            class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-50"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Batalkan Publikasi --}}
    <div id="unpublishModal" class="fixed inset-0 z-[90] hidden">
        <div class="absolute inset-0 bg-slate-900/50" onclick="closeUnpublishModal()"></div>

        <div class="relative z-[91] flex min-h-screen items-center justify-center p-4">
            <div class="w-full max-w-md overflow-hidden rounded-xl bg-white shadow-xl">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-lg font-extrabold text-slate-900">
                        Batalkan Publikasi?
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Hasil seleksi tidak akan tampil lagi di halaman publik.
                    </p>
                </div>

                <div class="px-5 py-5">
                    <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                        Apakah kamu yakin ingin membatalkan publikasi pengumuman?
                    </div>
                </div>

                <div class="flex justify-end gap-2 border-t border-slate-200 bg-slate-50 px-5 py-4">
                    <x-button type="button" variant="secondary" onclick="closeUnpublishModal()">
                        Batal
                    </x-button>

                    <button
                        type="button"
                        onclick="confirmUnpublishAnnouncement()"
                        class="rounded-md bg-red-600 px-4 py-2 text-sm font-bold text-white hover:bg-red-700"
                    >
                        Ya, Batalkan
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let latestReadiness = null;
        let latestArasResults = [];

        document.addEventListener('DOMContentLoaded', async function () {
            const params = new URLSearchParams(window.location.search);
            const periodId = params.get('period_id') || '1';

            await loadPeriodOptions('periodIdInput', periodId);

            loadPageData();

            document.getElementById('periodIdInput')?.addEventListener('change', function () {
                loadPageData();
            });
        });

        async function loadPageData() {
            await Promise.all([
                loadPreview(),
                loadPublicationStatus(),
                checkReadiness(false),
            ]);
        }

        async function loadPreview() {
            const periodId = getPeriodId();
            const preview = document.getElementById('previewContent');

            preview.innerHTML = `
                <p class="text-sm text-slate-500">
                    Memuat preview hasil ARAS...
                </p>
            `;

            try {
                const params = new URLSearchParams({
                    period_id: periodId,
                });

                const result = await DutaAdmin.request(`/aras-results?${params.toString()}`);
                latestArasResults = result?.data || [];

                renderPreview();
            } catch (error) {
                latestArasResults = [];

                preview.innerHTML = `
                    <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                        ${escapeHtml(getErrorMessage(error))}
                    </div>
                `;
            }
        }

        async function loadPublicationStatus() {
            const periodId = getPeriodId();

            try {
                const params = new URLSearchParams({
                    period_id: periodId,
                });

                const response = await fetch(`/api/public/results?${params.toString()}`, {
                    headers: {
                        'Accept': 'application/json',
                    },
                });

                const payload = await response.json();
                const data = payload?.data || {};

                if (data.is_published) {
                    renderPublishBadge(true);
                    document.getElementById('announcementNote').value = data.announcement_note || '';
                    return;
                }

                renderPublishBadge(false);
            } catch (error) {
                renderPublishBadge(false);
            }
        }

        async function checkReadiness(showMessage = true) {
            const periodId = getPeriodId();

            try {
                const result = await DutaAdmin.request('/announcements/check-readiness', {
                    method: 'POST',
                    body: JSON.stringify({
                        period_id: Number(periodId),
                    }),
                });

                latestReadiness = result?.data;

                renderReadiness(latestReadiness);

                if (showMessage) {
                    showAlert(
                        latestReadiness.ready ? 'success' : 'danger',
                        result.message || (latestReadiness.ready ? 'Data siap dipublikasikan.' : 'Data belum siap dipublikasikan.')
                    );
                }

                return latestReadiness;
            } catch (error) {
                showAlert('danger', getErrorMessage(error));
                return null;
            }
        }

        async function publishAnnouncement() {
            const readiness = await checkReadiness(false);

            if (!readiness) {
                showAlert('danger', 'Gagal mengecek kesiapan data.');
                return;
            }

            if (!readiness.ready) {
                openReadinessModal(readiness);
                return;
            }

            try {
                const result = await DutaAdmin.request('/announcements/publish', {
                    method: 'POST',
                    body: JSON.stringify({
                        period_id: Number(getPeriodId()),
                        announcement_note: document.getElementById('announcementNote').value || null,
                    }),
                });

                showAlert('success', result.message || 'Pengumuman berhasil dipublikasikan.');
                renderPublishBadge(true);
                await loadPublicationStatus();
            } catch (error) {
                showAlert('danger', getErrorMessage(error));
            }
        }

        function openUnpublishModal() {
            document.getElementById('unpublishModal').classList.remove('hidden');
        }

        function closeUnpublishModal() {
            document.getElementById('unpublishModal').classList.add('hidden');
        }

        async function confirmUnpublishAnnouncement() {
            try {
                const result = await DutaAdmin.request('/announcements/unpublish', {
                    method: 'POST',
                    body: JSON.stringify({
                        period_id: Number(getPeriodId()),
                    }),
                });

                closeUnpublishModal();
                showAlert('success', result.message || 'Publikasi berhasil dibatalkan.');
                renderPublishBadge(false);
            } catch (error) {
                showAlert('danger', getErrorMessage(error));
            }
        }

        function renderPreview() {
            const preview = document.getElementById('previewContent');

            if (!latestArasResults.length) {
                preview.innerHTML = `
                    <div class="rounded-md border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                        Belum ada hasil ARAS. Hitung ARAS terlebih dahulu sebelum publikasi.
                    </div>
                `;
                return;
            }

            const sorted = [...latestArasResults].sort((a, b) => Number(a.final_rank) - Number(b.final_rank));
            const topResults = sorted.slice(0, 10);

            preview.innerHTML = `
                <div class="space-y-3">
                    ${topResults.map(item => `
                        <div class="flex items-center justify-between gap-3 rounded-lg bg-slate-50 px-3 py-3">
                            <div class="flex min-w-0 items-center gap-3">
                                <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-extrabold text-[#00288E]">
                                    ${escapeHtml(item.final_rank || '-')}
                                </span>

                                <div class="min-w-0">
                                    <p class="truncate text-sm font-extrabold text-slate-900">
                                        ${escapeHtml(item.candidate?.full_name || '-')}
                                    </p>
                                    <p class="truncate text-xs text-slate-500">
                                        ${escapeHtml(item.candidate?.study_program || '-')}
                                    </p>
                                </div>
                            </div>

                            <span class="shrink-0 rounded-md bg-yellow-100 px-2 py-1 text-xs font-bold text-yellow-700">
                                Ki: ${formatDecimal(item.utility_score)}
                            </span>
                        </div>
                    `).join('')}
                </div>

                <p class="mt-4 text-center text-xs font-bold text-[#00288E]">
                    Menampilkan ${topResults.length} dari ${sorted.length} kandidat
                </p>
            `;
        }

        function renderReadiness(readiness) {
            const content = document.getElementById('readinessContent');

            if (!readiness) {
                content.innerHTML = `
                    <p class="text-sm text-slate-500">
                        Data kesiapan belum tersedia.
                    </p>
                `;
                return;
            }

            content.innerHTML = `
                <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                    <div class="rounded-lg border border-slate-200 p-4">
                        <p class="text-xs font-bold uppercase text-slate-500">Kriteria Aktif</p>
                        <p class="mt-1 text-2xl font-extrabold text-slate-900">${readiness.criteria_count || 0}</p>
                    </div>

                    <div class="rounded-lg border border-slate-200 p-4">
                        <p class="text-xs font-bold uppercase text-slate-500">Kandidat Layak</p>
                        <p class="mt-1 text-2xl font-extrabold text-slate-900">${readiness.eligible_candidate_count || 0}</p>
                    </div>

                    <div class="rounded-lg border border-slate-200 p-4">
                        <p class="text-xs font-bold uppercase text-slate-500">Hasil ARAS</p>
                        <p class="mt-1 text-2xl font-extrabold text-slate-900">${readiness.aras_result_count || 0}</p>
                    </div>

                    <div class="rounded-lg border border-slate-200 p-4">
                        <p class="text-xs font-bold uppercase text-slate-500">Nilai Belum Lengkap</p>
                        <p class="mt-1 text-2xl font-extrabold ${readiness.missing_score_count > 0 ? 'text-red-600' : 'text-green-600'}">
                            ${readiness.missing_score_count || 0}
                        </p>
                    </div>
                </div>

                <div class="mt-4">
                    ${readiness.ready ? `
                        <div class="rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">
                            Data siap dipublikasikan.
                        </div>
                    ` : `
                        <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                            Data belum siap dipublikasikan. Klik Publikasikan Sekarang untuk melihat rincian warning.
                        </div>
                    `}
                </div>
            `;
        }

        function openReadinessModal(readiness) {
            const content = document.getElementById('readinessModalContent');

            const warnings = readiness.warnings || [];
            const samples = readiness.missing_score_samples || [];

            content.innerHTML = `
                <div class="space-y-5">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900">Warning</h3>

                        <div class="mt-2 space-y-2">
                            ${warnings.length ? warnings.map(warning => `
                                <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                                    ${escapeHtml(warning)}
                                </div>
                            `).join('') : `
                                <p class="text-sm text-slate-500">Tidak ada warning.</p>
                            `}
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900">
                            Contoh Nilai yang Belum Lengkap
                        </h3>

                        <div class="mt-2 overflow-hidden rounded-lg border border-slate-200">
                            <div class="max-h-80 overflow-y-auto">
                                <table class="min-w-full divide-y divide-slate-200 text-sm">
                                    <thead class="sticky top-0 bg-slate-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left font-bold text-slate-600">Kandidat</th>
                                            <th class="px-4 py-3 text-left font-bold text-slate-600">Kode</th>
                                            <th class="px-4 py-3 text-left font-bold text-slate-600">Kriteria</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 bg-white">
                                        ${samples.length ? samples.map(item => `
                                            <tr>
                                                <td class="px-4 py-3 font-semibold text-slate-900">
                                                    ${escapeHtml(item.candidate_name || '-')}
                                                </td>
                                                <td class="px-4 py-3 font-bold text-[#00288E]">
                                                    ${escapeHtml(item.criterion_code || '-')}
                                                </td>
                                                <td class="px-4 py-3 text-slate-700">
                                                    ${escapeHtml(item.criterion_name || '-')}
                                                </td>
                                            </tr>
                                        `).join('') : `
                                            <tr>
                                                <td colspan="3" class="px-4 py-6 text-center text-slate-500">
                                                    Tidak ada data nilai yang kurang.
                                                </td>
                                            </tr>
                                        `}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <p class="mt-2 text-xs text-slate-500">
                            Ditampilkan maksimal beberapa data pertama. Lengkapi seluruh nilai sebelum publikasi.
                        </p>
                    </div>
                </div>
            `;

            document.getElementById('readinessModal').classList.remove('hidden');
        }

        function closeReadinessModal() {
            document.getElementById('readinessModal').classList.add('hidden');
        }

        function renderPublishBadge(isPublished) {
            const target = document.getElementById('publishStatusBadge');

            if (isPublished) {
                target.innerHTML = `
                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                        Published
                    </span>
                `;
                return;
            }

            target.innerHTML = `
                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                    Draft
                </span>
            `;
        }

        function getPeriodId() {
            return document.getElementById('periodIdInput')?.value || 1;
        }

        function formatDecimal(value) {
            const number = Number(value || 0);

            return new Intl.NumberFormat('id-ID', {
                maximumFractionDigits: 6,
            }).format(number);
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