<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;

class PaymentService
{
    public function __construct(protected NotificationService $notificationService) {}

    /**
     * Create payment record for bank transfer
     */
    public function createBankTransferPayment($order, $paymentType = 'full', $paymentData = [])
    {
        try {
            DB::beginTransaction();

            // Calculate amount
            if ($paymentType === 'dp') {
                $amount = $order->total_price * 0.5;
            } else {
                if ($order->payment_status === Order::PAYMENT_STATUS_DP_PAID) {
                    $amount = $order->total_price * 0.5;
                } else {
                    $amount = $order->total_price;
                }
            }

            // Handle payment proof upload
            $paymentProofPath = null;
            if (isset($paymentData['payment_proof']) && $paymentData['payment_proof']) {
                $file = $paymentData['payment_proof'];
                $filename = 'payment_' . $order->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $paymentProofPath = $file->storeAs('payments', $filename, 'public');
            }

            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'amount' => $amount,
                'payment_type' => $paymentType,
                'status' => Payment::STATUS_PENDING,
                'payment_method' => 'bank_transfer',
                'bank_name' => $paymentData['bank_name'] ?? null,
                'account_number' => $paymentData['account_number'] ?? null,
                'account_holder' => $paymentData['account_holder'] ?? null,
                'payment_proof' => $paymentProofPath,
                'transfer_date' => $paymentData['transfer_date'] ?? now(),
                'verification_status' => Payment::VERIFICATION_PENDING,
            ]);

            DB::commit();

            // Notify admin about new payment submission
            $this->notificationService->notifyAdminPaymentReceived($order);

            return [
                'success' => true,
                'payment_id' => $payment->id,
                'order_id' => $order->id,
                'amount' => $amount,
                'payment_type' => $paymentType,
                'message' => 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.',
            ];
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to create payment: ' . $e->getMessage());
        }
    }

    /**
     * Verify payment by admin
     */
    public function verifyPayment($paymentId, $adminId, $isApproved = true, $notes = null)
    {
        try {
            DB::beginTransaction();

            $payment = Payment::findOrFail($paymentId);
            $order = $payment->order;

            if ($isApproved) {
                // Approve payment
                $payment->update([
                    'status' => Payment::STATUS_SUCCESS,
                    'verification_status' => Payment::VERIFICATION_VERIFIED,
                    'verified_by' => $adminId,
                    'verified_at' => now(),
                    'verification_notes' => $notes,
                    'paid_at' => now(),
                ]);

                // Update order payment status
                if ($payment->payment_type === Order::PAYMENT_DP) {
                    $order->update([
                        'payment_status' => Order::PAYMENT_STATUS_DP_PAID,
                    ]);
                } else {
                    // Full payment received
                    $order->update([
                        'payment_status' => Order::PAYMENT_STATUS_FULLY_PAID,
                        'status' => Order::STATUS_DIKERJAKAN,
                    ]);
                }

                DB::commit();

                return [
                    'success' => true,
                    'payment_id' => $payment->id,
                    'status' => $payment->status,
                    'message' => 'Pembayaran berhasil diverifikasi.',
                ];
            } else {
                // Reject payment
                $payment->update([
                    'status' => Payment::STATUS_FAILED,
                    'verification_status' => Payment::VERIFICATION_REJECTED,
                    'verified_by' => $adminId,
                    'verified_at' => now(),
                    'verification_notes' => $notes ?? 'Bukti pembayaran tidak valid.',
                ]);

                DB::commit();

                return [
                    'success' => true,
                    'payment_id' => $payment->id,
                    'status' => $payment->status,
                    'message' => 'Pembayaran ditolak.',
                ];
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Failed to verify payment: ' . $e->getMessage());
        }
    }

    /**
     * Verify payment status
     */
    public function verifyPaymentStatus($orderId)
    {
        try {
            $payment = Payment::where('order_id', $orderId)
                ->latest()
                ->first();

            if (!$payment) {
                throw new Exception('Payment not found');
            }

            return [
                'payment_id' => $payment->id,
                'order_id' => $orderId,
                'status' => $payment->status,
                'verification_status' => $payment->verification_status,
                'amount' => $payment->amount,
                'payment_type' => $payment->payment_type,
                'bank_name' => $payment->bank_name,
                'account_holder' => $payment->account_holder,
                'transfer_date' => $payment->transfer_date,
                'verified_at' => $payment->verified_at,
                'paid_at' => $payment->paid_at,
            ];
        } catch (Exception $e) {
            throw new Exception('Failed to verify payment: ' . $e->getMessage());
        }
    }

    /**
     * Get payment by order ID
     */
    public function getPaymentByOrderId($orderId)
    {
        return Payment::where('order_id', $orderId)
            ->with('order')
            ->latest()
            ->get();
    }

    /**
     * Get payment detail
     */
    public function getPaymentDetail($paymentId)
    {
        return Payment::with('order')
            ->findOrFail($paymentId);
    }

    /**
     * List all payments (for admin)
     */
    public function listPayments($filters = [], $limit = 20, $page = 1)
    {
        $query = Payment::with('order.user');

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['payment_type'])) {
            $query->where('payment_type', $filters['payment_type']);
        }

        if (isset($filters['order_id'])) {
            $query->where('order_id', $filters['order_id']);
        }

        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);
    }

    /**
     * Calculate remaining payment amount
     */
    public function calculateRemainingAmount($order)
    {
        if ($order->payment_type !== Order::PAYMENT_DP) {
            return 0;
        }

        // Get already paid amount
        $paidAmount = $order->payments()
            ->where('status', Payment::STATUS_SUCCESS)
            ->sum('amount');

        return max(0, $order->total_price - $paidAmount);
    }

    /**
     * Get payment statistics
     */
    public function getPaymentStats()
    {
        $today = now()->startOfDay();
        $monthStart = now()->startOfMonth();

        return [
            'total_transactions' => Payment::count(),
            'successful_today' => Payment::where('status', Payment::STATUS_SUCCESS)
                ->whereDate('created_at', $today)
                ->count(),
            'successful_month' => Payment::where('status', Payment::STATUS_SUCCESS)
                ->whereBetween('created_at', [$monthStart, now()->endOfMonth()])
                ->count(),
            'total_revenue_today' => Payment::where('status', Payment::STATUS_SUCCESS)
                ->whereDate('created_at', $today)
                ->sum('amount'),
            'total_revenue_month' => Payment::where('status', Payment::STATUS_SUCCESS)
                ->whereBetween('created_at', [$monthStart, now()->endOfMonth()])
                ->sum('amount'),
            'total_revenue_all' => Payment::where('status', Payment::STATUS_SUCCESS)
                ->sum('amount'),
            'pending_payments' => Payment::where('status', Payment::STATUS_PENDING)->count(),
            'failed_payments' => Payment::where('status', Payment::STATUS_FAILED)->count(),
        ];
    }
}
