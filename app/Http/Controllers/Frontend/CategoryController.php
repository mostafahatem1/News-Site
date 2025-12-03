<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($slug)
    {
        $category = Category::where('slug', $slug)
            ->active()
            ->firstOrFail();

        $posts = Post::where('category_id', $category->id)
            ->active()
            ->latest()
            ->paginate(6);
        return view('frontend.category_posts', compact('category', 'posts'));
    }
}
