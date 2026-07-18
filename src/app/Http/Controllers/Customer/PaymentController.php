<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct(protected PaymentService $paymentService) {}

    /**
     * Halaman pembayaran
     */
    public function show(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);

        $order->load(['items.design', 'customOrder', 'payments']);

        return view('payments.show', compact('order'));
    }

    /**
     * Proses pembayaran — upload bukti transfer
     */
    public function process(Request $request, Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);


        $request->validate([
            'payment_type' => ['required', 'in:full,dp'],
            'bank_name' => ['required', 'string', 'max:100'],
            'account_number' => ['required'],
            'account_holder' => ['required', 'string', 'max:100'],
            'payment_proof' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
        ]);

        try {
            $result = $this->paymentService->createBankTransferPayment($order, $request->payment_type, [
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'account_holder' => $request->account_holder,
                'payment_proof' => $request->file('payment_proof'),
                'transfer_date' => now(),
            ]);

            return redirect()->route('orders.show', $order->id)
                ->with('success', $result['message']);
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal mengunggah bukti transfer: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Callback selesai pembayaran (after upload)
     */
    public function finish(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Bukti pembayaran berhasil diunggah! Menunggu verifikasi admin.');
    }

    /**
     * Callback pending pembayaran
     */
    public function pending(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);

        return redirect()->route('orders.show', $order->id)
            ->with('warning', 'Pembayaran Anda sedang menunggu verifikasi admin.');
    }

    /**
     * Callback gagal pembayaran
     */
    public function failed(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);

        return redirect()->route('orders.show', $order->id)
            ->with('error', 'Pembayaran gagal. Silakan upload ulang bukti transfer.');
    }

    /**
     * Tampilkan detail satu pembayaran (customer)
     */
    public function detail(Order $order, $paymentId)
    {
        abort_if($order->user_id !== Auth::id(), 403);

        $payment = $order->payments()->where('id', $paymentId)->firstOrFail();

        return view('payments.detail', compact('order', 'payment'));
    }
}
