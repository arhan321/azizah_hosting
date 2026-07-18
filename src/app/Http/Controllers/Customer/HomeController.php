<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Design;

class HomeController extends Controller
{
    /**
     * Halaman utama / landing page
     */
    public function index()
    {
        $featuredDesigns = Design::with('category')
            ->latest()
            ->limit(6)
            ->get();

        $categories = Category::withCount('designs')
    ->get()
    ->filter(fn($cat) => $cat->designs_count > 0);

        return view('home', compact('featuredDesigns', 'categories'));
    }

    /**
     * Halaman konsultasi — link ke WhatsApp
     */
    public function konsultasi()
    {
        return view('konsultasi');
    }
}
