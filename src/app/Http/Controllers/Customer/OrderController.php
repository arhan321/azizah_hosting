<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Design;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\NotificationService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected NotificationService $notificationService
    ) {}

    /**
     * Daftar semua pesanan pelanggan
     */
    public function index(Request $request)
    {
        $this->notificationService->markAllAsRead(Auth::id());

        $status = $request->get('status');
        $type   = $request->get('type');

        $query = Order::where('user_id', Auth::id())
            ->with(['items.design', 'customOrder', 'payments', 'result'])
            ->latest();

        if ($status) {
            $query->where('status', $status);
        }
        if ($type) {
            $query->where('order_type', $type);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('orders.index', compact('orders', 'status', 'type'));
    }

    /**
     * Form pemesanan baru
     */
    public function create(Request $request)
    {
        $categories = Category::with('designs')->orderBy('name')->get();

        $designs = Design::with('category')->orderBy('name')->get();

        $selectedDesignId = $request->get('design_id');

        $selectedCategory = $request->get('category');

        return view('orders.create',compact('categories','designs','selectedDesignId','selectedCategory'));
    }

    /**
     * Simpan pesanan baru (katalog)
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_type'      => ['required', 'in:catalog,custom'],
            'payment_type'    => ['required', 'in:full,dp'],
            'items'           => ['required_if:order_type,catalog', 'array', 'min:1'],
            'items.*.design_id'     => ['required_if:order_type,catalog', 'exists:designs,id'],
            'items.*.size'          => ['nullable', 'string'],
            'items.*.color'         => ['nullable', 'string', 'max:100'],
            'items.*.custom_color'  => ['nullable', 'string', 'max:100'],
            'items.*.qty'           => ['nullable', 'integer', 'min:1'],
            'items.*.price'         => ['nullable', 'numeric', 'min:0'],
            'notes'           => ['nullable', 'string', 'max:1000'],
            'address'         => ['required', 'string', 'max:500'],
        ]);

        try {

            // SIMPAN KE KERANJANG
            if ($request->save_to_cart == 1) {

                $cart = session()->get('cart', []);

                foreach ($request->items as $item) {

                    $design = Design::find($item['design_id']);

                    $cart[] = [

                        'design_id' => $item['design_id'],
                        'name'      => $design->name,
                        'image'     => $design->image_url,
                        'price'     => $item['price'],
                        'size'      => $item['size'],
                        'color'     => $item['color'],
                        'qty'       => $item['qty'],
                        'address'   => $request->address,
                        'notes'     => $request->notes,
                        'payment_type' => $request->payment_type,

                    ];
                }

                session()->put('cart', $cart);

                return redirect()
                    ->route('cart.index')
                    ->with('success', 'Pesanan berhasil masuk keranjang');
            }

            // BUAT PESANAN LANGSUNG
            $items = collect($request->items)->map(function ($item) {

                $design = Design::findOrFail($item['design_id']);

                $qty = max(1, (int)($item['qty'] ?? 1));

                $price = isset($item['price']) && $item['price'] > 0
                    ? (float)$item['price']
                    : (float)$design->price;

                $color = ($item['color'] ?? '') === 'custom'
                    ? ($item['custom_color'] ?? 'Custom')
                    : ($item['color'] ?? null);

                return [

                    'design_id' => $design->id,

                    'price' => $price * $qty,

                    'qty' => $qty,

                    'customization_data' => [
                        'size'  => $item['size'] ?? null,
                        'color' => $color,
                        'qty'   => $qty,
                    ],

                ];
            })->toArray();

            $order = $this->orderService->createCatalogOrder(
                Auth::id(),
                $request->payment_type,
                $items
            );

            $order->update([
                'notes'   => $request->notes,
                'address' => $request->address,
            ]);

            session()->forget('cart');

            return redirect()
                ->route('orders.show', $order->id)
                ->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {

            return back()->withInput()
                ->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Detail pesanan
     */
    public function show(Order $order)
    {
        // Pastikan order milik user yang login
        abort_if($order->user_id !== Auth::id(), 403);

        $order->load(['items.design.category', 'customOrder.files', 'payments', 'result']);

        return view('orders.show', compact('order'));
    }

    /**
     * Batalkan pesanan
     */
    public function cancel(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);

        if (!in_array($order->status, [Order::STATUS_PENDING])) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan pada status saat ini.');
        }

        $order->update([
            'status' => Order::STATUS_CANCELLED,
            'payment_status' => Order::PAYMENT_STATUS_UNPAID,
        ]);

        return redirect()->route('orders.index')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }
    public function checkoutCart(Request $request)
    {
        $cart = session('cart', []);

        if (count($cart) == 0) {

            return back()->with(
                'error',
                'Keranjang kosong'
            );
        }

        $totalPrice = 0;

        foreach ($cart as $cartItem) {

            $totalPrice += (
                ($cartItem['price'] ?? 0)
                *
                ($cartItem['qty'] ?? 1)
            );
        }

        $order = Order::create([

            'user_id' => Auth::id(),

            'order_type' => 'catalog',

            'total_price' => $totalPrice,

            'address' => $cart[0]['address'] ?? '-',

            'notes' => $cart[0]['notes'] ?? null,

            'status' => 'pending',

            'payment_status' => 'unpaid',

            'payment_type' => 'full',

            'payment_type' => $cart[0]['payment_type'] ?? 'full',

        ]);

        foreach ($cart as $cartItem) {

            OrderItem::create([

                'order_id' => $order->id,

                'design_id' => $cartItem['design_id'],

                'price' => $cartItem['price'],

                'customization_data' => [

                    'size' => $cartItem['size'] ?? null,

                    'color' => $cartItem['color'] ?? null,

                    'qty' => $cartItem['qty'] ?? 1,

                ],

            ]);
        }

        session()->forget('cart');

        return redirect()
            ->route('orders.show', $order->id)
            ->with(
                'success',
                'Pesanan berhasil dikonfirmasi'
            );
    }
}
