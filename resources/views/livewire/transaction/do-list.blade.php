<div>
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl mb-6">
        <div class="p-4 border-b border-gray-100 dark:border-gray-700 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="w-full md:w-1/3 relative">
                <input wire:model.live="search" type="text" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block pl-10 p-2.5" placeholder="Cari DO...">
            </div>
            <a href="{{ route('do.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg text-sm transition-colors text-center w-full md:w-auto">
                + Buat DO Baru
            </a>
        </div>
        
        @if (session()->has('message'))
            <div class="p-4 mb-4 text-sm text-emerald-800 bg-emerald-50 dark:bg-gray-800 dark:text-emerald-400 rounded-lg mx-4 mt-4">
                {{ session('message') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="p-4 mb-4 text-sm text-red-800 bg-red-50 dark:bg-gray-800 dark:text-red-400 rounded-lg mx-4 mt-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3">Nomor DO</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Pelanggan</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dos as $do)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $do->nomor_do }}</td>
                        <td class="px-6 py-4">{{ $do->tanggal_do->format('d M Y') }}</td>
                        <td class="px-6 py-4">{{ $do->customer->nama_pelanggan }}</td>
                        <td class="px-6 py-4">
                            @if($do->status_do === 'draft')
                                <span class="bg-amber-100 text-amber-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-amber-900 dark:text-amber-300">Draft</span>
                            @elseif($do->status_do === 'finalized')
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">Finalized</span>
                            @elseif($do->status_do === 'invoiced')
                                <span class="bg-emerald-100 text-emerald-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-emerald-900 dark:text-emerald-300">Invoiced</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            @if($do->status_do === 'draft')
                                <a href="{{ route('do.edit', $do->id) }}" class="font-medium text-indigo-600 dark:text-indigo-500 hover:underline">Edit</a>
                                <button wire:click="finalizeDo({{ $do->id }})" wire:confirm="Yakin ingin finalisasi DO ini? Tidak dapat diedit lagi." class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Finalize</button>
                            @endif
                            
                            @if($do->status_do === 'finalized')
                                <button wire:click="createInvoice({{ $do->id }})" wire:confirm="Buat Invoice dari DO ini?" class="font-medium text-emerald-600 dark:text-emerald-500 hover:underline">Buat Invoice</button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100 dark:border-gray-700">
            {{ $dos->links() }}
        </div>
    </div>
</div>
