@extends('layouts.jury')

@section('content')
    <section class="mb-7">
        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
            <div>
                <div class="mb-3 flex items-center gap-2 text-sm font-semibold text-slate-500">
                    <a href="{{ route('jury.scoring.index') }}" class="hover:text-[#00288E]">Penilaian Peserta</a>
                    <span>/</span>
                    <span class="text-[#00288E]">Detail Peserta</span>
                </div>

                <h1 class="text-[34px] font-extrabold leading-tight tracking-tight text-[#00288E]">
                    Detail Peserta
                </h1>

                <p class="mt-2 text-sm font-medium text-slate-500">
                    Lihat informasi peserta sebelum melakukan penilaian.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a
                    id="backLink"
                    href="{{ route('jury.scoring.index') }}"
                    class="rounded-md border border-slate-300 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50"
                >
                    Kembali
                </a>

                <a
                    id="startScoringLink"
                    href="#"
                    class="rounded-md bg-[#00288E] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#001F73]"
                >
                    Mulai Penilaian
                </a>
            </div>
        </div>
    </section>

    <div id="pageAlert" class="mb-5 hidden rounded-md border px-4 py-3 text-sm"></div>

    <section id="loadingState">
        <x-card>
            <div class="py-10 text-center text-sm text-slate-500">
                Memuat detail peserta...
            </div>
        </x-card>
    </section>

    <section id="detailContent" class="hidden space-y-6">
        <section class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <x-card>
                <p class="text-sm font-semibold text-slate-700">Status Penilaian</p>
                <div id="scoringStatus" class="mt-3"></div>
            </x-card>

            <x-card>
                <p class="text-sm font-semibold text-slate-700">Jadwal Wawancara</p>
                <h2 id="scheduledAt" class="mt-2 text-lg font-extrabold text-slate-900">-</h2>
            </x-card>

            <x-card>
                <p class="text-sm font-semibold text-slate-700">Lokasi</p>
                <h2 id="locationText" class="mt-2 text-lg font-extrabold text-slate-900">-</h2>
            </x-card>
        </section>

        <section class="grid grid-cols-1 gap-5 lg:grid-cols-3">
            <div class="space-y-5 lg:col-span-2">
                <x-card>
                    <div class="mb-5 border-b border-slate-200 pb-4">
                        <h2 class="text-lg font-extrabold text-slate-900">
                            Informasi Peserta
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <p class="text-xs font-bold uppercase text-slate-500">Nama Lengkap</p>
                            <p id="candidateName" class="mt-1 text-base font-extrabold text-slate-900">-</p>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase text-slate-500">NIM</p>
                            <p id="studentNumber" class="mt-1 text-base font-semibold text-slate-700">-</p>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase text-slate-500">Nomor Pendaftaran</p>
                            <p id="registrationNumber" class="mt-1 text-base font-semibold text-slate-700">-</p>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase text-slate-500">Program Studi</p>
                            <p id="studyProgram" class="mt-1 text-base font-semibold text-slate-700">-</p>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase text-slate-500">Status Peserta</p>
                            <div id="candidateStatus" class="mt-2"></div>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase text-slate-500">Status Wawancara</p>
                            <div id="interviewStatus" class="mt-2"></div>
                        </div>
                    </div>
                </x-card>

                <div class="rounded-xl border border-blue-200 bg-blue-50 px-5 py-4">
                    <h3 class="text-sm font-extrabold text-[#00288E]">
                        Catatan untuk Juri
                    </h3>

                    <p class="mt-2 text-sm leading-relaxed text-slate-600">
                        Pastikan data peserta sudah sesuai sebelum memulai penilaian. Penilaian dilakukan berdasarkan kriteria yang sudah ditugaskan kepada akun juri kamu. Jika ada ketidaksesuaian data, hubungi admin seleksi.
                    </p>
                </div>
            </div>

            <x-card padding="p-0">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-lg font-extrabold text-slate-900">
                        Kriteria yang Dinilai
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Kriteria yang ditugaskan kepadamu.
                    </p>
                </div>

                <div id="criteriaList" class="p-5">
                    <p class="text-sm text-slate-500">Memuat kriteria...</p>
                </div>
            </x-card>
        </section>
    </section>
