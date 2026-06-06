<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Petugas - Duta PNJ</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
    </style>
</head>

<body class="min-h-screen bg-[#F3F6FA] text-[#20232A]">
    <div class="min-h-screen flex flex-col">

        {{-- Header --}}
        <header class="h-16 bg-white border-b border-[#D9DEEA]">
            <div class="max-w-6xl mx-auto h-full px-6 flex items-center justify-between">
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 flex items-center justify-center text-[#00288E]">
                        <svg viewBox="0 0 32 32" class="w-8 h-8" fill="none" aria-hidden="true">
                            <path d="M16 4L28 10.5V21.5L16 28L4 21.5V10.5L16 4Z" stroke="currentColor" stroke-width="2.4" stroke-linejoin="round"/>
                            <path d="M9.5 12.5L16 16L22.5 12.5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16 16V23" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
                        </svg>
                    </div>

                    <span class="text-[24px] font-bold tracking-[-0.03em] text-[#00288E]">
                        Duta PNJ
                    </span>
                </a>

                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-[15px] font-semibold text-[#3F4250] hover:text-[#00288E] transition">
                    <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" aria-hidden="true">
                        <path d="M19 12H5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </header>

        {{-- Main --}}
        <main class="flex-1 flex items-center justify-center px-6 py-14">
            <section class="w-full max-w-275 bg-white border border-[#C9D1E3] rounded-[14px] shadow-[0_12px_30px_rgba(15,23,42,0.08)] overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-[0.92fr_1.08fr] min-h-156">

                    {{-- Left Panel --}}
                    <aside class="bg-[#00288E] text-white px-12 py-12 flex flex-col justify-between">
                        <div>
                            <h1 class="max-w-97.5 text-[40px] leading-[1.16] font-bold tracking-[-0.04em]">
                                Sistem Seleksi Duta PNJ
                            </h1>

                            <p class="mt-6 max-w-107.5 text-[19px] leading-8 text-white/85">
                                Selamat datang di portal administrasi dan penjurian.
                                Silakan masuk untuk mengelola pendaftar atau memberikan penilaian hasil seleksi.
                            </p>

                            <div class="mt-7 space-y-4">
                                <div class="rounded-lg border border-white/25 bg-white/8 px-5 py-4">
                                    <div class="flex gap-4">
                                        <div class="mt-1 text-white">
                                            <svg viewBox="0 0 24 24" class="w-6 h-6" fill="none" aria-hidden="true">
                                                <path d="M12 3L20 7V12C20 17 16.5 20.2 12 21C7.5 20.2 4 17 4 12V7L12 3Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                                <path d="M9.2 12L11.2 14L15.5 9.8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>

                                        <div>
                                            <h2 class="text-[15px] font-bold">
                                                Akses Administrator
                                            </h2>
                                            <p class="mt-1 text-[15px] leading-6 text-white/75">
                                                Kelola jadwal dan verifikasi dokumen.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-lg border border-white/25 bg-white/8 px-5 py-4">
                                    <div class="flex gap-4">
                                        <div class="mt-1 text-white">
                                            <svg viewBox="0 0 24 24" class="w-6 h-6" fill="none" aria-hidden="true">
                                                <path d="M5 19H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                <path d="M8 16L16.5 7.5C17.3 6.7 17.3 5.4 16.5 4.6C15.7 3.8 14.4 3.8 13.6 4.6L5 13.2V16H8Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                            </svg>
                                        </div>

                                        <div>
                                            <h2 class="text-[15px] font-bold">
                                                Akses Juri
                                            </h2>
                                            <p class="mt-1 text-[15px] leading-6 text-white/75">
                                                Berikan penilaian objektif pada setiap tahap.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-white/10 text-center text-[12px] text-white/45">
                            Politeknik Negeri Jakarta © {{ date('Y') }}
                        </div>
                    </aside>

                    {{-- Login Form --}}
                    <section class="px-10 py-12 lg:px-24 flex items-center">
                        <div class="w-full">
                            <div class="mb-9">
                                <h2 class="text-[26px] font-bold tracking-[-0.03em] text-[#20232A]">
                                    Login Petugas
                                </h2>
                                <p class="mt-1.5 text-[15px] text-[#555A66]">
                                    Masukkan kredensial Anda untuk mengakses dashboard.
                                </p>
                            </div>

                            @if ($errors->any())
                                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-[14px] text-red-700">
                                    <p class="font-semibold">Login belum berhasil.</p>
                                    <ul class="mt-1 list-disc pl-5">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (session('status'))
                                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-[14px] text-green-700">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form id="loginForm" method="POST" action="{{ route('login.store') }}" class="space-y-6">
                                @csrf

                                <div>
                                    <label for="email" class="block mb-2 text-[14px] font-bold text-[#20232A]">
                                        Alamat Email
                                    </label>

                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center text-[#6F7481]">
                                            <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" aria-hidden="true">
                                                <path d="M4 6H20V18H4V6Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                                <path d="M4 7L12 13L20 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>

                                        <input
                                            id="email"
                                            type="email"
                                            name="email"
                                            value="{{ old('email') }}"
                                            autocomplete="email"
                                            required
                                            autofocus
                                            placeholder="contoh@pnj.ac.id"
                                            class="w-full h-14.5 rounded-lg border border-[#C8CFDE] bg-[#F7F8FB] pl-12 pr-4 text-[16px] text-[#20232A] placeholder:text-[#767B87] outline-none transition focus:bg-white focus:border-[#00288E] focus:ring-2 focus:ring-[#00288E]/15"
                                        >
                                    </div>
                                </div>

                                <div>
                                    <div class="mb-2 flex items-center justify-between">
                                        <label for="password" class="block text-[14px] font-bold text-[#20232A]">
                                            Kata Sandi
                                        </label>

                                        <a href="#" class="text-[14px] font-bold text-[#00288E] hover:underline">
                                            Lupa Password?
                                        </a>
                                    </div>

                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center text-[#6F7481]">
                                            <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" aria-hidden="true">
                                                <path d="M7 10V8C7 5.2 9.2 3 12 3C14.8 3 17 5.2 17 8V10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                <path d="M6 10H18V20H6V10Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                                <path d="M12 14V16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                        </div>

                                        <input
                                            id="password"
                                            type="password"
                                            name="password"
                                            autocomplete="current-password"
                                            required
                                            placeholder="••••••••"
                                            class="w-full h-14.5 rounded-lg border border-[#C8CFDE] bg-[#F7F8FB] pl-12 pr-12 text-[16px] text-[#20232A] placeholder:text-[#767B87] outline-none transition focus:bg-white focus:border-[#00288E] focus:ring-2 focus:ring-[#00288E]/15"
                                        >

                                        <button
                                            type="button"
                                            onclick="togglePassword()"
                                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-[#6F7481] hover:text-[#00288E]"
                                            aria-label="Tampilkan kata sandi"
                                        >
                                            <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" aria-hidden="true">
                                                <path d="M2.5 12C4.5 7.8 7.8 5.7 12 5.7C16.2 5.7 19.5 7.8 21.5 12C19.5 16.2 16.2 18.3 12 18.3C7.8 18.3 4.5 16.2 2.5 12Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                                <path d="M12 15A3 3 0 1 0 12 9A3 3 0 0 0 12 15Z" stroke="currentColor" stroke-width="2"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <button
                                    id="loginButton"
                                    type="submit"
                                    class="w-full h-13.25 rounded-lg bg-[#00288E] text-white text-[15px] font-bold hover:bg-[#001F73] transition flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed"
                                >
                                    <span id="loginButtonText">Masuk Ke Dashboard</span>

                                    <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" aria-hidden="true">
                                        <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M13 6L19 12L13 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            </form>

                            <div class="mt-16 rounded-lg bg-[#F1F3F7] border-l-4 border-[#8A6400] px-5 py-4">
                                <div class="flex gap-4">
                                    <div class="pt-0.5 text-[#8A6400]">
                                        <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" aria-hidden="true">
                                            <path d="M12 8V12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                            <path d="M12 16H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                            <path d="M12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22Z" stroke="currentColor" stroke-width="2"/>
                                        </svg>
                                    </div>

                                    <p class="text-[15px] leading-6 text-[#555A66]">
                                        Akun untuk Juri dibuat secara terpusat oleh Admin. Jika Anda belum memiliki akun atau mengalami kendala login, silakan hubungi Sekretariat Panitia.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </section>
        </main>

        {{-- Footer --}}
        <footer class="bg-[#E9EDF4] border-t border-[#D5DCE8]">
            <div class="max-w-6xl mx-auto px-6 h-32 flex flex-col md:flex-row items-center justify-between gap-5">
                <div class="text-[26px] font-bold tracking-[-0.03em] text-[#00288E]">
                    Duta PNJ
                </div>

                <p class="text-[14px] text-[#454A57]">
                    © {{ date('Y') }} Panitia Pemilihan Duta PNJ. Seluruh Hak Cipta Dilindungi.
                </p>

                <div class="flex items-center gap-7 text-[14px] font-bold text-[#454A57]">
                    <a href="#" class="hover:text-[#00288E]">Kontak Panitia</a>
                    <a href="#" class="hover:text-[#00288E]">Pusat Bantuan</a>
                </div>
            </div>
        </footer>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');

            if (!input) {
                return;
            }

            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>