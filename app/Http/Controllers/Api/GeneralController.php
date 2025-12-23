<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function allPosts()
    {
        $query = Post::query()->with(['user', 'category', 'images'])->withCount('comments')->activeUser()->activeCategory()->active();

        if ($query->count() === 0) {
            return api_response('No posts found', 404, null);
        }

        $posts = $this->posts(clone $query);

        $latest_posts = $this->latest_posts(clone $query);
        $oldest_posts = $this->oldest_posts(clone $query);

        $popular_posts = $this->popular_posts(clone $query);
        $most_viewed_posts = $this->most_viewed_posts(clone $query);

        $category_with_posts = $this->category_with_posts();



        $data = [
            'posts' => (new PostCollection($posts))->response()->getData(true),
            'latest_posts' => new PostCollection($latest_posts),
            'oldest_posts' => new PostCollection($oldest_posts),
            'popular_posts' => new PostCollection($popular_posts),
            'most_viewed_posts' => new PostCollection($most_viewed_posts),
            'category_with_posts' => new CategoryCollection($category_with_posts),
        ];
        return api_response('Posts retrieved successfully', 200, $data);
    }

    public function posts($query)
    {
        return $query->paginate(4);
    }

    public function latest_posts($query)
    {
        return $query->latest()->take(4)->get();
    }

    public function oldest_posts($query)
    {
        return $query->oldest()->take(3)->get();
    }

    public function popular_posts($query)
    {
        return $query->withCount('comments')->orderBy('comments_count', 'desc')->take(3)->get();
    }
    public function most_viewed_posts($query)
    {
        return $query->orderBy('num_of_views', 'desc')->take(3)->get();
    }
    public function category_with_posts()
    {

        $categories = Category::active()->get();

        return $categories->each(function ($category) {
            // Use the relationship query so we can chain withCount() and take()
            $posts = $category->posts()->withCount('comments')->take(4)->get();
            $category->setRelation('posts', $posts);
        });
    }

    public function showPosts($slug)
    {
        $post = Post::with(['user', 'category', 'images'])
            ->withCount('comments')
            ->activeUser()
            ->activeCategory()
            ->active()
            ->where('slug', $slug)
            ->first();
        if (!$post) {
            return api_response('Post not found', 404, null);
        }
        return api_response('Post retrieved successfully', 200, new PostResource($post));
    }

    public function postComments($slug)
    {
        $post = Post::with(['comments.user'])->activeUser()->activeCategory()->active()->where('slug', $slug)->first();
        if (!$post) {
            return api_response('Post not found', 404, null);
        }

        if ($post->comments->isEmpty()) {
            return api_response('No comments found', 404, null);
        }
        return api_response('Post comments retrieved successfully', 200, new CommentCollection($post->comments));
    }

    public function searchPosts(Request $request)
    {
        $search = strip_tags($request->input('search'));
        $posts = Post::where('title', 'LIKE', "%{$search}%")
            ->orWhere('desc', 'LIKE', "%{$search}%")
            ->active()
            ->latest()
            ->paginate(6);

        if (!$posts || $posts->isEmpty()) {
            return api_response('No posts found', 404, null);
        }
        return api_response('Posts retrieved successfully', 200, new PostCollection($posts));
    }
}
