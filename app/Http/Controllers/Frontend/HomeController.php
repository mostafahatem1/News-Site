<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $data['posts'] =  Post::with('images')->where('status', 1)->paginate(6);

        $data['latest_posts'] = Post::with('images')->where('status', 1)->latest()->take(3)->get();
        $data['num_of_views'] = Post::with('images')->where('status', 1)->orderBy('num_of_views', 'desc')->limit(3)->get();

        $data['oldest_post'] = Post::with('images')->where('status', 1)->oldest()->take(3)->get();
        $data['popular_posts'] = Post::with('images')->where('status', 1)->withCount('comments')->orderBy('comments_count', 'desc')->take(3)->get();

        $categories = Category::has('posts', '>=', 2)->with(['posts' => function ($query) {
            $query->active();
        }])->active()->get();

        $categories->each(function ($category) {
            $category->setRelation('posts', $category->posts->take(4));
        });

        $data['categories'] = $categories;

        return view('frontend.index', $data);
    }
}
