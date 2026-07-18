<?php

namespace App\Services;

use App\Models\Order;
use App\Models\CustomOrder;
use App\Models\CustomOrderFile;
use App\Models\CustomerCredit;
use App\Constants\AppConstants;
use Illuminate\Support\Facades\DB;
use Exception;

class CustomOrderService
{
    /**
     * Submit custom order
     */
    public function submitCustomOrder($userId, $data)
    {
        try {
            DB::beginTransaction();

            // Create order (status pending, menunggu admin approval)
            $order = Order::create([
                'user_id' => $userId,
                'order_type' => Order::ORDER_TYPE_CUSTOM,
                'status' => Order::STATUS_PENDING,
                'total_price' => 0, // Will be set by admin
                'payment_type' => Order::PAYMENT_FULL,
                'payment_status' => Order::PAYMENT_STATUS_UNPAID,
            ]);

            // Create custom order detail
            $customOrder = CustomOrder::create([
                'order_id' => $order->id,
                'name' => $data['name'],
                'description' => $data['description'],
                'dimensions' => $data['dimensions'] ?? null,
                'color_preference' => $data['color_preference'] ?? null,
                'deadline' => $data['deadline'] ?? null,
                'brief' => $data['brief'] ?? null,
            ]);

            // Handle file uploads jika ada
            if (isset($data['files']) && count($data['files']) > 0) {
                foreach ($data['files'] as $file) {
                    CustomOrderFile::create([
                        'custom_order_id' => $customOrder->id,
                        'file_url' => $file['url'],
                        'file_name' => $file['name'],
                        'file_size' => $file['size'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return $this->getCustomOrderWithRelations($order->id);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get custom order with all relations
     */
    public function getCustomOrderWithRelations($orderId)
    {
        return Order::with([
            'user',
            'customOrder.files',
            'payments',
            'result',
        ])->findOrFail($orderId);
    }

    /**
     * Upload custom order files
     */
    public function uploadCustomOrderFiles($customOrderId, $files)
    {
        try {
            $customOrder = CustomOrder::findOrFail($customOrderId);

            foreach ($files as $file) {
                CustomOrderFile::create([
                    'custom_order_id' => $customOrder->id,
                    'file_url' => $file['url'],
                    'file_name' => $file['name'],
                    'file_size' => $file['size'] ?? null,
                ]);
            }

            return $customOrder->fresh()->load('files');
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get pending custom orders (for admin approval)
     */
    public function getPendingCustomOrders($limit = 10, $page = 1)
    {
        return Order::where('order_type', Order::ORDER_TYPE_CUSTOM)
            ->where('status', Order::STATUS_PENDING)
            ->with([
                'user',
                'customOrder.files',
                'payments',
            ])
            ->orderBy('created_at', 'asc')
            ->paginate($limit, ['*'], 'page', $page);
    }

    /**
     * Approve custom order
     */
    public function approveCustomOrder($orderId, $adminQuote, $adminNotes = null)
    {
        try {
            DB::beginTransaction();

            $order = Order::findOrFail($orderId);

            // Validate order is custom and pending
            if ($order->order_type !== Order::ORDER_TYPE_CUSTOM) {
                throw new Exception('Order is not a custom order');
            }

            if ($order->status !== Order::STATUS_PENDING) {
                throw new Exception('Order is not in pending status');
            }

            // Update custom order with admin quote
            $customOrder = $order->customOrder;
            $customOrder->update([
                'admin_quote' => $adminQuote,
                'admin_notes' => $adminNotes,
            ]);

            // Update order with price and status
            $order->update([
                'total_price' => $adminQuote,
                'status' => Order::STATUS_APPROVED,
            ]);

            DB::commit();

            return $this->getCustomOrderWithRelations($orderId);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Reject custom order (convert payment to credit)
     */
    public function rejectCustomOrder($orderId, $feedback = null)
    {
        try {
            DB::beginTransaction();

            $order = Order::findOrFail($orderId);

            // Validate order is custom and pending
            if ($order->order_type !== Order::ORDER_TYPE_CUSTOM) {
                throw new Exception('Order is not a custom order');
            }

            // If there are payments, convert to credit
            if ($order->payments()->where('status', 'success')->exists()) {
                $totalPaid = $order->payments()
                    ->where('status', 'success')
                    ->sum('amount');

                // Create customer credit
                CustomerCredit::create([
                    'user_id' => $order->user_id,
                    'amount' => $totalPaid,
                    'reason' => "Rejected custom order #{$order->id}",
                    'custom_order_id' => $order->customOrder->id,
                ]);
            }

            // Update custom order
            $customOrder = $order->customOrder;
            $customOrder->update([
                'admin_notes' => $feedback ?? 'Custom order rejected',
            ]);

            // Update order status (soft delete or mark as cancelled)
            $order->update([
                'status' => 'cancelled', // Add this status if needed
                'notes' => $feedback ?? 'Custom order rejected',
            ]);

            DB::commit();

            return $this->getCustomOrderWithRelations($orderId);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get custom order by ID
     */
    public function getCustomOrderById($orderId)
    {
        return Order::where('order_type', Order::ORDER_TYPE_CUSTOM)
            ->with([
                'user',
                'customOrder.files',
                'payments',
                'result',
            ])
            ->findOrFail($orderId);
    }

    /**
     * List custom orders (for admin)
     */
    public function listCustomOrders($filters = [], $limit = 10, $page = 1)
    {
        $query = Order::where('order_type', Order::ORDER_TYPE_CUSTOM)
            ->with([
                'user',
                'customOrder.files',
                'payments',
                'result',
            ]);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('customOrder', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);
    }

    /**
     * Get custom order details for customer
     */
    public function getCustomOrderForCustomer($orderId, $userId)
    {
        return Order::where('id', $orderId)
            ->where('user_id', $userId)
            ->where('order_type', Order::ORDER_TYPE_CUSTOM)
            ->with([
                'customOrder.files',
                'payments',
                'result',
            ])
            ->firstOrFail();
    }

    /**
     * Check if custom order can be paid
     */
    public function canPayCustomOrder($orderId)
    {
        $order = Order::findOrFail($orderId);

        if ($order->order_type !== Order::ORDER_TYPE_CUSTOM) {
            throw new Exception('Order is not a custom order');
        }

        if ($order->status !== Order::STATUS_APPROVED) {
            throw new Exception('Custom order must be approved before payment');
        }

        if ($order->payment_status === Order::PAYMENT_STATUS_FULLY_PAID) {
            throw new Exception('Order already fully paid');
        }

        if (!$order->customOrder->admin_quote) {
            throw new Exception('Admin quote not set for this custom order');
        }

        return true;
    }

    /**
     * Get custom order statistics
     */
    public function getCustomOrderStats()
    {
        return [
            'total_custom_orders' => Order::where('order_type', Order::ORDER_TYPE_CUSTOM)->count(),
            'pending_approval' => Order::where('order_type', Order::ORDER_TYPE_CUSTOM)
                ->where('status', Order::STATUS_PENDING)
                ->count(),
            'approved' => Order::where('order_type', Order::ORDER_TYPE_CUSTOM)
                ->where('status', Order::STATUS_APPROVED)
                ->count(),
            'in_progress' => Order::where('order_type', Order::ORDER_TYPE_CUSTOM)
                ->where('status', Order::STATUS_DIKERJAKAN)
                ->count(),
            'completed' => Order::where('order_type', Order::ORDER_TYPE_CUSTOM)
                ->where('status', Order::STATUS_SELESAI)
                ->count(),
        ];
    }
}
