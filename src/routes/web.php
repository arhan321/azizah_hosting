<?php

use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\CatalogController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\CustomOrderController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\CatalogController as AdminCatalog;
use App\Http\Controllers\Admin\OrderController as AdminOrder;
use App\Http\Controllers\Admin\CustomerController as AdminCustomer;
use App\Http\Controllers\Admin\PaymentController as AdminPayment;
use App\Http\Controllers\Admin\ReportController as AdminReport;
use App\Http\Controllers\Admin\PortfolioController as AdminPortfolio;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Aqlam Mural Kaligrafi
|--------------------------------------------------------------------------
*/

// ─── Public Routes ─────────────────────────────────────────────────────────

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/katalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/katalog/{slug}', [CatalogController::class, 'show'])->name('catalog.show');
Route::get('/portofolio', [CatalogController::class, 'portfolio'])->name('portfolio');
Route::get('/tentang-kami', function () {
    return view('about');
})->name('about');
Route::get('/konsultasi', [HomeController::class, 'konsultasi'])->name('konsultasi');

// ─── Customer Routes (auth required) ────────────────────────────────────────

Route::middleware(['auth'])->group(function () {
    Route::post(
        '/cart/remove/{index}',
        function ($index) {

            $cart = session()->get('cart', []);

            unset($cart[$index]);

            session()->put(
                'cart',
                array_values($cart)
            );

            return back();
        }
    )->name('cart.remove');

    // Keranjang
    Route::get('/keranjang', function () {

        $cart = session()->get('cart', []);

        return view('customer.cart', compact('cart'));
    })->name('cart.index');

    Route::post('/cart/add', [OrderController::class, 'addToCart'])
        ->name('cart.add');

    Route::post('/cart/checkout', [OrderController::class, 'checkoutCart'])
        ->name('cart.checkout');

    // Pesanan Katalog
    Route::prefix('pesanan')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/buat', [OrderController::class, 'create'])->name('create');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::delete('/{order}', [OrderController::class, 'cancel'])->name('cancel');
    });

    // Pesanan Custom
    Route::prefix('pesanan-custom')->name('custom-orders.')->group(function () {
        Route::get('/buat', [CustomOrderController::class, 'create'])->name('create');
        Route::post('/', [CustomOrderController::class, 'store'])->name('store');
        Route::get('/{order}', [CustomOrderController::class, 'show'])->name('show');
        Route::delete('/{order}', [CustomOrderController::class, 'cancel'])->name('cancel');
        Route::post('/{customOrder}/upload', [CustomOrderController::class, 'uploadFiles'])->name('upload');
    });

    // Pembayaran
    Route::prefix('pembayaran')->name('payments.')->group(function () {
        Route::get('/{order}', [PaymentController::class, 'show'])->name('show');
        Route::get('/{order}/detail/{payment}', [PaymentController::class, 'detail'])->name('detail');
        Route::post('/{order}', [PaymentController::class, 'process'])->name('process');
        Route::get('/{order}/selesai', [PaymentController::class, 'finish'])->name('finish');
        Route::get('/{order}/pending', [PaymentController::class, 'pending'])->name('pending');
        Route::get('/{order}/gagal', [PaymentController::class, 'failed'])->name('failed');
    });

    // Profil pelanggan
    Route::prefix('profil')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
        Route::patch('/password', [ProfileController::class, 'updatePassword'])->name('password');
    });
});

// ─── Admin Routes ────────────────────────────────────────────────────────────

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');

    // Kelola Katalog
    Route::prefix('katalog')->name('catalog.')->group(function () {
        Route::get('/', [AdminCatalog::class, 'index'])->name('index');
        Route::get('/tambah', [AdminCatalog::class, 'create'])->name('create');
        Route::post('/', [AdminCatalog::class, 'store'])->name('store');
        Route::get('/{design}/edit', [AdminCatalog::class, 'edit'])->name('edit');
        Route::put('/{design}', [AdminCatalog::class, 'update'])->name('update');
        Route::delete('/{design}', [AdminCatalog::class, 'destroy'])->name('destroy');

        // Kategori
        Route::prefix('kategori')->name('categories.')->group(function () {
            Route::get('/', [AdminCatalog::class, 'categories'])->name('index');
            Route::post('/', [AdminCatalog::class, 'storeCategory'])->name('store');
            Route::put('/{category}', [AdminCatalog::class, 'updateCategory'])->name('update');
            Route::delete('/{category}', [AdminCatalog::class, 'destroyCategory'])->name('destroy');
        });
    });

    // Kelola Portofolio
    Route::prefix('portofolio')->name('portfolio.')->group(function () {
        Route::get('/', [AdminPortfolio::class, 'index'])->name('index');
        Route::get('/tambah', [AdminPortfolio::class, 'create'])->name('create');
        Route::post('/', [AdminPortfolio::class, 'store'])->name('store');
        Route::get('/{portfolio}/edit', [AdminPortfolio::class, 'edit'])->name('edit');
        Route::put('/{portfolio}', [AdminPortfolio::class, 'update'])->name('update');
        Route::delete('/{portfolio}', [AdminPortfolio::class, 'destroy'])->name('destroy');
    });

    // Kelola Pesanan
    Route::prefix('pesanan')->name('orders.')->group(function () {
        Route::get('/', [AdminOrder::class, 'index'])->name('index');
        Route::get('/{order}', [AdminOrder::class, 'show'])->name('show');
        Route::patch('/{order}/status', [AdminOrder::class, 'updateStatus'])->name('updateStatus');
        Route::post('/{order}/hasil', [AdminOrder::class, 'uploadResult'])->name('uploadResult');
        Route::post('/{order}/quote', [AdminOrder::class, 'setQuote'])->name('setQuote');
    });

    // Kelola Pelanggan
    Route::prefix('pelanggan')->name('customers.')->group(function () {
        Route::get('/', [AdminCustomer::class, 'index'])->name('index');
        Route::get('/{user}', [AdminCustomer::class, 'show'])->name('show');
    });

    // Kelola Pembayaran
    Route::prefix('pembayaran')->name('payments.')->group(function () {
        Route::get('/', [AdminPayment::class, 'index'])->name('index');
        Route::patch('/{payment}/konfirmasi', [AdminPayment::class, 'confirm'])->name('confirm');
        Route::patch('/{payment}/tolak', [AdminPayment::class, 'reject'])->name('reject');
    });

    // Laporan
    Route::prefix('laporan')->name('reports.')->group(function () {
        Route::get('/', [AdminReport::class, 'index'])->name('index');
        Route::get('/cetak', [AdminReport::class, 'print'])->name('print');
        Route::get('/export', [AdminReport::class, 'export'])->name('export');
    });
});

// ─── Auth Routes ─────────────────────────────────────────────────────────────
require __DIR__ . '/auth.php';
