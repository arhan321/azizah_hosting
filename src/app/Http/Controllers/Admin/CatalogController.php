<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Design;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class CatalogController extends Controller
{
    public function __construct(protected FileUploadService $uploadService) {}

    // ─── Designs ─────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Design::with('category');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $designs    = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.catalog.index', compact('designs', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.catalog.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id'  => ['required', 'exists:categories,id'],
            'name'         => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'specification' => ['nullable', 'string'], // tambahkan ini
            'price'        => ['required', 'numeric', 'min:0'],
            'image'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $imagePath = null;

        try {
            if ($request->hasFile('image')) {
                $imagePath = $this->uploadService->upload(
                    $request->file('image'),
                    'designs'
                );
            }

            Design::create([
                'category_id' => $request->category_id,
                'name'        => $request->name,
                'slug'        => Str::slug($request->name).'-'.Str::random(5),
                'description' => $this->sanitizeRichText($request->description),
                'specification' => $this->sanitizeRichText($request->specification),
                'price'       => $request->price,
                'image_url'   => $imagePath,
            ]);
        } catch (Throwable $exception) {
            $this->uploadService->delete($imagePath);

            throw $exception;
        }

        return redirect()->route('admin.catalog.index')
            ->with('success', 'Desain berhasil ditambahkan.');
    }

    public function edit(Design $design)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.catalog.edit', compact('design', 'categories'));
    }

    public function update(Request $request, Design $design)
    {
        $request->validate([
            'category_id'  => ['required', 'exists:categories,id'],
            'name'         => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'specification' => ['nullable', 'string'], // tambahkan ini
            'price'        => ['required', 'numeric', 'min:0'],
            'image'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $data = [
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $this->sanitizeRichText($request->description),
            'specification' => $this->sanitizeRichText($request->specification),
            'price'       => $request->price,
        ];

        $oldImagePath = $design->getRawOriginal('image_url');

        $newImagePath = null;

        try {
            if ($request->hasFile('image')) {
                $newImagePath = $this->uploadService->upload(
                    $request->file('image'),
                    'designs'
                );
                $data['image_url'] = $newImagePath;
            }

            $design->update($data);
        } catch (Throwable $exception) {
            $this->uploadService->delete($newImagePath);

            throw $exception;
        }

        if ($newImagePath !== null && $oldImagePath !== $newImagePath) {
            $this->uploadService->delete($oldImagePath);
        }

        return redirect()->route('admin.catalog.index')
            ->with('success', 'Desain berhasil diperbarui.');
    }

    public function destroy(Design $design)
    {
        $design->delete();
        return back()->with('success', 'Desain berhasil dihapus.');
    }

    // ─── Categories ──────────────────────────────────────────────────────────

    public function categories()
    {
        $categories = Category::withCount([
            'designs',
            'portfolios'
        ])->orderBy('name')->get();

        return view('admin.catalog.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroyCategory(Category $category)
    {
        if ($category->designs()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki desain.');
        }

        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    protected function sanitizeRichText(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        $allowedTags = '<p><br><strong><b><em><i><u><ul><ol><li><blockquote><h1><h2><h3><h4><h5><h6><a>';
        $sanitized = strip_tags($value, $allowedTags);
        $plainText = trim(str_replace('&nbsp;', ' ', strip_tags($sanitized)));

        return $plainText === '' ? null : $sanitized;
    }
}