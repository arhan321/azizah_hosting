<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::with('category')->orderBy('order')->latest()->paginate(20);
        return view('admin.portfolio.index', compact('portfolios'));
    }

    public function create()
{
    $categories = Category::all();

    return view('admin.portfolio.create', compact('categories'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'category_id' => 'nullable|exists:categories,id',
            'client_name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'completion_date' => 'nullable|date',
            'is_featured' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('portfolios', 'public');
            $validated['image_url'] = Storage::url($path);
        }

        Portfolio::create($validated);

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Portofolio berhasil ditambahkan');
    }

    public function edit(Portfolio $portfolio)
{
    $categories = Category::all();

    return view(
        'admin.portfolio.edit',
        compact('portfolio', 'categories')
    );
}

    public function update(Request $request, Portfolio $portfolio)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'category_id' => 'nullable|exists:categories,id',
            'client_name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'completion_date' => 'nullable|date',
            'is_featured' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($portfolio->image_url) {
                $oldPath = str_replace('/storage/', '', $portfolio->image_url);
                Storage::disk('public')->delete($oldPath);
            }
            
            $path = $request->file('image')->store('portfolios', 'public');
            $validated['image_url'] = Storage::url($path);
        }

        $portfolio->update($validated);

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Portofolio berhasil diperbarui');
    }

    public function destroy(Portfolio $portfolio)
    {
        // Hapus gambar
        if ($portfolio->image_url) {
            $path = str_replace('/storage/', '', $portfolio->image_url);
            Storage::disk('public')->delete($path);
        }

        $portfolio->delete();

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Portofolio berhasil dihapus');
    }
}
