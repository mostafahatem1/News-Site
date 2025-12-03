<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function allCategories(){
        $categories = Category::active()->get();

        if ($categories->isEmpty()) {
            return api_response('No categories found', 404, null);
        }
        return api_response('Categories retrieved successfully', 200, new CategoryCollection($categories));
    }
    
    public function categoryPosts($slug){
        $category = Category::where('slug', $slug)->first();

        if (!$category) {
            return api_response('Category not found', 404, null);
        }

        $posts = $category->posts()->activeUser()->active()->paginate(4);

        if ($posts->isEmpty()) {
            return api_response('No posts found for this category', 404, null);
        }

        return api_response('Posts retrieved successfully', 200, new PostCollection($posts));
    }
}
