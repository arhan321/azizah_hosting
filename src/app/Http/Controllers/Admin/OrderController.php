<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderResult;
use App\Services\FileUploadService;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        protected FileUploadService   $uploadService,
        protected NotificationService $notifService
    ) {
    }

    /**
     * Daftar semua pesanan
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.design', 'customOrder', 'payments'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('order_type', $request->type);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $orders = $query->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Detail pesanan
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.design.category', 'customOrder.files', 'payments', 'result']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update status pesanan
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'in:pending,approved,dikerjakan,selesai,cancelled'],
        ]);

        $order->update(['status' => $request->status]);

        // Kirim notifikasi ke pelanggan
        $this->notifService->notifyStatusChange($order);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    /**
     * Upload hasil dokumentasi proyek
     */
    public function uploadResult(Request $request, Order $order)
    {
        $request->validate([
            'result_file'   => ['required', 'file', 'mimes:jpg,jpeg,png,pdf,mp4', 'max:20480'],
            'result_notes'  => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            $path = $this->uploadService->upload($request->file('result_file'), 'hasil');

            OrderResult::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'file_url' => $path,
                    'notes'    => $request->result_notes,
                ]
            );

            // Update status jika belum selesai
            if ($order->status !== Order::STATUS_SELESAI) {
                $order->update(['status' => Order::STATUS_SELESAI]);
                $this->notifService->notifyStatusChange($order);
            }

            return back()->with('success', 'Dokumentasi hasil berhasil diupload.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal upload hasil: ' . $e->getMessage());
        }
    }

    /**
     * Set harga (quote) untuk pesanan custom
     */
    public function setQuote(Request $request, Order $order)
    {
        abort_if($order->order_type !== Order::ORDER_TYPE_CUSTOM, 404);

        $request->validate([
            'admin_quote' => ['required', 'numeric', 'min:1'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $order->customOrder->update([
            'admin_quote' => $request->admin_quote,
            'admin_notes' => $request->admin_notes,
        ]);

        // Update total price di order utama
        $order->update([
            'total_price' => $request->admin_quote,
            'status'      => Order::STATUS_APPROVED,
        ]);

        // Notifikasi ke pelanggan
        $this->notifService->notifyQuoteReady($order);

        return back()->with('success', 'Harga custom berhasil ditetapkan. Pelanggan telah dinotifikasi.');
    }
}
