<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Halaman utama laporan penjualan
     */
    public function index(Request $request)
    {
        $fromDate = $request->get('from', now()->startOfMonth()->toDateString());
        $toDate   = $request->get('to', now()->toDateString());

        // Total revenue rentang waktu
        $totalRevenue = Payment::where('status', 'success')
            ->whereBetween('paid_at', [$fromDate, $toDate . ' 23:59:59'])
            ->sum('amount');

        // Total pesanan
        $totalOrders = Order::whereBetween('created_at', [$fromDate, $toDate . ' 23:59:59'])->count();

        // Data per bulan
        $monthlySales = Payment::where('status', 'success')
            ->whereBetween('paid_at', [$fromDate, $toDate . ' 23:59:59'])
            ->selectRaw('DATE_FORMAT(paid_at, "%Y-%m") as month, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Detail pesanan dalam rentang
        $orders = Order::with(['user', 'items.design', 'customOrder', 'payments'])
            ->whereBetween('created_at', [$fromDate, $toDate . ' 23:59:59'])
            ->latest()
            ->get();

        // Pesanan per status
        $ordersByStatus = Order::whereBetween('created_at', [$fromDate, $toDate . ' 23:59:59'])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('admin.reports.index', compact(
            'fromDate', 'toDate', 'totalRevenue', 'totalOrders',
            'monthlySales', 'orders', 'ordersByStatus'
        ));
    }

    /**
     * Cetak laporan (versi print-friendly)
     */
    public function print(Request $request)
    {
        $fromDate = $request->get('from', now()->startOfMonth()->toDateString());
        $toDate   = $request->get('to', now()->toDateString());

        $orders = Order::with(['user', 'items.design', 'customOrder', 'payments'])
            ->whereBetween('created_at', [$fromDate, $toDate . ' 23:59:59'])
            ->latest()
            ->get();

        $totalRevenue = Payment::where('status', 'success')
            ->whereBetween('paid_at', [$fromDate, $toDate . ' 23:59:59'])
            ->sum('amount');

        return view('admin.reports.print', compact('orders', 'totalRevenue', 'fromDate', 'toDate'));
    }

    /**
     * Export laporan ke CSV (sederhana)
     */
    public function export(Request $request)
    {
        $fromDate = $request->get('from', now()->startOfMonth()->toDateString());
        $toDate   = $request->get('to', now()->toDateString());

        $orders = Order::with(['user', 'payments'])
            ->whereBetween('created_at', [$fromDate, $toDate . ' 23:59:59'])
            ->get();

        $csv = "No,Tanggal,Pelanggan,Email,Tipe,Status,Total,Pembayaran\n";
        foreach ($orders as $i => $order) {
            $totalPaid = $order->payments->where('status', 'success')->sum('amount');
            $csv .= implode(',', [
                $i + 1,
                $order->created_at->format('d/m/Y'),
                '"' . $order->user->name . '"',
                '"' . $order->user->email . '"',
                $order->order_type,
                $order->status,
                $order->total_price,
                $totalPaid,
            ]) . "\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="laporan-' . $fromDate . '-' . $toDate . '.csv"');
    }
}
