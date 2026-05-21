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
    @endphp

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
