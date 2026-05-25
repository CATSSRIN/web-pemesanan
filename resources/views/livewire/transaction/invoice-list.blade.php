<div>
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl mb-6">
        <div class="p-4 border-b border-gray-100 dark:border-gray-700 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="w-full md:w-1/3 relative">
                <input wire:model.live="search" type="text" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block pl-10 p-2.5" placeholder="Cari Invoice...">
            </div>
        </div>
        
        @if (session()->has('message'))
            <div class="p-4 mb-4 text-sm text-emerald-800 bg-emerald-50 dark:bg-gray-800 dark:text-emerald-400 rounded-lg mx-4 mt-4">
                {{ session('message') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3">Nomor Invoice</th>
                        <th class="px-6 py-3">Tanggal / Jatuh Tempo</th>
                        <th class="px-6 py-3">Pelanggan</th>
                        <th class="px-6 py-3 text-right">Total</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $inv)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            {{ $inv->nomor_invoice }}<br>
                            <span class="text-xs font-normal text-gray-500">Ref DO: {{ $inv->deliveryOrder->nomor_do ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            {{ $inv->tanggal_invoice->format('d M Y') }}<br>
                            <span class="text-xs font-normal text-red-500">Tempo: {{ $inv->jatuh_tempo->format('d M Y') }}</span>
                        </td>
                        <td class="px-6 py-4">{{ $inv->customer->nama_pelanggan }}</td>
                        <td class="px-6 py-4 text-right font-medium text-gray-900 dark:text-white">Rp {{ number_format($inv->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($inv->status_invoice === 'unpaid')
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Unpaid</span>
                            @elseif($inv->status_invoice === 'paid')
                                <span class="bg-emerald-100 text-emerald-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-emerald-900 dark:text-emerald-300">Paid</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            @if($inv->status_invoice === 'unpaid')
                                <button wire:click="markAsPaid({{ $inv->id }})" wire:confirm="Tandai Invoice ini sudah LUNAS?" class="font-medium text-emerald-600 dark:text-emerald-500 hover:underline mr-2">Tandai Lunas</button>
                            @endif
                            <a href="{{ route('print.invoice', $inv->id) }}" target="_blank" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">PDF Invoice</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100 dark:border-gray-700">
            {{ $invoices->links() }}
        </div>
    </div>
</div>
