<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Portfolio;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Throwable;

class PortfolioController extends Controller
{
    public function __construct(protected FileUploadService $uploadService) {}

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

        $image = $validated['image'];
        unset($validated['image']);

        $imagePath = null;

        try {
            $imagePath = $this->uploadService->uploadTo($image, 'portfolios');
            $validated['image_url'] = $imagePath;

            Portfolio::create($validated);
        } catch (Throwable $exception) {
            $this->uploadService->delete($imagePath);

            throw $exception;
        }

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

        $image = $validated['image'] ?? null;
        unset($validated['image']);

        $oldImagePath = $portfolio->getRawOriginal('image_url');

        $newImagePath = null;

        try {
            if ($image) {
                $newImagePath = $this->uploadService->uploadTo(
                    $image,
                    'portfolios'
                );
                $validated['image_url'] = $newImagePath;
            }

            $portfolio->update($validated);
        } catch (Throwable $exception) {
            $this->uploadService->delete($newImagePath);

            throw $exception;
        }

        if ($newImagePath !== null && $oldImagePath !== $newImagePath) {
            $this->uploadService->delete($oldImagePath);
        }

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Portofolio berhasil diperbarui');
    }

    public function destroy(Portfolio $portfolio)
    {
        $imagePath = $portfolio->getRawOriginal('image_url');

        $portfolio->delete();

        $this->uploadService->delete($imagePath);

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Portofolio berhasil dihapus');
    }
}