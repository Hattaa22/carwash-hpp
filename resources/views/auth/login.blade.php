<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Sistem HPP Car Wash</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="min-h-screen bg-slate-50 font-sans antialiased text-slate-800 flex items-center justify-center p-4 sm:p-6 md:p-8">

    <div class="w-full max-w-sm sm:max-w-md my-auto">
        <!-- Logo & Brand Header -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 bg-blue-600 text-white rounded-2xl shadow-lg shadow-blue-500/20 mb-3">
                <i data-lucide="droplets" class="w-6 h-6 sm:w-7 sm:h-7"></i>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Carwash HPP</h1>
            <p class="text-xs text-slate-500 mt-1">Sistem Manajemen & Kalkulasi HPP Car Wash</p>
        </div>

        <!-- Login Card Container -->
        <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xl shadow-slate-200/50 p-6 sm:p-8 space-y-5">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="text-base sm:text-lg font-bold text-slate-900">Masuk ke Akun</h2>
                <p class="text-xs text-slate-500 mt-0.5">Masukkan kredensial Anda untuk melanjutkan</p>
            </div>

            <!-- Validation & Feedback Messages -->
            @if(session('success'))
                <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-xs font-medium flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 flex-shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-xs font-medium flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 flex-shrink-0"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <i data-lucide="mail" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="email" id="email" name="email" value="{{ old('email', 'admin@carwash.com') }}" required autofocus
                               placeholder="nama@email.com" 
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-blue-600 focus:bg-white transition">
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <i data-lucide="lock" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="password" id="password" name="password" required value="password"
                               placeholder="••••••••" 
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-blue-600 focus:bg-white transition">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        <span class="text-xs text-slate-600 font-medium">Ingat Saya</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 sm:py-3 px-4 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-xs sm:text-sm rounded-xl shadow-md shadow-blue-500/20 transition flex items-center justify-center gap-2">
                        <i data-lucide="log-in" class="w-4 h-4"></i>
                        Masuk Sistem
                    </button>
                </div>
            </form>

            <!-- Quick Demo Credentials Hint -->
            <div class="pt-3 border-t border-slate-100 text-center">
                <p class="text-[11px] text-slate-400">Demo Login: <span class="font-semibold text-slate-600">admin@carwash.com</span> / <span class="font-semibold text-slate-600">password</span></p>
            </div>
        </div>

        <!-- Footer Credits -->
        <p class="text-center text-[11px] text-slate-400 mt-6 sm:mt-8">&copy; {{ date('Y') }} Carwash HPP System. All rights reserved.</p>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
