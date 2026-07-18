<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan — Aqlam Mural Kaligrafi</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 4px; }
        .subtitle { text-align: center; color: #555; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #1a1a2e; color: white; padding: 8px; text-align: left; }
        td { padding: 6px 8px; border-bottom: 1px solid #eee; }
        tfoot td { font-weight: bold; border-top: 2px solid #333; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom:20px">
        <button onclick="window.print()">Cetak Laporan</button>
        <a href="{{ route('admin.reports.index') }}" style="margin-left:10px">Kembali</a>
    </div>

    <h2>Aqlam Mural Kaligrafi</h2>
    <div class="subtitle">Laporan Penjualan: {{ $fromDate }} s/d {{ $toDate }}</div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Email</th>
                <th>Tipe</th>
                <th>Status</th>
                <th>Total Harga</th>
                <th>Sudah Dibayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $i => $order)
            @php $totalPaid = $order->payments->where('status','success')->sum('amount'); @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                <td>{{ $order->user->name }}</td>
                <td>{{ $order->user->email }}</td>
                <td>{{ ucfirst($order->order_type) }}</td>
                <td>{{ ucfirst($order->status) }}</td>
                <td>Rp {{ $order->total_price > 0 ? number_format($order->total_price, 0, ',', '.') : '-' }}</td>
                <td>{{ $totalPaid > 0 ? 'Rp ' . number_format($totalPaid, 0, ',', '.') : '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center">Tidak ada data</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7">Total Pendapatan</td>
                <td>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <p style="margin-top:30px; text-align:right; color:#888; font-size:10px">
        Dicetak pada {{ now()->format('d F Y, H:i') }}
    </p>
</body>
</html>
