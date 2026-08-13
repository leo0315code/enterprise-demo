<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'posts' => Post::count(),
            'messages' => ContactMessage::count(),
            'unread' => ContactMessage::where('is_read', false)->count(),
            'product_categories' => Category::where('type', 'product')->count(),
            'post_categories' => Category::where('type', 'post')->count(),
        ];

        // 弱口令提醒：默认管理员密码为 admin123，建议尽快修改
        $weakPassword = Hash::check('admin123', auth()->user()->password ?? '');

        $recentMessages = ContactMessage::latest()->take(5)->get();
        $recentPosts = Post::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'weakPassword', 'recentMessages', 'recentPosts'));
    }
}
