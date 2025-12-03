<?php

namespace App\Http\Controllers\Backend\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Flasher\Laravel\Facade\Flasher;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:posts')->only(['index']);
        $this->middleware('can:posts_show')->only(['show']);
        $this->middleware('can:posts_create')->only(['create']);
        $this->middleware('can:posts_store')->only(['store']);
        $this->middleware('can:posts_edit')->only(['edit']);
        $this->middleware('can:posts_update')->only(['update']);
        $this->middleware('can:posts_delete')->only(['destroy']);
        $this->middleware('can:post_status')->only(['status']);
    }

    public function index()
    {
         $posts = Post::with(['user', 'category'])
         ->when(\request()->keyword != null, function ($query) {
            $query->search(\request()->keyword);
        })
            ->when(\request()->status != null, function ($query) {
                $query->whereStatus(\request()->status);
            })
            ->orderBy(\request()->sort_by ?? 'id', \request()->order_by ?? 'desc')
            ->paginate(\request()->limit_by ?? 5);
        return view('backend.posts.index', compact('posts'));
    }




    public function show(string $id)
    {
        $post = Post::with(['user', 'category', 'comments', 'images'])->withCount('comments')->findOrFail($id);
        return view('backend.posts.show', compact('post'));
    }




    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        Flasher::addSuccess('Post deleted successfully.');
        return redirect()->back();
        //
    }
    public function status($id)
    {
        $post = Post::findOrFail($id);
        $post->status = !$post->status;
        $post->save();
        return redirect()->back()->with('success', 'Post status updated successfully.');
    }
}
