<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Post;
use App\Models\RelatedSite;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        if (Schema::hasTable('related_sites')) {
            $relatedSite = RelatedSite::select('name', 'url')->get();
        }

        if (Schema::hasTable('categories')) {
            $categories = Category::select('id', 'slug', 'name')->where('status', 1)->get();
        }

        if (!Cache::has('read_more_posts')) {
            Cache::remember('read_more_posts', 3600, function () {
                return Post::select('slug', 'title')->where('status', 1)->latest()->limit(10)->get();
            });
        }
        $read_more_posts = Cache::get('read_more_posts');

        if (!Cache::has('latest_posts')) {
            Cache::remember('latest_posts', 3600, function () {
                return Post::with('images')->where('status', 1)->latest()->take(5)->get();
            });
        }
        $latest_posts = Cache::get('latest_posts');

        if (!Cache::has('popular_posts')) {
            Cache::remember('popular_posts', 3600, function () {
                return Post::with('images')->where('status', 1)->withCount('comments')->orderBy('comments_count', 'desc')->take(5)->get();
            });
        }
        $popular_posts = Cache::get('popular_posts');


        view()->share([
            'read_more_posts' => $read_more_posts,
            'latest_posts' => $latest_posts,
            'popular_posts' => $popular_posts,
            'relatedSite' => $relatedSite,
            'categories' => $categories,
        ]);
    }
}
