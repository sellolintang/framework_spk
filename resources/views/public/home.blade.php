<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemilihan Duta Kampus</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-800 antialiased">

    {{-- NAVBAR --}}
    <header class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-8">
            <a href="#" class="text-xl font-bold tracking-tight text-blue-900">
                Duta Kampus
            </a>

            <div class="hidden items-center gap-10 text-sm font-medium text-slate-600 md:flex">
                <a href="#beranda" class="border-b-2 border-blue-900 pb-1 text-blue-900">Beranda</a>
                <a href="#persyaratan" class="hover:text-blue-900">Persyaratan</a>
                <a href="#jadwal" class="hover:text-blue-900">Jadwal</a>
                <a href="#pengumuman" class="hover:text-blue-900">Pengumuman</a>
            </div>

            <div class="flex items-center gap-3">
                <a href="#login" class="hidden text-sm font-semibold text-blue-900 sm:inline">
                    Login
                </a>

                <a href="#daftar"
                   class="rounded-lg bg-blue-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">
                    Daftar
                </a>
            </div>
        </nav>
    </header>

    {{-- HERO --}}
    <section id="beranda" class="bg-gradient-to-br from-slate-50 to-slate-100">
        <div class="mx-auto grid max-w-7xl items-center gap-12 px-6 py-20 lg:grid-cols-2 lg:px-8 lg:py-24">
            <div>
                <span class="inline-flex rounded-full bg-yellow-300 px-4 py-1.5 text-sm font-semibold text-yellow-900">
                    Pendaftaran 2026 Dibuka
                </span>

                <h1 class="mt-6 max-w-2xl text-4xl font-extrabold leading-tight tracking-tight text-blue-900 md:text-5xl">
                    Wujudkan Representasi Terbaik dalam Pemilihan Duta Kampus
                </h1>

                <p class="mt-6 max-w-xl text-base leading-8 text-slate-600 md:text-lg">
                    Jadilah representasi unggul kampus. Kembangkan potensi kepemimpinan,
                    komunikasi, wawasan, dan kontribusi nyata bagi almamater tercinta.
                </p>

                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="#daftar"
                       class="rounded-lg bg-blue-900 px-7 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">
                        Daftar Sekarang
                    </a>
                </div>
            </div>

            <div class="relative">
                <div class="absolute -inset-4 rounded-3xl bg-blue-100 blur-2xl"></div>

                <div class="relative overflow-hidden rounded-3xl bg-white shadow-xl ring-1 ring-slate-200">
                    <img src="{{ asset('images/duta-hero.jpg') }}"
                         alt="Finalis Duta Kampus"
                         class="h-[260px] w-full object-cover md:h-[360px]">
                </div>
            </div>
        </div>
    </section>

    {{-- ALUR SELEKSI --}}
    <section class="bg-slate-50 py-16">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-blue-900">Alur Seleksi</h2>
                <div class="mx-auto mt-3 h-1 w-16 rounded-full bg-yellow-600"></div>
            </div>

            <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-5">
                @php
                    $steps = [
                        ['Tahap 1', 'Pendaftaran', 'M12 4v16m8-8H4'],
                        ['Tahap 2', 'Verifikasi Data', 'M9 12l2 2 4-4m5 2a8 8 0 11-16 0 8 8 0 0116 0z'],
                        ['Tahap 3', 'Jadwal Wawancara', 'M8 7V3m8 4V3M5 11h14M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                        ['Tahap 4', 'Penilaian Juri', 'M12 8c-1.657 0-3 1.343-3 3v1H7a2 2 0 00-2 2v3h14v-3a2 2 0 00-2-2h-2v-1c0-1.657-1.343-3-3-3z'],
                        ['Tahap 5', 'Pengumuman', 'M11 5.882V19.24a1 1 0 001.447.894l6.106-3.053A1 1 0 0019 16.187V8.813a1 1 0 00-.447-.832l-6.106-3.053A1 1 0 0011 5.882z'],
                    ];
                @endphp

                @foreach ($steps as $step)
                    <div class="rounded-xl border border-slate-200 bg-white p-6 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-indigo-100 text-blue-900">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $step[2] }}"/>
                            </svg>
                        </div>

                        <p class="mt-5 text-xs font-bold uppercase tracking-widest text-yellow-700">
                            {{ $step[0] }}
                        </p>

                        <h3 class="mt-1 text-sm font-bold text-slate-900">
                            {{ $step[1] }}
                        </h3>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- PERSYARATAN --}}
    <section id="persyaratan" class="bg-slate-100 py-16">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="flex flex-col justify-between gap-4 md:flex-row md:items-end">
                <div>
                    <h2 class="text-2xl font-bold text-blue-900">Persyaratan Pendaftaran</h2>
                    <p class="mt-2 text-sm text-slate-600">
                        Pastikan Anda memenuhi kriteria berikut sebelum mendaftar.
                    </p>
                </div>

                <a href="#panduan" class="text-sm font-semibold text-blue-900 hover:underline">
                    Syarat Lengkap (PDF)
                </a>
            </div>

            <div class="mt-10 grid gap-6 md:grid-cols-2">
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex gap-4">
                        <div class="text-blue-900">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 14l9-5-9-5-9 5 9 5z"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 14l6.16-3.422A12.083 12.083 0 0118 20.944 11.952 11.952 0 0012 22a11.952 11.952 0 00-6-1.056 12.083 12.083 0 01-.16-10.366L12 14z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900">Status Akademik</h3>
                            <p class="mt-1 text-sm leading-6 text-slate-600">
                                Mahasiswa aktif kampus minimal semester 2 dan maksimal semester 4.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex gap-4">
                        <div class="text-blue-900">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900">Indeks Prestasi Kumulatif</h3>
                            <p class="mt-1 text-sm leading-6 text-slate-600">
                                Memiliki IPK minimal 3.25 dan dapat dibuktikan melalui dokumen akademik.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex gap-4">
                        <div class="text-blue-900">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900">Kemampuan Berbahasa</h3>
                            <p class="mt-1 text-sm leading-6 text-slate-600">
                                Mampu berkomunikasi dengan baik dalam Bahasa Indonesia dan Bahasa Inggris.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex gap-4">
                        <div class="text-blue-900">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m10-4a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900">Etika dan Kepribadian</h3>
                            <p class="mt-1 text-sm leading-6 text-slate-600">
                                Memiliki kepribadian menarik, berwawasan luas, dan berkelakuan baik.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- JADWAL --}}
    <section id="jadwal" class="bg-slate-50 py-16">
        <div class="mx-auto grid max-w-7xl gap-10 px-6 lg:grid-cols-3 lg:px-8">
            <div>
                <h2 class="text-2xl font-bold text-blue-900">Jadwal Penting</h2>
                <p class="mt-3 text-sm leading-6 text-slate-600">
                    Simpan tanggal-tanggal penting agar Anda tidak melewatkan tahapan seleksi.
                </p>

                <div class="mt-8 rounded-xl border border-yellow-200 bg-yellow-50 p-5 text-yellow-900">
                    <div class="flex gap-3">
                        <svg class="mt-0.5 h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 110-20 10 10 0 010 20z"/>
                        </svg>
                        <div>
                            <h3 class="font-bold">Catatan</h3>
                            <p class="mt-1 text-sm leading-6">
                                Perubahan jadwal sewaktu-waktu akan diumumkan melalui laman ini dan media sosial resmi.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-5 lg:col-span-2">
                @php
                    $schedules = [
                        ['MEI', '15-30', 'Pendaftaran Online', 'Pengisian formulir dan unggah dokumen administrasi.', true],
                        ['JUNI', '05', 'Pengumuman Lolos Berkas', 'Hasil verifikasi administrasi pendaftar.', false],
                        ['JUNI', '10', 'Wawancara dan Uji Bakat', 'Seleksi mendalam oleh juri kampus.', false],
                        ['JULI', '01', 'Malam Inagurasi', 'Penetapan Duta Kampus terpilih.', false],
                    ];
                @endphp

                @foreach ($schedules as $schedule)
                    <div class="flex gap-5 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex h-16 w-20 shrink-0 flex-col items-center justify-center rounded-lg {{ $schedule[4] ? 'bg-blue-900 text-white' : 'bg-slate-200 text-slate-700' }}">
                            <span class="text-xs font-semibold uppercase">{{ $schedule[0] }}</span>
                            <span class="text-xl font-bold">{{ $schedule[1] }}</span>
                        </div>

                        <div>
                            <h3 class="font-bold text-slate-900">{{ $schedule[2] }}</h3>
                            <p class="mt-1 text-sm leading-6 text-slate-600">{{ $schedule[3] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section id="pengumuman" class="bg-slate-100 py-16">
        <div class="mx-auto max-w-3xl px-6">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-blue-900">Pertanyaan Sering Diajukan</h2>
                <p class="mt-3 text-sm text-slate-600">
                    Temukan jawaban cepat atas kendala atau pertanyaan Anda.
                </p>
            </div>

            <div class="mt-10 space-y-4">
                <details class="group rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <summary class="flex cursor-pointer list-none items-center justify-between font-semibold text-slate-900">
                        Apakah pendaftaran dipungut biaya?
                        <span class="transition group-open:rotate-180">⌄</span>
                    </summary>
                    <p class="mt-4 text-sm leading-6 text-slate-600">
                        Tidak. Pendaftaran Pemilihan Duta Kampus tidak dipungut biaya.
                    </p>
                </details>

                <details class="group rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <summary class="flex cursor-pointer list-none items-center justify-between font-semibold text-slate-900">
                        Apakah mahasiswa tingkat akhir boleh mendaftar?
                        <span class="transition group-open:rotate-180">⌄</span>
                    </summary>
                    <p class="mt-4 text-sm leading-6 text-slate-600">
                        Ketentuan semester mengikuti persyaratan resmi panitia pada tahun berjalan.
                    </p>
                </details>

                <details class="group rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <summary class="flex cursor-pointer list-none items-center justify-between font-semibold text-slate-900">
                        Apa saja dokumen yang harus disiapkan?
                        <span class="transition group-open:rotate-180">⌄</span>
                    </summary>
                    <p class="mt-4 text-sm leading-6 text-slate-600">
                        Calon perlu menyiapkan data diri, foto, CV, serta dokumen lain sesuai panduan pendaftaran.
                    </p>
                </details>
            </div>
        </div>
    </section>

    {{-- KONTAK --}}
    <section class="bg-slate-50 py-16">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid gap-10 rounded-2xl bg-blue-900 p-8 text-white shadow-xl md:grid-cols-2 md:p-12">
                <div class="flex flex-col justify-center">
                    <h2 class="text-2xl font-bold md:text-3xl">Masih memiliki pertanyaan?</h2>
                    <p class="mt-5 max-w-xl text-blue-100">
                        Tim panitia siap membantu Anda memberikan informasi lebih lanjut terkait proses seleksi.
                    </p>

                    <div class="mt-8 space-y-4 text-sm">
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/10">
                                ✉
                            </span>
                            <span>duta@kampus.ac.id</span>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/10">
                                ☎
                            </span>
                            <span>+62 812-3456-7890 (Sekretariat)</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl bg-white p-6 text-slate-900">
                    <h3 class="font-bold">Kirim Pesan Cepat</h3>

                    <form class="mt-5 space-y-4">
                        <input type="text"
                               placeholder="Nama Lengkap"
                               class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-900 focus:ring-2 focus:ring-blue-100">

                        <input type="email"
                               placeholder="Email Mahasiswa"
                               class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-900 focus:ring-2 focus:ring-blue-100">

                        <textarea rows="4"
                                  placeholder="Pesan Anda"
                                  class="w-full resize-none rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-900 focus:ring-2 focus:ring-blue-100"></textarea>

                        <button type="button"
                                class="w-full rounded-lg bg-blue-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-800">
                            Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="border-t border-slate-200 bg-slate-100">
        <div class="mx-auto grid max-w-7xl gap-10 px-6 py-12 md:grid-cols-3 lg:px-8">
            <div>
                <h2 class="text-xl font-bold text-blue-900">Duta Kampus</h2>
                <p class="mt-4 max-w-sm text-sm leading-6 text-slate-600">
                    Menjadi ikon keunggulan akademik dan non-akademik di lingkungan kampus.
                </p>

                <div class="mt-6 flex gap-3">
                    <a href="#" class="flex h-9 w-9 items-center justify-center rounded-full bg-white text-blue-900 shadow-sm">
                        🌐
                    </a>
                    <a href="#" class="flex h-9 w-9 items-center justify-center rounded-full bg-white text-blue-900 shadow-sm">
                        ↗
                    </a>
                </div>
            </div>

            <div class="md:col-start-3 grid grid-cols-2 gap-8 text-sm">
                <div>
                    <h3 class="font-bold text-yellow-700">Menu Seleksi</h3>
                    <ul class="mt-4 space-y-3 text-slate-600">
                        <li><a href="#" class="hover:text-blue-900">Kontak Panitia</a></li>
                        <li><a href="#" class="hover:text-blue-900">Panduan Seleksi</a></li>
                        <li><a href="#" class="hover:text-blue-900">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-blue-900">Pusat Bantuan</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold text-yellow-700">Tautan Terkait</h3>
                    <ul class="mt-4 space-y-3 text-slate-600">
                        <li><a href="#" class="hover:text-blue-900">Website Kampus</a></li>
                        <li><a href="#" class="hover:text-blue-900">Portal Mahasiswa</a></li>
                        <li><a href="#" class="hover:text-blue-900">Katalog UKM</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-200 px-6 py-5">
            <p class="mx-auto max-w-7xl text-sm text-slate-500">
                © {{ date('Y') }} Panitia Pemilihan Duta Kampus. Seluruh Hak Cipta Dilindungi.
            </p>
        </div>
    </footer>

</body>
</html>
