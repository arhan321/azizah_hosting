<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\NotificationService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct(
        protected NotificationService $notifService,
        protected PaymentService $paymentService
    ) {}

    /**
     * Daftar semua pembayaran
     */
    public function index(Request $request)
    {
        $query = Payment::with(['order.user', 'verifiedBy'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('verification_status')) {
            $query->where('verification_status', $request->verification_status);
        }

        if ($request->filled('search')) {
            $query->whereHas('order.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $payments = $query->paginate(20)->withQueryString();

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Verifikasi pembayaran (approve)
     */
    public function confirm(Request $request, Payment $payment)
    {
        $request->validate([
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $this->paymentService->verifyPayment(
                $payment->id,
                Auth::id(),
                true,
                $request->notes
            );

            // Notifikasi
            $this->notifService->notifyPaymentConfirmed($payment->order);

            return back()->with('success', 'Pembayaran berhasil diverifikasi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memverifikasi pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Tolak pembayaran
     */
    public function reject(Request $request, Payment $payment)
    {
        $request->validate([
            'notes' => ['required', 'string', 'max:500'],
        ]);

        try {
            $this->paymentService->verifyPayment(
                $payment->id,
                Auth::id(),
                false,
                $request->notes
            );

            // Notifikasi (opsional, bisa dibuat method baru)
            // $this->notifService->notifyPaymentRejected($payment->order, $request->notes);

            return back()->with('success', 'Pembayaran ditolak.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menolak pembayaran: ' . $e->getMessage());
        }
    }
}
