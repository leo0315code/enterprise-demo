<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categorySlug = $request->query('category');
        $keyword = trim((string) $request->query('q', ''));

        $categories = Category::active()->where('type', 'product')->ordered()->get();

        $products = Product::active()->with('category')->ordered();
        if ($categorySlug) {
            $cat = Category::where('slug', $categorySlug)->first();
            if ($cat) {
                $products->where('category_id', $cat->id);
            }
        }
        if ($keyword !== '') {
            $products->where(fn ($q) => $q
                ->where('title', 'like', "%{$keyword}%")
                ->orWhere('summary', 'like', "%{$keyword}%"));
        }
        $products = $products->paginate(9)->withQueryString();

        return view('products.index', compact('products', 'categories', 'categorySlug', 'keyword'));
    }

    public function show($slug)
    {
        // 仅展示上架产品（与列表页 active scope 一致）
        $product = Product::active()->where('slug', $slug)->firstOrFail();
        return view('products.show', compact('product'));
    }
}
