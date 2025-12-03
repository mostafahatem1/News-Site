<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;

use App\Models\Post;
use App\Notifications\NewCommentNotification;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web')->only(['storeComment', 'lessComment', 'allComment']);
    }
    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $single_post = Post::with(['comments' => function($query) {
        $query->with('user')->active()->latest()->limit(3); }])
        ->where('slug', $slug)
        ->active()
        ->firstOrFail();
        // Increment the view count

         $single_post->increment('num_of_views');



        $category = $single_post->category;

        $posts_belonging_to_category = Post::where('category_id', $category->id)
            ->active()
            ->where('id', '!=', $single_post->id)
            ->latest()
            ->take(5)
            ->select('id', 'title', 'slug', 'status')
            ->get();

        return view('frontend.show_post', compact('single_post',  'posts_belonging_to_category'));
    }

    public function allComment($slug)
    {
        $post = Post::where('slug', $slug)->active()->firstOrFail();
        $comments = $post->comments()->with('user')->active()->latest()->get();

        return response()->json([
            'comments' => $comments,
        ]);
    }
    public function lessComment($slug)
    {
        $post = Post::where('slug', $slug)->active()->firstOrFail();
        $comments = $post->comments()->with('user')->active()->latest()->limit(3)->get();

        return response()->json([
            'comments' => $comments,
        ]);
    }

    public function storeComment(CommentRequest $request)
    {

        $comment = Comment::create([
            'comment' => $request->comment,
            'user_id' => $request->user_id,
            'post_id' => $request->post_id,
            'ip_address' => $request->ip(),
        ]);
        $comment->load('user'); // Eager load the user relationship
        if(!$comment) {
            return response()->json(['message' => 'Comment could not be saved.'], 500);
        }

        $post = Post::find($request->post_id);
        $user = $post->user;

        if(auth()->user()->id != $user->id){
         // Notify the post owner about the new comment
        $user->notify(new NewCommentNotification($comment,$post));
        }


        return response()->json([
            'message' => 'Comment saved successfully.',
            'comment'=>$comment,
            'status'=>201,
        ], 201); // Changed to return 201 status code
    }

    public function search(Request $request)
    {
        $request->validate([
            'search' => 'required|string|max:255',
        ]);
        $search = strip_tags($request->input('search'));
        $posts = Post::where('title', 'LIKE', "%{$search}%")
            ->orWhere('desc', 'LIKE', "%{$search}%")
            ->active()
            ->latest()
            ->paginate(6);

        $count = $posts->total();

        return view('frontend.list_posts', compact('posts', 'search', 'count'));
    }



}
