<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Design;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Daftar semua desain (katalog)
     */
    public function index(Request $request)
    {
        $query = Design::with('category');

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default      => $query->latest(),
        };

        $designs    = $query->paginate(12)->withQueryString();
        $categories = Category::withCount('designs')->orderBy('name')->get();

        return view('catalog.index', compact('designs', 'categories'));
    }

    /**
     * Detail desain
     */
    public function show(string $slug)
    {
        $design   = Design::with('category')->where('slug', $slug)->firstOrFail();
        $related  = Design::with('category')
            ->where('category_id', $design->category_id)
            ->where('id', '!=', $design->id)
            ->limit(4)
            ->get();

        return view('catalog.show', compact('design', 'related'));
    }

    public function portfolio(Request $request)
    {
        $portfolios = \App\Models\Portfolio::orderBy('order')->latest()->paginate(16);

        return view('catalog.portfolio', compact('portfolios'));
    }
}
