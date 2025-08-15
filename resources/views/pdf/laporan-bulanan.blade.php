<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Bulanan Penjualan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Laporan Bulanan Penjualan</h2>
    <p><strong>Bulan:</strong> {{ \Carbon\Carbon::parse($selectedMonth)->translatedFormat('F Y') }}</p>
    @if($selectedSalesId)
        <p><strong>Sales:</strong> {{ $sales->where('id', $selectedSalesId)->first()->name }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>Nama Toko</th>
                <th>Total Barang</th>
                <th>Total Uang</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($results as $r)
                <tr>
                    <td>{{ $r->store_name }}</td>
                    <td>{{ $r->total_barang }}</td>
                    <td>Rp{{ number_format($r->total_uang, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center">Tidak ada data untuk bulan ini</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
