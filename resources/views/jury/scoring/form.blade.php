@extends('layouts.jury')

@section('content')
    <section class="mb-7">
        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
            <div>
                <div class="mb-3 flex items-center gap-2 text-sm font-semibold text-slate-500">
                    <a href="{{ route('jury.scoring.index') }}" class="hover:text-[#00288E]">Penilaian Peserta</a>
                    <span>/</span>
                    <span class="text-[#00288E]">Form Penilaian</span>
                </div>

                <h1 class="text-[34px] font-extrabold leading-tight tracking-tight text-[#00288E]">
                    Form Penilaian Peserta
                </h1>

                <p class="mt-2 text-sm font-medium text-slate-500">
                    Berikan nilai sesuai kriteria yang ditugaskan kepada kamu.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a
                    id="detailLink"
                    href="#"
                    class="rounded-md border border-slate-300 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50"
                >
                    Detail Peserta
                </a>

                <a
                    id="backToListLink"
                    href="{{ route('jury.scoring.index') }}"
                    class="rounded-md border border-slate-300 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50"
                >
                    Kembali
                </a>
            </div>
        </div>
    </section>

    <div id="pageAlert" class="mb-5 hidden rounded-md border px-4 py-3 text-sm"></div>

    <section id="loadingState">
        <x-card>
            <div class="py-10 text-center text-sm text-slate-500">
                Memuat form penilaian...
            </div>
        </x-card>
    </section>

    <section id="formContent" class="hidden space-y-6">
        <x-card>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-4">
                <div>
                    <p class="text-xs font-bold uppercase text-slate-500">Nama Peserta</p>
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
            </div>

            <div class="mt-5 grid grid-cols-1 gap-5 md:grid-cols-3">
                <div>
                    <p class="text-xs font-bold uppercase text-slate-500">Jadwal Wawancara</p>
                    <p id="scheduledAt" class="mt-1 text-base font-semibold text-slate-700">-</p>
                </div>

                <div>
                    <p class="text-xs font-bold uppercase text-slate-500">Lokasi</p>
                    <p id="locationText" class="mt-1 text-base font-semibold text-slate-700">-</p>
                </div>

                <div>
                    <p class="text-xs font-bold uppercase text-slate-500">Progress Penilaian</p>
                    <div id="scoreProgress" class="mt-2">-</div>
                </div>
            </div>
        </x-card>

        <x-card padding="p-0">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-lg font-extrabold text-slate-900">
                    Kriteria Penilaian
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Semua nilai wajib diisi sebelum disimpan.
                </p>
            </div>

            <form id="scoreForm">
                <div id="criteriaFormList" class="divide-y divide-slate-200">
                    <div class="px-5 py-6 text-sm text-slate-500">
                        Memuat kriteria...
                    </div>
                </div>

                <div class="border-t border-slate-200 px-5 py-4">
                    <div class="rounded-md border border-blue-200 bg-blue-50 px-4 py-3">
                        <p class="text-sm font-semibold text-blue-800">
                            Nilai yang sudah disimpan akan masuk ke proses perhitungan hasil akhir. Pastikan nilai sudah benar sebelum menyimpan.
                        </p>
                    </div>
                </div>

                <div class="flex justify-end gap-2 border-t border-slate-200 bg-slate-50 px-5 py-4">
                    <a
                        id="cancelLink"
                        href="{{ route('jury.scoring.index') }}"
                        class="rounded-md border border-slate-300 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="rounded-md bg-[#00288E] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#001F73]"
                    >
                        Simpan Nilai
                    </button>
                </div>
            </form>
        </x-card>
    </section>
@endsection

@push('scripts')
    <script>
        const candidateId = @json($candidateId);
        let scoringData = null;

        document.addEventListener('DOMContentLoaded', function () {
            const periodId = getPeriodId();

            document.getElementById('backToListLink').href = `/jury/scoring?period_id=${periodId}`;
            document.getElementById('cancelLink').href = `/jury/scoring?period_id=${periodId}`;
            document.getElementById('detailLink').href = `/jury/scoring/${candidateId}?period_id=${periodId}`;

            loadScoringForm();

            document.getElementById('scoreForm')?.addEventListener('submit', submitScores);
        });

        async function loadScoringForm() {
            try {
                const params = new URLSearchParams({
                    period_id: getPeriodId(),
                });

                const result = await DutaJury.request(`/jury/scoring-candidates/${candidateId}?${params.toString()}`);

                scoringData = result?.data;

                renderCandidateInfo();
                renderCriteriaForm();

                document.getElementById('loadingState').classList.add('hidden');
                document.getElementById('formContent').classList.remove('hidden');
            } catch (error) {
                document.getElementById('loadingState').innerHTML = `
                    <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
                        ${escapeHtml(getErrorMessage(error))}
                    </div>
                `;
            }
        }

        function renderCandidateInfo() {
            const candidate = scoringData?.candidate || {};
            const summary = scoringData?.summary || {};

            setText('candidateName', candidate.full_name || '-');
            setText('studentNumber', candidate.student_number || '-');
            setText('registrationNumber', candidate.registration_number || '-');
            setText('studyProgram', candidate.study_program || '-');
            setText('scheduledAt', formatDateTime(candidate.scheduled_at));
            setText('locationText', candidate.location || '-');

            document.getElementById('scoreProgress').innerHTML = `
                ${renderProgress(summary.completion_percentage || 0)}
                <p class="mt-1 text-xs text-slate-500">
                    ${summary.filled_count || 0}/${summary.criteria_count || 0} kriteria
                </p>
            `;
        }

        function renderCriteriaForm() {
            const target = document.getElementById('criteriaFormList');
            const criteria = scoringData?.criteria || [];

            if (!criteria.length) {
                target.innerHTML = `
                    <div class="px-5 py-6">
                        <div class="rounded-md border border-yellow-200 bg-yellow-50 px-4 py-3 text-sm font-semibold text-yellow-800">
                            Tidak ada kriteria yang ditugaskan kepada kamu.
                        </div>
                    </div>
                `;
                return;
            }

            target.innerHTML = criteria.map(item => {
                const minScore = Number(item.min_score ?? 0);
                const maxScore = Number(item.max_score ?? 100);
                const value = item.score ?? '';

                return `
                    <div class="px-5 py-5">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div class="md:col-span-2">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="rounded-md bg-blue-100 px-2 py-1 text-xs font-extrabold text-[#00288E]">
                                        ${escapeHtml(item.code || '-')}
                                    </span>

                                    <span class="rounded-md bg-green-100 px-2 py-1 text-xs font-bold text-green-700">
                                        ${escapeHtml(item.type || 'benefit')}
                                    </span>
                                </div>

                                <p class="mt-2 text-sm font-extrabold text-slate-900">
                                    ${escapeHtml(item.name || '-')}
                                </p>

                                <p class="mt-1 text-xs text-slate-500">
                                    Rentang nilai: ${formatNumber(minScore)} sampai ${formatNumber(maxScore)}
                                </p>
                            </div>

                            <div>
                                <label class="mb-1 block text-xs font-bold text-slate-600">
                                    Nilai
                                </label>

                                <input
                                    type="number"
                                    class="score-input h-11 w-full rounded-md border border-slate-300 bg-white px-4 text-sm outline-none focus:border-[#00288E] focus:ring-2 focus:ring-blue-100"
                                    data-criterion-id="${item.id}"
                                    data-code="${escapeHtml(item.code || '-')}"
                                    min="${minScore}"
                                    max="${maxScore}"
                                    step="0.01"
                                    value="${escapeHtml(value)}"
                                    required
                                >
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        async function submitScores(event) {
            event.preventDefault();

            const inputs = [...document.querySelectorAll('.score-input')];

            if (!inputs.length) {
                showAlert('danger', 'Tidak ada kriteria yang bisa dinilai.');
                return;
            }

            const scores = [];

            for (const input of inputs) {
                const criterionId = Number(input.dataset.criterionId);
                const code = input.dataset.code;
                const value = Number(input.value);
                const min = Number(input.min);
                const max = Number(input.max);

                if (Number.isNaN(value)) {
                    showAlert('danger', `Nilai ${code} wajib diisi.`);
                    return;
                }

                if (value < min || value > max) {
                    showAlert('danger', `Nilai ${code} harus berada pada rentang ${formatNumber(min)} sampai ${formatNumber(max)}.`);
                    return;
                }

                scores.push({
                    criterion_id: criterionId,
                    score: value,
                });
            }

            try {
                const result = await DutaJury.request(`/jury/scoring-candidates/${candidateId}/scores`, {
                    method: 'POST',
                    body: JSON.stringify({
                        period_id: Number(getPeriodId()),
                        scores: scores,
                    }),
                });

                showAlert('success', result.message || 'Nilai peserta berhasil disimpan.');

                setTimeout(() => {
                    window.location.href = `/jury/scoring?period_id=${getPeriodId()}`;
                }, 700);
            } catch (error) {
                showAlert('danger', getErrorMessage(error));
            }
        }

        function renderProgress(value) {
            const percent = Number(value || 0);

            return `
                <div class="h-2 w-36 overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full rounded-full bg-[#00288E]" style="width: ${Math.min(percent, 100)}%"></div>
                </div>
                <p class="mt-1 text-xs font-bold text-slate-700">${formatNumber(percent)}%</p>
            `;
        }

        function getPeriodId() {
            const params = new URLSearchParams(window.location.search);
            return params.get('period_id') || 1;
        }

        function formatNumber(value) {
            return new Intl.NumberFormat('id-ID', {
                maximumFractionDigits: 2,
            }).format(Number(value || 0));
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