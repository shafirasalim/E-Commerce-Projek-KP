<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        h2 { margin-bottom: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #999; padding: 5px 8px; }
        th { background: #eee; text-align: left; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <h2>Laporan Penjualan — Cianjur Fresh</h2>
    <p>
        Periode: {{ \Carbon\Carbon::parse($from)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}<br>
        Omzet (Paid): Rp {{ number_format($summary['omzet'], 0, ',', '.') }} |
        Total Transaksi: {{ $summary['total_transaksi'] }} |
        Paid: {{ $summary['total_paid'] }} |
        Produk Terjual: {{ $summary['produk_terjual'] }}
    </p>

    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Item</th>
                <th>Status</th>
                <th class="right">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $t)
                <tr>
                    <td>#INV-{{ $t->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($t->transaction_date)->format('d/m/Y H:i') }}</td>
                    <td>{{ $t->user->name ?? '-' }}</td>
                    <td>{{ $t->details->sum('quantity') }}</td>
                    <td>{{ strtoupper($t->status) }}</td>
                    <td class="right">{{ number_format($t->total_amount, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>