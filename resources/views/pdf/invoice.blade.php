<!DOCTYPE html>
<html>
<head>
    <title>Invoice - {{ $invoice->nomor_invoice }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; }
        .info { margin-bottom: 20px; width: 100%; }
        .info td { border: none; padding: 2px; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data th, table.data td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        table.data th { background-color: #f2f2f2; }
        .text-right { text-align: right !important; }
        .total-row td { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>INVOICE</h2>
    </div>
    
    <table class="info">
        <tr>
            <td width="50%">
                <strong>Kepada Yth:</strong><br>
                {{ $invoice->customer->nama_pelanggan }}<br>
                {{ $invoice->customer->alamat }}<br>
                {{ $invoice->customer->kota }}
            </td>
            <td width="50%" class="text-right">
                <strong>No Invoice:</strong> {{ $invoice->nomor_invoice }}<br>
                <strong>Ref DO:</strong> {{ $invoice->deliveryOrder->nomor_do ?? '-' }}<br>
                <strong>Tanggal:</strong> {{ $invoice->tanggal_invoice->format('d M Y') }}<br>
                <strong>Jatuh Tempo:</strong> {{ $invoice->jatuh_tempo->format('d M Y') }}
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Harga (Rp)</th>
                <th class="text-right">Subtotal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->product->nama_produk ?? 'Item' }}</td>
                <td class="text-right">{{ $item->qty }}</td>
                <td class="text-right">{{ number_format($item->harga, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4" class="text-right">TOTAL</td>
                <td class="text-right">{{ number_format($invoice->total, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 50px;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; text-align: right;">Hormat Kami,</td>
            </tr>
            <tr>
                <td style="border: none; height: 80px;"></td>
            </tr>
            <tr>
                <td style="border: none; text-align: right;">( _________________ )</td>
            </tr>
        </table>
    </div>
</body>
</html>
