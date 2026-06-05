<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Pengumuman Hasil Seleksi - Duta PNJ' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
            <a href="{{ url('/') }}" class="text-xl font-extrabold text-[#00288E]">
                Duta PNJ
            </a>

            <nav class="hidden items-center gap-6 text-sm font-semibold text-slate-600 md:flex">
                <a href="{{ url('/') }}" class="hover:text-[#00288E]">Beranda</a>
                <a href="{{ url('/registration') }}" class="hover:text-[#00288E]">Pendaftaran</a>
                <a href="{{ route('public.results') }}" class="text-[#00288E]">Pengumuman</a>
            </nav>

            <a
                href="{{ url('/login') }}"
                class="rounded-md bg-[#00288E] px-4 py-2 text-sm font-bold text-white hover:bg-[#001F73]"
            >
                Login
            </a>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-6 py-10">
        <section class="text-center">
            <h1 class="text-3xl font-extrabold tracking-tight text-[#00288E] md:text-4xl">
                Pengumuman Hasil Seleksi Duta PNJ
            </h1>

            <p class="mx-auto mt-3 max-w-2xl text-sm leading-relaxed text-slate-600">
                Hasil seleksi ditampilkan berdasarkan keputusan resmi panitia setelah proses penilaian dan perhitungan ARAS selesai.
            </p>
        </section>

        <section id="notPublishedState" class="mt-10 hidden">
            <div class="rounded-xl border border-yellow-200 bg-yellow-50 px-6 py-8 text-center">
                <h2 class="text-xl font-extrabold text-yellow-800">
                    Hasil Seleksi Belum Dipublikasikan
                </h2>

                <p class="mx-auto mt-2 max-w-2xl text-sm leading-relaxed text-yellow-800">
                    Panitia belum mempublikasikan hasil seleksi. Silakan cek kembali halaman ini setelah pengumuman resmi dibuka.
                </p>
            </div>
        </section>

        <section id="publishedState" class="mt-10 hidden">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="space-y-6 lg:col-span-2">
                    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                        <label for="searchInput" class="mb-2 block text-sm font-bold text-slate-700">
                            Cari Peserta
                        </label>

                        <div class="flex flex-col gap-3 md:flex-row">
                            <input
                                id="searchInput"
                                type="text"
                                placeholder="Masukkan nama, NIM, atau program studi..."
                                class="h-11 flex-1 rounded-md border border-slate-300 bg-white px-4 text-sm outline-none focus:border-[#00288E] focus:ring-2 focus:ring-blue-100"
                            >

                            <button
                                type="button"
                                onclick="renderResults()"
                                class="h-11 rounded-md bg-[#00288E] px-5 text-sm font-bold text-white hover:bg-[#001F73]"
                            >
                                Cari
                            </button>
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-200 px-5 py-4">
                            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                <div>
                                    <h2 class="text-lg font-extrabold text-[#00288E]">
                                        Daftar Hasil Seleksi
                                    </h2>

                                    <p id="tableInfo" class="mt-1 text-sm text-slate-500">
                                        Memuat hasil seleksi...
                                    </p>
                                </div>

                                <span id="publishedAtBadge" class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                                    Published
                                </span>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-5 py-3 text-left text-xs font-extrabold uppercase tracking-wide text-slate-600">Peringkat</th>
                                        <th class="px-5 py-3 text-left text-xs font-extrabold uppercase tracking-wide text-slate-600">Nama Peserta</th>
                                        <th class="px-5 py-3 text-left text-xs font-extrabold uppercase tracking-wide text-slate-600">NIM</th>
                                        <th class="px-5 py-3 text-left text-xs font-extrabold uppercase tracking-wide text-slate-600">Program Studi</th>
                                        <th class="px-5 py-3 text-left text-xs font-extrabold uppercase tracking-wide text-slate-600">Status</th>
                                    </tr>
                                </thead>

                                <tbody id="resultsTableBody" class="divide-y divide-slate-200 bg-white">
                                    <tr>
                                        <td colspan="5" class="px-5 py-8 text-center text-sm text-slate-500">
                                            Memuat hasil seleksi...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <aside class="space-y-6">
                    <div class="rounded-xl bg-[#00288E] p-5 text-white shadow-sm">
                        <p class="text-xs font-extrabold uppercase tracking-wide text-blue-100">
                            Catatan Resmi
                        </p>

                        <div id="announcementNote" class="mt-3 text-sm leading-relaxed text-blue-50">
                            Memuat catatan resmi...
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                        <h2 class="text-lg font-extrabold text-slate-900">
                            Ringkasan Seleksi
                        </h2>

                        <div class="mt-4 space-y-3 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Total Peserta</span>
                                <span id="totalParticipants" class="font-extrabold text-slate-900">0</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Peserta Terpilih</span>
                                <span id="selectedParticipants" class="font-extrabold text-green-700">0</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Periode</span>
                                <span id="periodYear" class="font-extrabold text-slate-900">-</span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                        <h2 class="text-lg font-extrabold text-slate-900">
                            Informasi Lanjutan
                        </h2>

                        <p class="mt-2 text-sm leading-relaxed text-slate-600">
                            Peserta yang terpilih akan dihubungi oleh panitia untuk informasi tahap berikutnya.
                        </p>

                        <div class="mt-4 rounded-md border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                            Sertifikat partisipasi akan diinformasikan oleh panitia.
                        </div>
                    </div>
                </aside>
            </div>
        </section>
    </main>

    <footer class="mt-12 border-t border-slate-200 bg-white">
        <div class="mx-auto max-w-6xl px-6 py-8">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-lg font-extrabold text-[#00288E]">Duta PNJ</p>
                    <p class="mt-1 text-sm text-slate-500">
                        Sistem Seleksi Mahasiswa Politeknik Negeri Jakarta.
                    </p>
                </div>

                <p class="text-sm text-slate-500">
                    © 2026 Panitia Pemilihan Duta PNJ.
                </p>
            </div>
        </div>
    </footer>

    <script>
        const SELECTED_LIMIT = 18;

        let allResults = [];
        let publicData = null;

        document.addEventListener('DOMContentLoaded', function () {
            loadPublicResults();

            document.getElementById('searchInput')?.addEventListener('input', debounce(function () {
                renderResults();
            }, 300));
        });

        async function loadPublicResults() {
            try {
                const response = await fetch('/api/public/results', {
                    headers: {
                        'Accept': 'application/json',
                    },
                });

                const result = await response.json();
                const data = result?.data || {};

                publicData = data;

                if (!data.is_published) {
                    showNotPublished();
                    return;
                }

                allResults = data.results || [];

                showPublished();
                renderSummary(data);
                renderResults();
            } catch (error) {
                showNotPublished();
            }
        }

        function showNotPublished() {
            document.getElementById('notPublishedState').classList.remove('hidden');
            document.getElementById('publishedState').classList.add('hidden');
        }

        function showPublished() {
            document.getElementById('notPublishedState').classList.add('hidden');
            document.getElementById('publishedState').classList.remove('hidden');
        }

        function renderSummary(data) {
            const results = data.results || [];
            const selected = results.filter(item => Number(item.final_rank) <= SELECTED_LIMIT);

            document.getElementById('totalParticipants').textContent = results.length;
            document.getElementById('selectedParticipants').textContent = selected.length;
            document.getElementById('periodYear').textContent = data.period?.election_year || '-';

            document.getElementById('announcementNote').textContent =
                data.announcement_note || 'Tidak ada catatan tambahan dari panitia.';

            document.getElementById('publishedAtBadge').textContent = data.published_at
                ? `Dipublikasikan ${formatDateTime(data.published_at)}`
                : 'Published';
        }

        function renderResults() {
            const keyword = (document.getElementById('searchInput')?.value || '').toLowerCase();

            const sorted = [...allResults].sort((a, b) => Number(a.final_rank) - Number(b.final_rank));

            const filtered = sorted.filter(item => {
                const candidate = item.candidate || {};

                return [
                    candidate.full_name,
                    candidate.student_number,
                    candidate.study_program,
                    candidate.registration_number,
                ].some(value => String(value || '').toLowerCase().includes(keyword));
            });

            const tableBody = document.getElementById('resultsTableBody');

            if (!filtered.length) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-sm text-slate-500">
                            Data peserta tidak ditemukan.
                        </td>
                    </tr>
                `;

                document.getElementById('tableInfo').textContent = 'Menampilkan 0 peserta';
                return;
            }

            tableBody.innerHTML = filtered.map(item => {
                const candidate = item.candidate || {};
                const rank = Number(item.final_rank);
                const isSelected = rank <= SELECTED_LIMIT;

                return `
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-4">
                            ${renderRankBadge(rank)}
                        </td>

                        <td class="px-5 py-4">
                            <p class="font-extrabold text-[#00288E]">${escapeHtml(candidate.full_name || '-')}</p>
                            <p class="mt-1 text-xs text-slate-500">${escapeHtml(candidate.registration_number || '-')}</p>
                        </td>

                        <td class="px-5 py-4 text-sm text-slate-700">
                            ${escapeHtml(candidate.student_number || '-')}
                        </td>

                        <td class="px-5 py-4 text-sm text-slate-700">
                            ${escapeHtml(candidate.study_program || '-')}
                        </td>

                        <td class="px-5 py-4">
                            ${renderStatusBadge(isSelected)}
                        </td>
                    </tr>
                `;
            }).join('');

            document.getElementById('tableInfo').textContent =
                `Menampilkan ${filtered.length} dari ${allResults.length} peserta`;
        }

        function renderRankBadge(rank) {
            const baseClass = 'inline-flex h-8 w-8 items-center justify-center rounded-full text-sm font-extrabold';

            if (rank === 1) {
                return `<span class="${baseClass} bg-yellow-500 text-white">1</span>`;
            }

            if (rank === 2) {
                return `<span class="${baseClass} bg-yellow-300 text-slate-900">2</span>`;
            }

            if (rank === 3) {
                return `<span class="${baseClass} bg-yellow-100 text-yellow-700">3</span>`;
            }

            return `<span class="${baseClass} bg-slate-100 text-slate-700">${escapeHtml(rank || '-')}</span>`;
        }

        function renderStatusBadge(isSelected) {
            if (isSelected) {
                return `
                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                        Terpilih
                    </span>
                `;
            }

            return `
                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                    Belum Terpilih
                </span>
            `;
        }

        function formatDateTime(value) {
            if (!value) return '-';

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
</body>
</html>