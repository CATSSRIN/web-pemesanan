<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Pemesanan') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-50 dark:bg-gray-900 dark:text-gray-100 flex overflow-hidden h-screen">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-900 text-white flex-shrink-0 flex flex-col hidden md:flex transition-all duration-300">
            <div class="h-16 flex items-center justify-center border-b border-slate-800 font-bold text-xl tracking-wider">
                <span class="text-indigo-400 mr-2">✦</span> KEMIRI PRO
            </div>
            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-1 px-3">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-slate-800 {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-indigo-400' : 'text-slate-300' }}">
                            Dashboard
                        </a>
                    </li>
                    <li class="pt-4 pb-2 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Master Data</li>
                    <li>
                        <a href="{{ route('customers.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-slate-800 {{ request()->routeIs('customers.*') ? 'bg-slate-800 text-indigo-400' : 'text-slate-300' }}">
                            Pelanggan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('products.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-slate-800 {{ request()->routeIs('products.*') ? 'bg-slate-800 text-indigo-400' : 'text-slate-300' }}">
                            Produk
                        </a>
                    </li>
                    <li class="pt-4 pb-2 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Transaksi</li>
                    <li>
                        <a href="{{ route('do.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-slate-800 {{ request()->routeIs('do.*') ? 'bg-slate-800 text-indigo-400' : 'text-slate-300' }}">
                            Delivery Order
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('invoices.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg hover:bg-slate-800 {{ request()->routeIs('invoices.*') ? 'bg-slate-800 text-indigo-400' : 'text-slate-300' }}">
                            Faktur (Invoice)
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Topbar -->
            <header class="h-16 flex items-center justify-between bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 sm:px-6 z-10">
                <div class="flex items-center">
                    <button class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    @if (isset($header))
                        <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200 ml-2 md:ml-0">{{ $header }}</h1>
                    @endif
                </div>
                
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ auth()->user()->name ?? 'User' }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 font-medium hover:text-red-800">Logout</button>
                    </form>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50 dark:bg-gray-900">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
