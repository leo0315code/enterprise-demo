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
        $products = $products->paginate(9);

        return view('products.index', compact('products', 'categories', 'categorySlug'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        return view('products.show', compact('product'));
    }
}
