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

        $categories = Category::active()->where('type', 'product')->ordered()->get();

        $products = Product::active()->with('category')->ordered();
        if ($categorySlug) {
            $cat = Category::where('slug', $categorySlug)->first();
            if ($cat) {
                $products->where('category_id', $cat->id);
            }
        }
        $products = $products->paginate(9)->withQueryString();

        return view('products.index', compact('products', 'categories', 'categorySlug'));
    }

    public function show($slug)
    {
        // 仅展示上架产品（与列表页 active scope 一致）
        $product = Product::active()->where('slug', $slug)->firstOrFail();
        return view('products.show', compact('product'));
    }
}
