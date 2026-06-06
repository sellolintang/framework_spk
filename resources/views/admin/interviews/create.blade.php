@extends('layouts.admin')

@section('content')
    <section class="mb-7">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-[34px] font-extrabold leading-none tracking-tight text-[#00288E]">
                    Generate Jadwal Wawancara
                </h1>

                <p class="mt-2 text-sm font-medium text-slate-500">
                    Buat jadwal wawancara otomatis untuk semua calon valid yang belum dijadwalkan.
                </p>
            </div>

            <x-button href="{{ route('admin.interviews.index') }}" variant="secondary">
                Kembali
            </x-button>
        </div>
    </section>

    <div id="pageAlert" class="mb-5 hidden rounded-md border px-4 py-3 text-sm"></div>

    <x-card>
        <form id="generateForm" class="space-y-5">
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <x-period-select width="w-40" height="h-11" />


                    <p class="mt-1.5 text-xs text-slate-500">
                        Sistem akan mengambil calon valid pada periode ini.
                    </p>
                </div>

                <div>
                    <label for="interview_date" class="mb-2 block text-sm font-bold text-slate-700">
                        Tanggal Wawancara <span class="text-red-600">*</span>
                    </label>

                    <input
                        id="interview_date"
                        type="date"
                        required
                        class="h-11 w-full rounded-md border border-slate-300 bg-white px-4 text-sm outline-none focus:border-[#00288E] focus:ring-2 focus:ring-blue-100"
                    >

                    <p class="mt-1.5 text-xs text-slate-500">
                        Biasanya wawancara dilakukan satu hari penuh.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="start_time" class="mb-2 block text-sm font-bold text-slate-700">
                        Jam Mulai <span class="text-red-600">*</span>
                    </label>

                    <input
                        id="start_time"
                        type="time"
                        value="08:00"
                        required
                        class="h-11 w-full rounded-md border border-slate-300 bg-white px-4 text-sm outline-none focus:border-[#00288E] focus:ring-2 focus:ring-blue-100"
                    >
                </div>

                <div>
                    <label for="duration_minutes" class="mb-2 block text-sm font-bold text-slate-700">
                        Durasi per Calon <span class="text-red-600">*</span>
                    </label>

                    <input
                        id="duration_minutes"
                        type="number"
                        min="5"
                        max="120"
                        value="15"
                        required
                        class="h-11 w-full rounded-md border border-slate-300 bg-white px-4 text-sm outline-none focus:border-[#00288E] focus:ring-2 focus:ring-blue-100"
                    >

                    <p class="mt-1.5 text-xs text-slate-500">
                        Contoh: 15 menit per calon.
                    </p>
                </div>
            </div>

            <div>
                <label for="location" class="mb-2 block text-sm font-bold text-slate-700">
                    Lokasi
                </label>

                <input
                    id="location"
                    type="text"
                    placeholder="Contoh: Ruang Aula PNJ"
                    class="h-11 w-full rounded-md border border-slate-300 bg-white px-4 text-sm outline-none focus:border-[#00288E] focus:ring-2 focus:ring-blue-100"
                >
            </div>

            <div class="rounded-md border border-blue-200 bg-blue-50 px-4 py-3">
                <p class="text-sm font-semibold text-blue-800">
                    Sistem akan membuat jadwal otomatis berdasarkan urutan nomor pendaftaran. Calon yang sudah memiliki jadwal tidak akan dibuatkan ulang.
                </p>
            </div>

            <div class="flex justify-end gap-2 border-t border-slate-200 pt-5">
                <x-button href="{{ route('admin.interviews.index') }}" variant="secondary">
                    Batal
                </x-button>

                <x-button type="submit">
                    Generate Jadwal
                </x-button>
            </div>
        </form>
    </x-card>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', async function () {
            const params = new URLSearchParams(window.location.search);
            const periodId = params.get('period_id') || '1';

            await loadPeriodOptions('periodIdInput', periodId);

            document.getElementById('generateForm')?.addEventListener('submit', submitGenerateSchedule);
        });

        async function submitGenerateSchedule(event) {
            event.preventDefault();

            const payload = {
                period_id: Number(document.getElementById('periodIdInput').value),
                interview_date: document.getElementById('interview_date').value,
                start_time: document.getElementById('start_time').value,
                duration_minutes: Number(document.getElementById('duration_minutes').value),
                location: document.getElementById('location').value || null,
            };

            try {
                const result = await DutaAdmin.request('/interviews/generate', {
                    method: 'POST',
                    body: JSON.stringify(payload),
                });

                const generatedCount = result?.data?.generated_count || 0;

                showAlert('success', `${result.message || 'Jadwal berhasil dibuat.'} Total dibuat: ${generatedCount}.`);

                setTimeout(() => {
                    window.location.href = "{{ route('admin.interviews.index') }}";
                }, 900);
            } catch (error) {
                showAlert('danger', getErrorMessage(error));
            }
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
        }

        function getErrorMessage(error) {
            return error?.message || 'Terjadi kesalahan.';
        }
    </script>
@endpush