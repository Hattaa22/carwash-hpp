<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem HPP Car Wash')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full font-sans antialiased text-slate-800 flex flex-col md:flex-row">

    <!-- SIDEBAR -->
    <aside class="w-full md:w-64 bg-slate-900 text-slate-300 flex-shrink-0 flex flex-col justify-between min-h-screen">
        <div>
            <!-- Logo Header -->
            <div class="h-16 flex items-center px-6 bg-slate-950 gap-3 border-b border-slate-800">
                <div class="p-2 bg-blue-600 text-white rounded-lg">
                    <i data-lucide="droplets" class="w-5 h-5"></i>
                </div>
                <span class="font-bold text-lg text-white tracking-wide">Carwash HPP</span>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white transition' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    Dashboard
                </a>
                
                <div class="pt-4 pb-1 text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">Master Data</div>
                <a href="{{ route('admin.components') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.components*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white transition' }}">
                    <i data-lucide="boxes" class="w-4 h-4 text-slate-400"></i>
                    Bahan Baku & Komponen
                </a>
                <a href="{{ route('admin.vehicles') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.vehicles*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white transition' }}">
                    <i data-lucide="car" class="w-4 h-4 text-slate-400"></i>
                    Kategori Kendaraan
                </a>
                <a href="{{ route('admin.categories') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.categories*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white transition' }}">
                    <i data-lucide="folder-tree" class="w-4 h-4 text-slate-400"></i>
                    Kategori Layanan
                </a>

                <div class="pt-4 pb-1 text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">Kalkulasi</div>
                <a href="{{ route('hpp.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('hpp.*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white transition' }}">
                    <i data-lucide="calculator" class="w-4 h-4 text-slate-400"></i>
                    Hitung HPP
                </a>
            </nav>
        </div>

        <!-- User Profile -->
        <div class="p-4 border-t border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-xs">
                    AD
                </div>
                <div>
                    <p class="text-sm font-medium text-white">Administrator</p>
                    <p class="text-xs text-slate-500">admin@carwash.com</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="flex-1 flex flex-col overflow-y-auto">
        <!-- Top Header -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 sticky top-0 z-10">
            <div class="flex items-center gap-2 text-sm text-slate-500">
                <span>Aplikasi</span>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                <span class="font-medium text-slate-800">@yield('title', 'Overview')</span>
            </div>
        </header>

        <!-- Dynamic Content Body -->
        <main class="p-6 space-y-6">
            @if(session('success') || session('error') || session('warning'))
                @include('components.alert')
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>