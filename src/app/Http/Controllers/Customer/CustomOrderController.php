<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomOrder;
use App\Models\CustomOrderFile;
use App\Models\Order;
use App\Services\FileUploadService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomOrderController extends Controller
{
    public function __construct(
        protected OrderService     $orderService,
        protected FileUploadService $uploadService
    ) {}

    /**
     * Form pesanan custom
     */
    public function create()
    {
        return view('orders.custom-create');
    }

    /**
     * Simpan pesanan custom
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'material'         => ['required', 'string', 'max:100'],
            'ornament_level'   => ['required', 'string', 'max:100'],
            'width'            => ['required', 'numeric', 'min:0.1'],
            'height'           => ['required', 'numeric', 'min:0.1'],
            'color_preference' => ['nullable', 'string', 'max:255'],
            'deadline'         => ['nullable', 'date', 'after:today'],
            'address'          => ['required', 'string', 'max:500'],
            'description'      => ['required_without:brief', 'string'],
            'brief'            => ['nullable', 'string'],
            'payment_type'     => ['required', 'in:full,dp'],
            'notes'            => ['nullable', 'string', 'max:1000'],
            'files'            => ['nullable', 'array'],
            'files.*'          => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $description = $request->input('description') ?: $request->input('brief');

        try {
            // Buat order utama dengan harga 0 (akan di-quote oleh admin)
            $order = Order::create([
                'user_id'        => Auth::id(),
                'order_type'     => Order::ORDER_TYPE_CUSTOM,
                'status'         => Order::STATUS_PENDING,
                'total_price'    => 0,
                'payment_type'   => $request->payment_type,
                'payment_status' => Order::PAYMENT_STATUS_UNPAID,
                'notes'          => $request->notes,
            ]);

            // Buat custom order
            $customOrder = CustomOrder::create([
                'order_id'         => $order->id,
                'name'             => $request->name,
                'description'      => $description,
                'material'         => $request->material,
                'ornament_level'   => $request->ornament_level,
                'width'            => $request->width,
                'height'           => $request->height,
                'color_preference' => $request->color_preference,
                'deadline'         => $request->deadline,
                'address'          => $request->address,
                'brief'            => $request->brief,
            ]);

            // Upload file referensi
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $path = $this->uploadService->upload($file, 'custom-referensi');
                    CustomOrderFile::create([
                        'custom_order_id' => $customOrder->id,
                        'file_url'        => $path,
                        'file_type'       => $file->getClientMimeType(),
                    ]);
                }
            }

            return redirect()->route('custom-orders.show', $order->id)
                ->with('success', 'Pesanan custom berhasil dikirim! Admin akan menghubungi Anda untuk konfirmasi harga.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Detail pesanan custom
     */
    public function show(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        abort_if($order->order_type !== Order::ORDER_TYPE_CUSTOM, 404);

        $order->load(['customOrder.files', 'payments', 'result']);

        return view('orders.custom-show', compact('order'));
    }

    public function cancel(Order $order)
{
    abort_if($order->user_id !== Auth::id(), 403);
    abort_if($order->order_type !== Order::ORDER_TYPE_CUSTOM, 404);

    if ($order->status !== Order::STATUS_PENDING) {
        return back()->with('error', 'Pesanan tidak dapat dibatalkan.');
    }

    if ($order->customOrder) {

        foreach ($order->customOrder->files as $file) {
            $this->uploadService->delete($file->file_url);
            $file->delete();
        }

        $order->customOrder->delete();
    }

    $order->delete();

    return redirect()
        ->route('orders.index')
        ->with('success', 'Pesanan custom berhasil dibatalkan.');
}

    /**
     * Upload file tambahan untuk pesanan custom
     */
    public function uploadFiles(Request $request, CustomOrder $customOrder)
    {
        abort_if($customOrder->order->user_id !== Auth::id(), 403);

        $request->validate([
            'files'   => ['required', 'array'],
            'files.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        try {
            foreach ($request->file('files') as $file) {
                $path = $this->uploadService->upload($file, 'custom-referensi');
                CustomOrderFile::create([
                    'custom_order_id' => $customOrder->id,
                    'file_url'        => $path,
                    'file_type'       => $file->getClientMimeType(),
                ]);
            }

            return back()->with('success', 'File berhasil diupload.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal upload file: ' . $e->getMessage());
        }
    }
}
