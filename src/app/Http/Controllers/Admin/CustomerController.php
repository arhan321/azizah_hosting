<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Daftar semua pelanggan
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')
            ->withCount('orders');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $customers = $query->latest()->paginate(20)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Detail pelanggan beserta riwayat pesanan
     */
    public function show(User $user)
    {
        abort_if($user->isAdmin(), 404);

        $orders = $user->orders()
            ->with(['items.design', 'customOrder', 'payments'])
            ->latest()
            ->paginate(10);

        return view('admin.customers.show', compact('user', 'orders'));
    }
}
