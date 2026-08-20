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

    <!-- SIDEBAR (Theme: Clean Light Blue & White Compact) -->
    <aside class="w-full md:w-56 bg-white text-slate-700 flex-shrink-0 flex flex-col justify-between min-h-screen border-r border-slate-200 shadow-xs">
        <div>
            <!-- Logo Header -->
            <div class="h-14 flex items-center px-4 bg-white gap-2.5 border-b border-slate-100">
                <div class="p-1.5 bg-blue-600 text-white rounded-lg shadow-sm">
                    <i data-lucide="droplets" class="w-4 h-4"></i>
                </div>
                <div>
                    <h1 class="font-bold text-sm text-slate-900 tracking-wide leading-none">Carwash HPP</h1>
                    <span class="text-[9px] text-blue-600 font-semibold uppercase tracking-wider">Management</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="p-3 space-y-1">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-sm font-semibold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <i data-lucide="layout-dashboard" class="w-3.5 h-3.5 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400' }}"></i>
                    Dashboard
                </a>
                
                <div class="pt-4 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3">Master Data</div>
                
                <a href="{{ route('admin.components') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition-all duration-200 {{ request()->routeIs('admin.components*') ? 'bg-blue-600 text-white shadow-sm font-semibold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <i data-lucide="boxes" class="w-3.5 h-3.5 {{ request()->routeIs('admin.components*') ? 'text-white' : 'text-slate-400' }}"></i>
                    Komponen & Bahan
                </a>
                
                <a href="{{ route('admin.vehicles') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition-all duration-200 {{ request()->routeIs('admin.vehicles*') ? 'bg-blue-600 text-white shadow-sm font-semibold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <i data-lucide="car" class="w-3.5 h-3.5 {{ request()->routeIs('admin.vehicles*') ? 'text-white' : 'text-slate-400' }}"></i>
                    Kategori Kendaraan
                </a>
                
                <a href="{{ route('admin.categories') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition-all duration-200 {{ request()->routeIs('admin.categories*') ? 'bg-blue-600 text-white shadow-sm font-semibold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <i data-lucide="tags" class="w-3.5 h-3.5 {{ request()->routeIs('admin.categories*') ? 'text-white' : 'text-slate-400' }}"></i>
                    Kategori Layanan
                </a>

                <div class="pt-4 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3">Kalkulasi</div>
                
                <a href="{{ route('hpp.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition-all duration-200 {{ request()->routeIs('hpp.*') ? 'bg-blue-600 text-white shadow-sm font-semibold' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <i data-lucide="calculator" class="w-3.5 h-3.5 {{ request()->routeIs('hpp.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    Hitung HPP
                </a>
            </nav>
        </div>

        <!-- User Profile & Footer -->
        <div class="p-3 border-t border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-[11px] shadow-sm">
                    AD
                </div>
                <div class="truncate">
                    <p class="text-xs font-semibold text-slate-900 leading-none truncate">Administrator</p>
                    <p class="text-[10px] text-slate-500 mt-0.5 truncate">admin@carwash.com</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Keluar / Logout">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="flex-1 flex flex-col overflow-y-auto">
        <!-- Top Header (Clean Modern Blue & White) -->
        <header class="h-16 bg-white border-b border-slate-200/80 flex items-center justify-between px-8 sticky top-0 z-20 shadow-xs">
            <!-- Left: Breadcrumb & Page Context -->
            <div class="flex items-center gap-3">
                <div class="p-1.5 bg-blue-50 text-blue-600 rounded-lg">
                    <i data-lucide="layout-grid" class="w-4 h-4"></i>
                </div>
                <div class="flex items-center gap-2 text-xs font-medium text-slate-500">
                    <span class="hover:text-blue-600 transition cursor-pointer">Sistem HPP</span>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                    <span class="font-semibold text-slate-900 bg-slate-100/80 px-2.5 py-1 rounded-md">@yield('title', 'Overview')</span>
                </div>
            </div>

            <!-- Right: Search Input & Quick Controls -->
            <div class="flex items-center gap-4">
                <!-- Search Bar -->
                <div class="relative hidden sm:block w-64">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" placeholder="Cari layanan, bahan..." class="w-full pl-9 pr-4 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:outline-none focus:border-blue-600 focus:bg-white transition shadow-xs">
                </div>

                <!-- Date Badge -->
                <div class="hidden lg:flex items-center gap-2 text-xs font-medium text-slate-600 bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-lg">
                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-blue-600"></i>
                    <span>{{ date('d M Y') }}</span>
                </div>
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