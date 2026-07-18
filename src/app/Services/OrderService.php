<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Design;
use App\Constants\AppConstants;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderService
{
    /**
     * Create catalog order
     */
    public function createCatalogOrder($userId, $paymentType, $items)
    {
        try {
            DB::beginTransaction();

            $totalPrice = 0;
            $validatedItems = [];

            foreach ($items as $item) {
                $design = Design::findOrFail($item['design_id']);
                // Use provided price (already includes qty multiplier) or fallback to design price
                $itemPrice = isset($item['price']) && $item['price'] > 0
                    ? (float)$item['price']
                    : (float)$design->price;

                $totalPrice += $itemPrice;

                $validatedItems[] = [
                    'design_id'          => $design->id,
                    'price'              => $itemPrice,
                    'customization_data' => $item['customization_data'] ?? null,
                ];
            }

            // Create order
            $order = Order::create([
                'user_id'        => $userId,
                'order_type'     => Order::ORDER_TYPE_CATALOG,
                'status'         => Order::STATUS_PENDING,
                'total_price'    => $totalPrice,
                'payment_type'   => $paymentType,
                'payment_status' => Order::PAYMENT_STATUS_UNPAID,
            ]);

            // Create order items
            foreach ($validatedItems as $item) {
                OrderItem::create([
                    'order_id'           => $order->id,
                    'design_id'          => $item['design_id'],
                    'price'              => $item['price'],
                    'customization_data' => $item['customization_data'],
                ]);
            }

            DB::commit();

            return $this->getOrderWithRelations($order->id);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get order with all relations
     */
    public function getOrderWithRelations($orderId)
    {
        return Order::with([
            'user',
            'items.design.category',
            'customOrder.files',
            'payments',
            'result',
        ])->findOrFail($orderId);
    }

    /**
     * Get user orders with pagination
     */
    public function getUserOrders($userId, $status = null, $type = null, $limit = 10, $page = 1)
    {
        $query = Order::where('user_id', $userId)
            ->with([
                'items.design',
                'customOrder',
                'payments',
                'result',
            ]);

        if ($status) {
            $query->where('status', $status);
        }

        if ($type) {
            $query->where('order_type', $type);
        }

        return $query->orderBy('created_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);
    }

    /**
     * Update order status
     */
    public function updateOrderStatus($orderId, $newStatus)
    {
        $order = Order::findOrFail($orderId);

        // Validate status transition
        $validStatuses = AppConstants\OrderStatus::all();
        if (!in_array($newStatus, $validStatuses)) {
            throw new Exception("Invalid status: {$newStatus}");
        }

        $order->update(['status' => $newStatus]);

        return $this->getOrderWithRelations($orderId);
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus($orderId, $paymentStatus)
    {
        $order = Order::findOrFail($orderId);

        $validStatuses = AppConstants\PaymentStatus::all();
        if (!in_array($paymentStatus, $validStatuses)) {
            throw new Exception("Invalid payment status: {$paymentStatus}");
        }

        // If payment is fully paid, update order status to dikerjakan
        if ($paymentStatus === Order::PAYMENT_STATUS_FULLY_PAID) {
            $order->update([
                'payment_status' => $paymentStatus,
                'status' => Order::STATUS_DIKERJAKAN,
            ]);
        } else {
            $order->update(['payment_status' => $paymentStatus]);
        }

        return $this->getOrderWithRelations($orderId);
    }

    /**
     * Calculate amount due based on payment type
     */
    public function calculateAmountDue($order)
    {
        if ($order->payment_type === Order::PAYMENT_DP) {
            return $order->total_price * 0.5;
        }
        return $order->total_price;
    }

    /**
     * Check if order can be paid
     */
    public function canPayOrder($orderId)
    {
        $order = Order::findOrFail($orderId);

        if ($order->status !== Order::STATUS_PENDING) {
            throw new Exception('Order is not in pending status');
        }

        if ($order->payment_status === Order::PAYMENT_STATUS_FULLY_PAID) {
            throw new Exception('Order already fully paid');
        }

        return true;
    }

    /**
     * Get order by ID
     */
    public function getOrderById($orderId)
    {
        return Order::with([
            'user',
            'items.design.category',
            'customOrder.files',
            'payments',
            'result',
        ])->findOrFail($orderId);
    }

    /**
     * Get all orders for admin
     */
    public function getAllOrders($filters = [], $limit = 20, $page = 1)
    {
        $query = Order::with([
            'user',
            'items.design',
            'customOrder',
            'payments',
            'result',
        ]);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['order_type'])) {
            $query->where('order_type', $filters['order_type']);
        }

        if (isset($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
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
     * Get dashboard statistics
     */
    public function getDashboardStats()
    {
        $today = now()->startOfDay();
        $monthStart = now()->startOfMonth();

        return [
            'total_orders_today' => Order::whereDate('created_at', $today)->count(),
            'total_orders_month' => Order::whereBetween('created_at', [
                $monthStart,
                now()->endOfMonth(),
            ])->count(),
            'total_orders_all' => Order::count(),
            'total_revenue_today' => Order::whereDate('created_at', $today)
                ->where('payment_status', Order::PAYMENT_STATUS_FULLY_PAID)
                ->sum('total_price'),
            'total_revenue_month' => Order::whereBetween('created_at', [
                $monthStart,
                now()->endOfMonth(),
            ])
                ->where('payment_status', Order::PAYMENT_STATUS_FULLY_PAID)
                ->sum('total_price'),
            'total_revenue_all' => Order::where('payment_status', Order::PAYMENT_STATUS_FULLY_PAID)
                ->sum('total_price'),
            'pending_orders' => Order::where('status', Order::STATUS_PENDING)->count(),
            'in_progress_orders' => Order::where('status', Order::STATUS_DIKERJAKAN)->count(),
            'completed_orders' => Order::where('status', Order::STATUS_SELESAI)->count(),
            'pending_custom_approvals' => Order::where('order_type', Order::ORDER_TYPE_CUSTOM)
                ->where('status', Order::STATUS_PENDING)
                ->count(),
        ];
    }

    /**
     * Get monthly sales data
     */
    public function getMonthlySalesData($months = 12)
    {
        $data = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthStart = $date->startOfMonth();
            $monthEnd = $date->endOfMonth();

            $data[] = [
                'month' => $date->format('Y-m'),
                'count' => Order::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'revenue' => Order::whereBetween('created_at', [$monthStart, $monthEnd])
                    ->where('payment_status', Order::PAYMENT_STATUS_FULLY_PAID)
                    ->sum('total_price'),
            ];
        }

        return $data;
    }

    /**
     * Get top designs
     */
    public function getTopDesigns($limit = 5)
    {
        return Design::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->limit($limit)
            ->get();
    }
}