@endsection

@push('scripts')
    <script>
        const candidateId = @json($candidateId);
        let detailData = null;

        document.addEventListener('DOMContentLoaded', function () {
            const periodId = getPeriodId();

            document.getElementById('backLink').href = `/jury/scoring?period_id=${periodId}`;
            document.getElementById('startScoringLink').href = `/jury/scoring/${candidateId}/form?period_id=${periodId}`;

            loadCandidateDetail();
        });

        async function loadCandidateDetail() {
            try {
                const params = new URLSearchParams({
                    period_id: getPeriodId(),
                });

                const result = await DutaJury.request(`/jury/scoring-candidates/${candidateId}?${params.toString()}`);
                detailData = result?.data;

                renderDetail();

                document.getElementById('loadingState').classList.add('hidden');
                document.getElementById('detailContent').classList.remove('hidden');
            } catch (error) {
                document.getElementById('loadingState').innerHTML = `
                    <x-card>
                        <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                            ${escapeHtml(getErrorMessage(error))}
                        </div>
                    </x-card>
                `;
            }
        }

        function renderDetail() {
            const candidate = detailData?.candidate || {};
            const criteria = detailData?.criteria || [];
            const summary = detailData?.summary || {};

            setText('candidateName', candidate.full_name || '-');
            setText('studentNumber', candidate.student_number || '-');
            setText('registrationNumber', candidate.registration_number || '-');
            setText('studyProgram', candidate.study_program || '-');
            setText('scheduledAt', formatDateTime(candidate.scheduled_at));
            setText('locationText', candidate.location || '-');

            document.getElementById('candidateStatus').innerHTML = renderSimpleBadge(candidate.status || '-');
            document.getElementById('interviewStatus').innerHTML = renderSimpleBadge(candidate.interview_status || '-');
            document.getElementById('scoringStatus').innerHTML = summary.is_complete
                ? renderStatusBadge('Sudah Dinilai', 'green')
                : renderStatusBadge('Belum Dinilai Lengkap', 'yellow');

            const startLink = document.getElementById('startScoringLink');
            startLink.textContent = summary.is_complete ? 'Edit Penilaian' : 'Mulai Penilaian';

            renderCriteria(criteria);
        }

        function renderCriteria(criteria) {
            const target = document.getElementById('criteriaList');

            if (!criteria.length) {
                target.innerHTML = `
                    <div class="rounded-md border border-yellow-200 bg-yellow-50 px-4 py-3 text-sm font-semibold text-yellow-800">
                        Belum ada kriteria yang ditugaskan.
                    </div>
                `;
                return;
            }

            target.innerHTML = `
                <div class="space-y-3">
                    ${criteria.map(item => `
                        <div class="rounded-lg border border-slate-200 px-4 py-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-extrabold text-[#00288E]">
                                        ${escapeHtml(item.code || '-')}
                                    </p>
                                    <p class="mt-1 text-sm font-semibold text-slate-900">
                                        ${escapeHtml(item.name || '-')}
                                    </p>
                                </div>

                                ${item.score !== null
                                    ? renderStatusBadge('Terisi', 'green')
                                    : renderStatusBadge('Kosong', 'yellow')
                                }
                            </div>

                            <p class="mt-2 text-xs text-slate-500">
                                Rentang nilai: ${escapeHtml(item.min_score ?? 0)} - ${escapeHtml(item.max_score ?? 100)}
                            </p>
                        </div>
                    `).join('')}
                </div>
            `;
        }

        function renderSimpleBadge(text) {
            return `
                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                    ${escapeHtml(text)}
                </span>
            `;
        }

        function renderStatusBadge(text, color) {
            const classes = {
                green: 'bg-green-100 text-green-700',
                yellow: 'bg-yellow-100 text-yellow-700',
                red: 'bg-red-100 text-red-700',
            };

            return `
                <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold ${classes[color] || classes.green}">
                    ${escapeHtml(text)}
                </span>
            `;
        }

        function getPeriodId() {
            const params = new URLSearchParams(window.location.search);
            return params.get('period_id') || 1;
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
    </script>
@endpush