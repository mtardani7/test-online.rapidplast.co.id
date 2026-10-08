@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-red-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-12">
            <div class="flex justify-center mb-6">
                <div class="bg-gradient-to-r from-red-500 to-red-600 p-4 rounded-2xl shadow-lg">
                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <h1 class="text-4xl font-extrabold text-gray-900 mb-4">
                Verifikasi <span class="text-red-600">Email Anda</span>
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Selesaikan langkah terakhir untuk melanjutkan proses rekrutmen
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border-2 border-red-100">
            <div class="bg-gradient-to-r from-red-600 to-red-700 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white/20 p-2 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white">Verifikasi Email</h2>
                            <p class="text-red-100 text-sm mt-1">Silakan verifikasi email Anda</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-8">
                @if (session('resent'))
                    <div class="mb-8 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-r-lg shadow-sm">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-semibold text-green-800">Email Terkirim!</h3>
                                <p class="mt-1 text-green-700">
                                    Tautan verifikasi baru telah dikirim ke email Anda. Silakan periksa kotak masuk atau folder spam.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid md:grid-cols-2 gap-8 mb-10">
                    <div class="bg-red-50 rounded-xl p-6 border border-red-100">
                        <div class="flex items-start mb-4">
                            <div class="bg-red-100 p-3 rounded-lg mr-4">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Periksa Email Anda</h3>
                                <p class="text-gray-600">
                                    Kami telah mengirimkan tautan verifikasi ke alamat email yang Anda daftarkan.
                                    Klik tautan tersebut untuk melanjutkan proses.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <div class="flex items-start mb-4">
                            <div class="bg-gray-200 p-3 rounded-lg mr-4">
                                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak Menerima Email?</h3>
                                <p class="text-gray-600">
                                    Jika email tidak ditemukan dalam beberapa menit, coba kirim ulang tautan verifikasi.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resend Form -->
                <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-xl p-6 mb-8 border border-red-200">
                    <div class="text-center">
                        <p class="text-gray-700 mb-6">
                            {{ __('Jika Anda tidak menerima email verifikasi') }},
                        </p>
                        <form method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-red-600 to-red-700 text-white font-bold rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-2xl shadow-lg">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                {{ __('Kirim Ulang Tautan Verifikasi') }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Tips Section -->
                <div class="mb-10">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Tips & Panduan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white border border-gray-200 rounded-xl p-6 text-center hover:border-red-300 hover:shadow-md transition-all">
                            <div class="bg-red-100 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                            </div>
                            <h4 class="font-bold text-gray-900 mb-2">Cek Folder Spam</h4>
                            <p class="text-gray-600 text-sm">Periksa folder spam atau junk mail di email Anda</p>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-xl p-6 text-center hover:border-red-300 hover:shadow-md transition-all">
                            <div class="bg-red-100 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h4 class="font-bold text-gray-900 mb-2">Tunggu Beberapa Menit</h4>
                            <p class="text-gray-600 text-sm">Email mungkin butuh waktu 1-5 menit untuk sampai</p>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-xl p-6 text-center hover:border-red-300 hover:shadow-md transition-all">
                            <div class="bg-red-100 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <h4 class="font-bold text-gray-900 mb-2">Butuh Bantuan?</h4>
                            <p class="text-gray-600 text-sm">Hubungi tim rekrutmen kami untuk bantuan lebih lanjut</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Note -->
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-500">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                Data Anda terlindungi dengan sistem keamanan terenkripsi
            </p>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
    .bg-gradient-red {
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
    }
    
    .shadow-custom {
        box-shadow: 0 10px 40px rgba(220, 38, 38, 0.15);
    }
    
    .hover-lift:hover {
        transform: translateY(-4px);
        transition: transform 0.3s ease;
    }
</style>
@endsection