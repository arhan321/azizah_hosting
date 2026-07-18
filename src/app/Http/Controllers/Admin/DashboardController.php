<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\Design;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders    = Order::count();
        $pendingOrders  = Order::where('status', Order::STATUS_PENDING)->count();
        $ongoingOrders  = Order::where('status', Order::STATUS_DIKERJAKAN)->count();
        $doneOrders     = Order::where('status', Order::STATUS_SELESAI)->count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalRevenue   = Payment::where('status', 'success')->sum('amount');

        // Data penjualan 6 bulan terakhir
        $monthlySales = Payment::where('status', 'success')
            ->where('paid_at', '>=', now()->subMonths(6))
            ->selectRaw('DATE_FORMAT(paid_at, "%Y-%m") as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        // Pesanan terbaru
        $recentOrders = Order::with(['user', 'items.design', 'customOrder'])
            ->latest()
            ->limit(5)
            ->get();

        // Top designs
        $topDesigns = Design::withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders', 'pendingOrders', 'ongoingOrders', 'doneOrders',
            'totalCustomers', 'totalRevenue', 'monthlySales', 'recentOrders', 'topDesigns'
        ));
    }
}
