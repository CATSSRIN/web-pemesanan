<div>
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl mb-6">
        <form wire:submit.prevent="store" class="p-6">
            @if (session()->has('error'))
                <div class="p-4 mb-6 text-sm text-red-800 bg-red-50 dark:bg-gray-800 dark:text-red-400 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 pb-2 border-b border-gray-100 dark:border-gray-700">Informasi Umum</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pelanggan</label>
                    <select wire:model.live="customer_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}">{{ $c->nama_pelanggan }} ({{ $c->kode_pelanggan }})</option>
                        @endforeach
                    </select>
                    @error('customer_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal DO</label>
                    <input type="date" wire:model="tanggal_do" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('tanggal_do') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat Kirim</label>
                    <textarea wire:model="alamat_kirim" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" rows="2"></textarea>
                    @error('alamat_kirim') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Keterangan Tambahan</label>
                    <input type="text" wire:model="keterangan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 pb-2 border-b border-gray-100 dark:border-gray-700">Item Produk</h3>
            
            <div class="flex items-end gap-4 mb-6 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="flex-1">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Produk (Harga otomatis menyesuaikan pelanggan)</label>
                    <select wire:model="selectedProductId" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                        <option value="">-- Pilih Produk --</option>
                        @foreach($availableProducts as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" wire:click="addProduct" class="bg-slate-800 hover:bg-slate-900 text-white font-medium py-2.5 px-6 rounded-lg text-sm transition-colors">
                    Tambahkan
                </button>
            </div>
            @if (session()->has('error_item'))
                <p class="text-red-500 text-xs mb-4">{{ session('error_item') }}</p>
            @endif

            <div class="overflow-x-auto mb-6">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3 rounded-l-lg">Produk</th>
                            <th class="px-4 py-3 w-32">Qty</th>
                            <th class="px-4 py-3 text-right">Harga (Rp)</th>
                            <th class="px-4 py-3 text-right">Subtotal (Rp)</th>
                            <th class="px-4 py-3 rounded-r-lg text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $index => $item)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $item['nama_produk'] }}</td>
                            <td class="px-4 py-3">
                                <input type="number" min="1" value="{{ $item['qty'] }}" wire:change="updateQty({{ $index }}, $event.target.value)" class="w-20 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </td>
                            <td class="px-4 py-3 text-right">{{ number_format($item['harga'], 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right font-medium text-gray-900 dark:text-white">{{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center">
                                <button type="button" wire:click="removeItem({{ $index }})" class="text-red-500 hover:text-red-700">
                                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">Belum ada produk yang ditambahkan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50 dark:bg-gray-700/50">
                            <td colspan="3" class="px-4 py-4 text-right font-semibold text-gray-900 dark:text-white">Grand Total:</td>
                            <td class="px-4 py-4 text-right font-bold text-indigo-600 dark:text-indigo-400 text-lg">Rp {{ number_format($this->grandTotal, 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                @error('items') <span class="text-red-500 text-sm font-medium mt-2 block">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('do.index') }}" class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">Simpan Delivery Order</button>
            </div>
        </form>
    </div>
</div>
