<!DOCTYPE html>
<html>
<head>
    <title>Delivery Order - {{ $do->nomor_do }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; }
        .info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h2>SURAT JALAN / DELIVERY ORDER</h2>
        <p>No: {{ $do->nomor_do }}</p>
    </div>
    <div class="info">
        <p><strong>Kepada Yth:</strong><br>
        {{ $do->customer->nama_pelanggan }}<br>
        {{ $do->alamat_kirim }}<br>
        Tanggal: {{ $do->tanggal_do->format('d M Y') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Qty</th>
                <th>Satuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($do->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->product->nama_produk }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ $item->product->satuan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top: 30px;">
        <p>Keterangan: {{ $do->keterangan ?? '-' }}</p>
    </div>
    <table style="width: 100%; border: none; margin-top: 50px;">
        <tr>
            <td style="border: none; text-align: center; width: 33%;">Penerima,</td>
            <td style="border: none; text-align: center; width: 33%;">Pengirim,</td>
            <td style="border: none; text-align: center; width: 33%;">Hormat Kami,</td>
        </tr>
        <tr>
            <td style="border: none; height: 80px;"></td>
            <td style="border: none;"></td>
            <td style="border: none;"></td>
        </tr>
        <tr>
            <td style="border: none; text-align: center;">(_____________)</td>
            <td style="border: none; text-align: center;">(_____________)</td>
            <td style="border: none; text-align: center;">(_____________)</td>
        </tr>
    </table>
</body>
</html>
