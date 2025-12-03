<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Str;
use App\Traits\UploadImages;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\DB;
use Flasher\Laravel\Facade\Flasher;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ProfileController extends Controller
{
    use UploadImages;

    function index()
    {
        $posts = Post::with('images')->where('user_id', auth(auth('admin')->check() ? 'admin' : 'web')->id())->active()->latest()->get();
        return view('frontend.dashboard.profile', compact('posts'));
    }

    function storePost(PostRequest $request)
    {
        try {
            DB::beginTransaction();
            $input['title'] = $request->title;
            $input['desc'] = $request->desc;
            $input['category_id'] = $request->category_id;
            $input['comment_able'] = $request->comment_able == 'on' ? 1 : 0;
            $input[auth('admin')->check() ? 'admin_id' : 'user_id'] = auth(auth('admin')->check() ? 'admin' : 'web')->id();

            $post = Post::create($input);

            // Use the trait method for image uploads
            $this->uploadImages($request->images, $post);
            Flasher::addSuccess('Post created successful!');
            Cache::forget('read_more_posts');
            Cache::forget('latest_posts');
            Cache::forget('popular_posts');
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['errors' => $e->getMessage()]);
        }
    }

    public function postComments($post_id){
        $comments = Comment::where('post_id', $post_id)->with('user')->active()->latest()->get();

         return response()->json([
            'message' => 'Comment saved successfully.',
            'comments'=>$comments,
            'status'=>200,
        ]);
    }

    public function editPost($id)
    {
        $post = Post::findOrFail($id);
        $categories = \App\Models\Category::active()->get();
        if ($post->user_id != auth(auth('admin')->check() ? 'admin' : 'web')->id()) {
            Flasher::addError('You are not authorized to edit this post!');
            return redirect()->back();
        }
        return view('frontend.dashboard.edit_post', compact('post', 'categories'));
    }


    public function updatePost(Request $request)
    {

        try {
            DB::beginTransaction();
            $post = Post::findOrFail($request->post_id);
            if ($post->user_id != auth(auth('admin')->check() ? 'admin' : 'web')->id()) {
                Flasher::addError('You are not authorized to update this post!');
                return redirect()->back();
            }
            $post->title = $request->title;
            $post->desc = $request->desc;
            $post->category_id = $request->category_id;
            $post->comment_able = $request->comment_able == 'on' ? 1 : 0;

            // Use the trait method for image uploads
            $this->uploadImages($request->images, $post);

            $post->save();
            Flasher::addSuccess('Post updated successful!');
            Cache::forget('read_more_posts');
            Cache::forget('latest_posts');
            Cache::forget('popular_posts');
            DB::commit();
            return redirect()->route('frontend.dashboard.profile');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['errors' => $e->getMessage()]);
        }
    }

    public function deletePostImage(Request $request)
    {
        try {
            DB::beginTransaction();
            $post = Post::findOrFail($request->post_id);
            if ($post->user_id != auth(auth('admin')->check() ? 'admin' : 'web')->id()) {
                Flasher::addError('You are not authorized to delete this image!');
                return redirect()->back();
            }
            $image = $post->images()->findOrFail($request->image_id);
            $this->removeImage($image);
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Image deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['errors' => $e->getMessage()]);
        }
    }

     public function deletePost($id)
    {
        try {
            DB::beginTransaction();
            $post = Post::findOrFail($id);
            if ($post->user_id == auth(auth('admin')->check() ? 'admin' : 'web')->id()) {
                // Delete images associated with the post
                $this->removeImage($post->images);
                $post->delete();
                Flasher::addSuccess('Post deleted successful!');
                Cache::forget('read_more_posts');
                Cache::forget('latest_posts');
                Cache::forget('popular_posts');
            } else {
                Flasher::addError('You are not authorized to delete this post!');
            }
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['errors' => $e->getMessage()]);
        }
    }
}
