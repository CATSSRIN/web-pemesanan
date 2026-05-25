<x-app-layout>
    <x-slot name="header">
        Dashboard Overview
    </x-slot>

    @php
        $stats = [
            ['title' => 'Total Pelanggan', 'value' => \App\Models\Customer::count(), 'color' => 'bg-blue-500'],
            ['title' => 'Total Produk', 'value' => \App\Models\Product::count(), 'color' => 'bg-emerald-500'],
            ['title' => 'DO Bulan Ini', 'value' => \App\Models\DeliveryOrder::whereMonth('tanggal_do', now()->month)->count(), 'color' => 'bg-amber-500'],
            ['title' => 'Invoice Unpaid', 'value' => \App\Models\Invoice::where('status_invoice', 'unpaid')->count(), 'color' => 'bg-rose-500'],
        ];
        $recentDO = \App\Models\DeliveryOrder::with('customer')->latest()->take(5)->get();
        $missions = [
            ['label' => 'Isi Master Pelanggan', 'done' => \App\Models\Customer::count() > 0],
            ['label' => 'Isi Master Produk', 'done' => \App\Models\Product::count() > 0],
            ['label' => 'Buat DO Pertama', 'done' => \App\Models\DeliveryOrder::count() > 0],
            ['label' => 'Tarik Invoice dari DO', 'done' => \App\Models\Invoice::count() > 0],
        ];
        $completedMissions = collect($missions)->where('done', true)->count();
        $progressPercent = count($missions) > 0 ? (int) round(($completedMissions / count($missions)) * 100) : 0;
    @endphp

    <div class="bg-gradient-to-r from-indigo-600 to-violet-600 rounded-xl shadow-sm p-6 mb-8 text-white">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold">Selamat datang di Control Center Penjualan 🎯</h2>
                <p class="text-indigo-100 mt-1">Alur sederhana: buat DO, finalize, lalu tarik menjadi Invoice (1 DO = 1 Invoice).</p>
            </div>
            <div class="w-full lg:w-80">
                <div class="flex justify-between text-sm mb-1">
                    <span>Progress Setup Harian</span>
                    <span>{{ $progressPercent }}%</span>
                </div>
                <div class="w-full bg-indigo-400/50 rounded-full h-3">
                    <div class="bg-white h-3 rounded-full transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
                </div>
                <p class="text-xs mt-2 text-indigo-100">{{ $completedMissions }} dari {{ count($missions) }} misi selesai.</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach($missions as $mission)
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-4">
                <p class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $mission['label'] }}</p>
                @if($mission['done'])
                    <p class="text-xs mt-2 text-emerald-600 font-semibold">✓ Selesai (Badge Dapat!)</p>
                @else
                    <p class="text-xs mt-2 text-amber-600 font-semibold">• Belum selesai</p>
                @endif
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <a href="{{ route('do.create') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:border-indigo-400 transition">
            <p class="text-sm text-gray-500 dark:text-gray-400">Langkah Cepat 1</p>
            <p class="font-semibold text-gray-900 dark:text-white">Buat Delivery Order</p>
        </a>
        <a href="{{ route('do.index') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:border-indigo-400 transition">
            <p class="text-sm text-gray-500 dark:text-gray-400">Langkah Cepat 2</p>
            <p class="font-semibold text-gray-900 dark:text-white">Finalize & Tarik Invoice</p>
        </a>
        <a href="{{ route('invoices.index') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:border-indigo-400 transition">
            <p class="text-sm text-gray-500 dark:text-gray-400">Langkah Cepat 3</p>
            <p class="font-semibold text-gray-900 dark:text-white">Cek Pelunasan Invoice</p>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @foreach($stats as $stat)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-5 flex items-center">
                <div class="w-12 h-12 rounded-lg {{ $stat['color'] }} flex items-center justify-center text-white font-bold text-xl shadow-inner">
                    {{ substr($stat['title'], 0, 1) }}
                </div>
                <div class="ml-4">
                    <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">{{ $stat['title'] }}</h3>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stat['value'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Delivery Order Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-4 font-medium">Nomor DO</th>
                        <th class="px-6 py-4 font-medium">Tanggal</th>
                        <th class="px-6 py-4 font-medium">Pelanggan</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($recentDO as $do)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $do->nomor_do }}</td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $do->tanggal_do->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $do->customer->nama_pelanggan }}</td>
                        <td class="px-6 py-4">
                            @if($do->status_do === 'draft')
                                <span class="px-2.5 py-1 text-xs font-medium bg-amber-100 text-amber-800 rounded-full">Draft</span>
                            @elseif($do->status_do === 'finalized')
                                <span class="px-2.5 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Finalized</span>
                            @elseif($do->status_do === 'invoiced')
                                <span class="px-2.5 py-1 text-xs font-medium bg-emerald-100 text-emerald-800 rounded-full">Invoiced</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
