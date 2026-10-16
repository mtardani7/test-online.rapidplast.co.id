@extends('layouts.app')

@section('title', 'Cara Mengerjakan Tes | Sistem Tes Online')

@section('content')
<div class="min-h-screen overflow-hidden bg-white text-slate-800">
    <header class="relative z-10 border-b border-slate-200/70 bg-white/90">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8" aria-label="Navigasi utama">
            <a href="{{ route('landing') }}" class="flex items-center gap-3" aria-label="Sistem Tes Online - beranda panduan">
                <img src="{{ asset('images/logo3.png') }}" alt="RapidPlast" class="h-10 w-auto object-contain sm:h-12">
                <span class="hidden border-l border-slate-200 pl-3 text-sm font-bold leading-tight text-slate-800 sm:block">
                    Sistem<br><span class="text-rose-600">Tes Online</span>
                </span>
            </a>
            <div class="flex items-center gap-2 sm:gap-3">
                @auth
                    @if(auth()->user()->hasAnyRole(['admin', 'super_admin']))
                        <a href="{{ route('admin.dashboard') }}" class="rounded-full px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 sm:px-4">Dashboard</a>
                    @else
                        <a href="{{ route('user.tests') }}" class="rounded-full px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 sm:px-4">Daftar Tes</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="rounded-full px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 sm:px-4">Masuk</a>
                    <a href="{{ route('register') }}" class="rounded-full bg-rose-600 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-rose-600/20 transition hover:-translate-y-0.5 hover:bg-rose-700 sm:px-5">Daftar</a>
                @endauth
            </div>
        </nav>
    </header>

    <main>
        <section class="relative isolate overflow-hidden bg-slate-950">
            <div class="absolute inset-0 -z-10 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-rose-900/60 via-slate-950 to-slate-950"></div>
            <div class="absolute -right-24 top-12 -z-10 h-72 w-72 rounded-full bg-rose-500/20 blur-3xl sm:h-96 sm:w-96"></div>
            <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 py-16 sm:px-6 sm:py-20 lg:grid-cols-[1.05fr_0.95fr] lg:px-8 lg:py-24">
                <div class="max-w-2xl">
                    <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-4 py-2 text-sm font-semibold text-rose-100">
                        <span class="h-2 w-2 rounded-full bg-rose-400"></span>
                        Panduan singkat untuk peserta
                    </div>
                    <h1 class="text-4xl font-black leading-tight tracking-tight text-white sm:text-5xl lg:text-6xl">
                        Cara mengerjakan tes,<br class="hidden sm:block">
                        <span class="text-rose-300">langkah demi langkah.</span>
                    </h1>
                    <p class="mt-6 max-w-xl text-base leading-7 text-slate-300 sm:text-lg sm:leading-8">
                        Baru pertama kali ikut tes online? Tenang. Ikuti panduan ini supaya kamu tahu harus mulai dari mana, cara menjawab, dan kapan mengirim jawaban.
                    </p>
                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        @auth
                            @if(auth()->user()->hasAnyRole(['admin', 'super_admin']))
                                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-rose-600 px-6 py-3.5 font-bold text-white shadow-xl shadow-rose-950/30 transition hover:-translate-y-0.5 hover:bg-rose-500">
                                    Buka dashboard <span aria-hidden="true">→</span>
                                </a>
                            @else
                                <a href="{{ route('user.tests') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-rose-600 px-6 py-3.5 font-bold text-white shadow-xl shadow-rose-950/30 transition hover:-translate-y-0.5 hover:bg-rose-500">
                                    Lihat daftar tes <span aria-hidden="true">→</span>
                                </a>
                            @endif
                        @else
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-rose-600 px-6 py-3.5 font-bold text-white shadow-xl shadow-rose-950/30 transition hover:-translate-y-0.5 hover:bg-rose-500">
                                Mulai dengan daftar <span aria-hidden="true">→</span>
                            </a>
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-xl border border-white/20 px-6 py-3.5 font-bold text-white transition hover:bg-white/10">Saya sudah punya akun</a>
                        @endauth
                    </div>
                    <p class="mt-5 text-sm text-slate-400">Tidak perlu pengalaman khusus. Baca instruksi di layar dan kerjakan dengan tenang.</p>
                </div>

                <div class="relative mx-auto w-full max-w-lg lg:ml-auto">
                    <div class="absolute -inset-4 rounded-[2rem] bg-gradient-to-br from-rose-500/20 to-indigo-400/10 blur-xl"></div>
                    <div class="relative rounded-[1.75rem] border border-white/10 bg-white p-5 shadow-2xl shadow-black/30 sm:p-7">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.18em] text-rose-600">Contoh tampilan</p>
                                <h2 class="mt-1 text-lg font-extrabold text-slate-900">Pertanyaan 3 dari 10</h2>
                            </div>
                            <div class="rounded-xl bg-rose-50 px-3 py-2 text-right">
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-rose-500">Sisa waktu</span>
                                <span class="font-mono text-lg font-black text-rose-700">18:42</span>
                            </div>
                        </div>
                        <div class="pt-5">
                            <p class="text-sm font-semibold leading-6 text-slate-800">Pilih jawaban yang paling sesuai dengan pertanyaan.</p>
                            <div class="mt-4 space-y-3">
                                <div class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-500">
                                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-xs font-bold">A</span> Pilihan jawaban pertama
                                </div>
                                <div class="flex items-center gap-3 rounded-xl border-2 border-rose-500 bg-rose-50 px-4 py-3 text-sm font-semibold text-slate-800">
                                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-rose-600 text-xs font-bold text-white">B</span> Pilihan yang kamu pilih
                                    <span class="ml-auto text-rose-600" aria-hidden="true">✓</span>
                                </div>
                                <div class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-500">
                                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 text-xs font-bold">C</span> Pilihan jawaban lainnya
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                            <span class="text-xs font-medium text-slate-400">Jawaban dipilih? Lanjut ke soal berikutnya.</span>
                            <span class="rounded-lg bg-slate-900 px-4 py-2 text-xs font-bold text-white">Lanjut →</span>
                        </div>
                    </div>
                    <div class="absolute -bottom-5 -left-3 hidden items-center gap-3 rounded-2xl border border-slate-100 bg-white p-3 shadow-xl sm:flex">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-lg text-emerald-700" aria-hidden="true">✓</span>
                        <span class="text-sm font-bold text-slate-800">Jawab, cek, lalu kirim</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-20 lg:px-8" aria-labelledby="steps-title">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-rose-600">Alurnya sederhana</p>
                <h2 id="steps-title" class="mt-3 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Enam langkah sampai selesai</h2>
                <p class="mt-4 text-base leading-7 text-slate-600">Ikuti urutannya. Kamu bisa melihat petunjuk di layar kapan saja sebelum mulai mengerjakan.</p>
            </div>

            <div class="mt-12 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @php
                    $steps = [
                        ['01', 'Masuk atau buat akun', 'Daftar menggunakan data diri yang diminta. Kalau sudah punya akun, langsung masuk.'],
                        ['02', 'Pilih tes yang tersedia', 'Buka daftar tes, lalu pilih tes yang ingin kamu kerjakan. Baca nama dan keterangannya.'],
                        ['03', 'Pilih bagian tes', 'Tes bisa memiliki beberapa jenis dan bagian. Buka bagian yang ingin dikerjakan.'],
                        ['04', 'Baca petunjuk dan waktu', 'Perhatikan aturan, jumlah soal, dan durasi. Waktu mulai berjalan saat kamu membuka bagian tes.'],
                        ['05', 'Kerjakan soal satu per satu', 'Pilih jawaban, lalu tekan tombol lanjut. Gunakan nomor soal untuk melihat soal lain jika tersedia.'],
                        ['06', 'Periksa lalu kirim', 'Sebelum mengirim, cek kembali jawaban dan soal yang belum dijawab. Setelah dikirim, bagian tes selesai.'],
                    ];
                @endphp
                @foreach($steps as [$number, $title, $description])
                    <article class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-rose-200 hover:shadow-xl hover:shadow-rose-900/5 sm:p-7">
                        <div class="flex items-start justify-between gap-4">
                            <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-50 text-sm font-black text-rose-700 transition group-hover:bg-rose-600 group-hover:text-white">{{ $number }}</span>
                            <span class="mt-2 h-px flex-1 bg-gradient-to-r from-rose-200 to-transparent"></span>
                        </div>
                        <h3 class="mt-6 text-lg font-extrabold text-slate-900">{{ $title }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $description }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="bg-slate-50 py-16 sm:py-20" aria-labelledby="tips-title">
            <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[0.8fr_1.2fr] lg:items-center lg:px-8">
                <div>
                    <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-rose-600">Biar lebih siap</p>
                    <h2 id="tips-title" class="mt-3 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Sebelum menekan “Mulai”</h2>
                    <p class="mt-4 max-w-lg leading-7 text-slate-600">Persiapan kecil bisa bikin kamu lebih fokus. Pastikan semua sudah siap sebelum waktu tes berjalan.</p>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
                        <span class="text-2xl" aria-hidden="true">🔋</span>
                        <h3 class="mt-3 font-extrabold text-slate-900">Cek perangkat</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Gunakan perangkat yang nyaman, isi daya baterai, dan siapkan koneksi internet.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
                        <span class="text-2xl" aria-hidden="true">⏱️</span>
                        <h3 class="mt-3 font-extrabold text-slate-900">Pilih waktu yang cukup</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Jangan membuka bagian tes sebelum kamu siap. Timer tetap berjalan saat tes ditinggalkan, dan terlalu sering pindah tab akan mengakhiri tes otomatis.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
                        <span class="text-2xl" aria-hidden="true">📖</span>
                        <h3 class="mt-3 font-extrabold text-slate-900">Baca soal baik-baik</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Pahami pertanyaannya dulu, terutama jika ada bacaan atau gambar pendukung.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
                        <span class="text-2xl" aria-hidden="true">✅</span>
                        <h3 class="mt-3 font-extrabold text-slate-900">Pastikan sudah terkirim</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Ikuti konfirmasi setelah menekan tombol kirim agar proses selesai dengan benar.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-4xl px-4 py-16 sm:px-6 sm:py-20" aria-labelledby="faq-title">
            <div class="text-center">
                <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-rose-600">Pertanyaan umum</p>
                <h2 id="faq-title" class="mt-3 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Masih ada yang ingin ditanyakan?</h2>
            </div>
            <div class="mt-8 space-y-3">
                <details class="group rounded-2xl border border-amber-200 bg-amber-50/70 p-5 open:bg-amber-50 sm:p-6" open>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-bold text-slate-900">
                        Bolehkah membuka tab atau aplikasi lain saat tes?
                        <span class="text-xl text-amber-700 transition group-open:rotate-45" aria-hidden="true">+</span>
                    </summary>
                    <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-700">Sebaiknya tetap di halaman tes. Sistem menghitung saat kamu meninggalkan tab. Jika dilakukan sampai 3 kali, jawaban yang sudah tersimpan akan dikirim otomatis dan bagian tes tersebut berakhir. Timer juga tetap berjalan saat kamu berpindah tab. Siapkan kebutuhanmu sebelum menekan “Mulai”.</p>
                </details>
                <details class="group rounded-2xl border border-slate-200 bg-white p-5 open:border-rose-200 open:bg-rose-50/30 sm:p-6">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-bold text-slate-900">
                        Kapan waktu tes mulai dihitung?
                        <span class="text-xl text-rose-600 transition group-open:rotate-45" aria-hidden="true">+</span>
                    </summary>
                    <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-600">Waktu mulai dihitung ketika kamu membuka bagian tes. Karena itu, baca petunjuk dan siapkan perangkat sebelum mulai.</p>
                </details>
                <details class="group rounded-2xl border border-slate-200 bg-white p-5 open:border-rose-200 open:bg-rose-50/30 sm:p-6">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-bold text-slate-900">
                        Apakah saya bisa kembali ke soal sebelumnya?
                        <span class="text-xl text-rose-600 transition group-open:rotate-45" aria-hidden="true">+</span>
                    </summary>
                    <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-600">Gunakan nomor soal atau tombol sebelumnya jika tersedia. Pada tes yang dibagi menjadi beberapa bagian, selesaikan bagian yang sedang dibuka sesuai petunjuk.</p>
                </details>
                <details class="group rounded-2xl border border-slate-200 bg-white p-5 open:border-rose-200 open:bg-rose-50/30 sm:p-6">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-bold text-slate-900">
                        Apa yang terjadi saat waktu habis?
                        <span class="text-xl text-rose-600 transition group-open:rotate-45" aria-hidden="true">+</span>
                    </summary>
                    <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-600">Sistem akan menyelesaikan bagian tes menggunakan jawaban yang sudah tersimpan. Jadi, jawab dan kirim soal dengan koneksi internet yang stabil.</p>
                </details>
            </div>
        </section>

        <section class="px-4 pb-16 sm:px-6 sm:pb-20">
            <div class="mx-auto max-w-7xl overflow-hidden rounded-[2rem] bg-gradient-to-br from-rose-700 via-rose-600 to-indigo-700 px-6 py-10 text-center shadow-2xl shadow-rose-900/15 sm:px-10 sm:py-14">
                <p class="text-sm font-bold uppercase tracking-[0.18em] text-rose-100">Kamu sudah tahu caranya</p>
                <h2 class="mx-auto mt-3 max-w-2xl text-3xl font-black tracking-tight text-white sm:text-4xl">Siap mengerjakan tes dengan lebih tenang?</h2>
                <p class="mx-auto mt-4 max-w-xl text-sm leading-6 text-rose-50 sm:text-base">Masuk ke akunmu untuk melihat tes yang tersedia.</p>
                @guest
                    <div class="mt-7 flex flex-col justify-center gap-3 sm:flex-row">
                        <a href="{{ route('register') }}" class="rounded-xl bg-white px-6 py-3.5 font-extrabold text-rose-700 transition hover:-translate-y-0.5 hover:bg-rose-50">Buat akun</a>
                        <a href="{{ route('login') }}" class="rounded-xl border border-white/40 px-6 py-3.5 font-extrabold text-white transition hover:bg-white/10">Masuk</a>
                    </div>
                @else
                    @if(auth()->user()->hasAnyRole(['admin', 'super_admin']))
                        <a href="{{ route('admin.dashboard') }}" class="mt-7 inline-flex rounded-xl bg-white px-6 py-3.5 font-extrabold text-rose-700 transition hover:-translate-y-0.5 hover:bg-rose-50">Buka dashboard</a>
                    @else
                        <a href="{{ route('user.tests') }}" class="mt-7 inline-flex rounded-xl bg-white px-6 py-3.5 font-extrabold text-rose-700 transition hover:-translate-y-0.5 hover:bg-rose-50">Lihat daftar tes</a>
                    @endif
                @endauth
            </div>
        </section>
    </main>

    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-6 text-center text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between sm:px-6 sm:text-left lg:px-8">
            <span>© {{ date('Y') }} Sistem Tes Online</span>
            <span>Kerjakan dengan jujur, fokus, dan sesuai petunjuk.</span>
        </div>
    </footer>
</div>
@endsection
