<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Product::with('category')->ordered();
        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $products = $query->paginate(15)->withQueryString();
        $categories = Category::active()->where('type', 'product')->ordered()->get();

        return inertia('Products', [
            'products' => $products,
            'categories' => $categories,
            'filters' => $request->only(['q', 'category_id', 'status']),
        ]);
    }

    public function create()
    {
        $categories = Category::active()->where('type', 'product')->ordered()->get();
        return view('admin.products.form', ['product' => null, 'categories' => $categories]);
    }

    /**
     * 仅返回表格局部，供弹窗保存后无刷新刷新列表。
     */
    public function rows()
    {
        $products = Product::with('category')->ordered()->paginate(15)->withQueryString();
        $categories = Category::active()->where('type', 'product')->ordered()->get();
        return view('admin.products._table', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:200', 'unique:products'],
            'thumbnail' => ['nullable', 'string', 'max:500'],
            'summary' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
            'sort' => ['integer', 'min:0'],
            'is_featured' => ['boolean'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']) ?: 'product-' . time();
        $data['is_featured'] = $request->boolean('is_featured');

        Product::create($data);

        if (request()->ajax()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('admin.products.index')->with('success', '产品已创建');
    }

    public function edit(Product $product)
    {
        $categories = Category::active()->where('type', 'product')->ordered()->get();
        if (request()->ajax()) {
            return view('admin.products._fields', compact('product', 'categories'));
        }
        return view('admin.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:200', 'unique:products,slug,' . $product->id],
            'thumbnail' => ['nullable', 'string', 'max:500'],
            'summary' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
            'sort' => ['integer', 'min:0'],
            'is_featured' => ['boolean'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']) ?: 'product-' . $product->id;
        $data['is_featured'] = $request->boolean('is_featured');

        $product->update($data);

        if (request()->ajax()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('admin.products.index')->with('success', '产品已更新');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        if (request()->ajax() || request()->inertia()) {
            return response()->json(['ok' => true]);
        }
        return redirect()->route('admin.products.index')->with('success', '产品已删除');
    }
}
