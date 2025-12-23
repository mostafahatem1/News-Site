<?php

namespace App\Http\Controllers\Api\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\PostRequest;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\PostCollection;
use App\Models\Comment;
use App\Models\Post;
use App\Notifications\NewCommentNotification;
use App\Traits\UploadImages;
use Flasher\Laravel\Facade\Flasher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    use UploadImages;
  public function getPost(Request $request)
{
    try {
        $user = $request->user();
        if (!$user) {
            return api_response('User not found', 404);
        }

        // Get user's posts (all posts, not just active)
        $posts = $user->posts()
            ->with('user', 'category',  'comments')
            ->orderBy('created_at', 'desc')
            ->get();

        // Return proper structure that frontend expects
        return api_response(
            'Posts retrieved successfully',
            200,
            [
                'posts' => new PostCollection($posts),  // Can be empty
                'count' => $posts->count()
            ]
        );
    } catch (\Exception $e) {
        return api_response(
            'Failed to retrieve posts',
            500,
            ['error' => $e->getMessage()]
        );
    }
}


    function storePost(PostRequest $request)
    {
        try {
            DB::beginTransaction();
            $input['title'] = $request->title;
            $input['desc'] = $request->desc;
            $input['category_id'] = $request->category_id;
            $input['comment_able'] = $request->comment_able == 'on' ? 1 : 0;
            $user = $request->user();
            $input[$user instanceof \App\Models\Admin ? 'admin_id' : 'user_id'] = $user->id;

            $post = Post::create($input);

            // Use the trait method for image uploads
            $this->uploadImages($request->images, $post);
            Flasher::addSuccess('Post created successful!');
            Cache::forget('read_more_posts');
            Cache::forget('latest_posts');
            Cache::forget('popular_posts');
            DB::commit();

            return api_response('Post created successfully', 201, $post);
        } catch (\Exception $e) {
            DB::rollBack();
            return api_response('Post creation failed', 500, ['errors' => $e->getMessage()]);
        }
    }

    function showPost($slug)
    {
        $post = Post::where('slug', $slug)->active()->activeCategory()->first();
        if (!$post) {
            return api_response('Post not found', 404);
        }
        return api_response('Post retrieved successfully', 200, $post);
    }

    public function updatePost(PostRequest $request, $slug)
{
    try {
        DB::beginTransaction();

        $post = Post::where('slug', $slug)->first();

        // Add this check
        if (!$post) {
            return api_response('Post not found', 404);
        }

        if ($post->user_id != request()->user()->id) {
            return api_response('You are not authorized to update this post!', 403);
        }

        $post->title = $request->title;
        $post->desc = $request->desc;
        $post->category_id = $request->category_id;
        $post->comment_able = $request->comment_able == 'on' ? 1 : 0;

        if ($request->has('images')) {
            $this->removeImage($post->images);
            $this->uploadImages($request->images, $post);
        }

        $post->save();

        Cache::forget('read_more_posts');
        Cache::forget('latest_posts');
        Cache::forget('popular_posts');

        DB::commit();
        return api_response('Post updated successfully', 200, $post);

    } catch (\Exception $e) {
        DB::rollBack();
        return api_response('Post update failed', 500, ['errors' => $e->getMessage()]);
    }
}


    public function deletePost($id)
    {
        try {
            DB::beginTransaction();
            $post = Post::find($id);
            if (!$post) {
                return api_response('Post not found', 404);
            }
            if ($post->user_id == request()->user()->id) {
                // Delete images associated with the post
                $this->removeImage($post->images);
                $post->delete();

                Cache::forget('read_more_posts');
                Cache::forget('latest_posts');
                Cache::forget('popular_posts');
            } else {
               return api_response('You are not authorized to delete this post!', 403);
            }
            DB::commit();
            return api_response('Post deleted successfully', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return api_response('Post deletion failed', 500, ['errors' => $e->getMessage()]);
        }
    }

    public function postComments($slug)
    {
        $post = Post::where('slug', $slug)->first();
        if (!$post) {
            return api_response('Post not found', 404);
        }

        $comments = $post->comments()->get();
        if ($comments->isEmpty()) {
            return api_response('No comments found', 404);
        }
        return api_response('Post comments retrieved successfully', 200, new CommentCollection($comments));
    }


    public function storeComment(CommentRequest $request)
    {

        $post = Post::find($request->post_id);
        if (!$post) {
            return api_response('Post not found', 404);
        }

       $comment = Comment::create([
            'comment' => $request->comment,
            'user_id' => request()->user()->id,
            'post_id' => $request->post_id,
            'ip_address' => $request->ip(),
        ]);


        if(auth()->user()->id != $post->user_id){
         // Notify the post owner about the new comment
        $post->user->notify(new NewCommentNotification($comment,$post));
        }

        return api_response('Comment added successfully', 201, $comment);
    }
}