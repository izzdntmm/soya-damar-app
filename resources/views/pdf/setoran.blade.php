<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Setoran — Soya Damar</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1f2937; }

        .header { background: #15803d; color: white; padding: 20px 24px; margin-bottom: 20px; }
        .header h1 { font-size: 20px; font-weight: bold; }
        .header p { font-size: 11px; opacity: 0.8; margin-top: 3px; }
        .header .periode { margin-top: 8px; font-size: 12px; background: rgba(255,255,255,0.15); display: inline-block; padding: 3px 10px; border-radius: 4px; }

        .stats { display: flex; gap: 12px; padding: 0 24px; margin-bottom: 20px; }
        .stat-card { flex: 1; border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; text-align: center; }
        .stat-card .label { font-size: 10px; color: #6b7280; text-transform: uppercase; }
        .stat-card .value { font-size: 18px; font-weight: bold; color: #15803d; margin-top: 3px; }

        .content { padding: 0 24px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        thead tr { background: #15803d; color: white; }
        th { padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 7px 10px; border-bottom: 1px solid #f3f4f6; font-size: 10px; }
        tr:nth-child(even) { background: #f0fdf4; }
        .total-row { background: #dcfce7 !important; font-weight: bold; border-top: 2px solid #15803d; }

        .footer { margin-top: 30px; padding: 16px 24px; border-top: 2px solid #e5e7eb; display: flex; justify-content: space-between; font-size: 10px; color: #9ca3af; }

        .badge { display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: 9px; font-weight: bold; }
        .badge-acc { background: #dcfce7; color: #15803d; }
        .badge-dikirim { background: #fef9c3; color: #854d0e; }
        .badge-draft { background: #f3f4f6; color: #6b7280; }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <h1>🥛 Laporan Setoran — Soya Damar</h1>
        <p>Sistem Manajemen Penyetoran Digital</p>
        <div class="periode">
            Periode: {{ \Carbon\Carbon::parse($mulai)->format('d M Y') }}
            – {{ \Carbon\Carbon::parse($akhir)->format('d M Y') }}
        </div>
    </div>

    {{-- Statistik Ringkasan --}}
    <div class="stats">
        <div class="stat-card">
            <div class="label">Total Setoran</div>
            <div class="value">{{ $setoran->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Total Unit</div>
            <div class="value">{{ $setoran->sum(fn($s) => $s->totalTerjual()) }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Total Pendapatan</div>
            <div class="value" style="font-size:13px">
                Rp {{ number_format($setoran->sum(fn($s) => $s->totalUang()), 0, ',', '.') }}
            </div>
        </div>
    </div>

    {{-- Tabel Data --}}
    <div class="content">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Sales</th>
                    <th>Toko</th>
                    <th>Unit</th>
                    <th>Total (Rp)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; $grandUnit = 0; @endphp
                @foreach($setoran as $i => $item)
                @php
                    $grandTotal += $item->totalUang();
                    $grandUnit  += $item->totalTerjual();
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $item->sales->nama }}</td>
                    <td>{{ $item->detail->count() }}</td>
                    <td>{{ $item->totalTerjual() }}</td>
                    <td>{{ number_format($item->totalUang(), 0, ',', '.') }}</td>
                    <td>
                        <span class="badge badge-{{ $item->status }}">
                            {{ strtoupper($item->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach

                {{-- Baris Total --}}
                <tr class="total-row">
                    <td colspan="4">TOTAL KESELURUHAN</td>
                    <td>{{ $grandUnit }}</td>
                    <td>{{ number_format($grandTotal, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <span>Dicetak: {{ now()->format('d M Y, H:i') }} WIB</span>
        <span>Soya Damar — Sistem Manajemen Penyetoran</span>
    </div>

</body>
</html>