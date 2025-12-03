<?php

namespace Tests\Feature\Frontend\Posts;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Image;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostsRetrievalTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;
    protected Post $post;

    public function setUp(): void
    {
        parent::setUp();
        $this->category = Category::factory()->create();
        $this->post = Post::factory()->create([
            'category_id' => $this->category->id,
            'slug' => 'my-test-post',
            'num_of_views' => 0,
            'status' => '1',
        ]);
        Image::factory()->create(['post_id' => $this->post->id]);
    }

    public function test_show_post_related_posts_are_limited_to_5_and_exclude_current_post()
    {
        // Create 7 active posts in the same category (excluding $this->post)
        $posts = Post::factory()->count(7)->create([
            'category_id' => $this->category->id,
            'status' => '1',  // Make sure we use string '1' for active
        ]);

        foreach ($posts as $post) {
            Image::factory()->create(['post_id' => $post->id]);
        }

        Post::factory()->count(2)->create([
            'category_id' => $this->category->id,
            'status' => '0',
        ]);

        ////////////////////////////////////////////////////////////////////////////////////

        $response = $this->get(route('frontend.post.show', ['slug' => $this->post->slug]));

        ///////////////////////////////////////////////////////////////////////////////////

        $response->assertStatus(200)
            ->assertViewIs('frontend.show_post')
            ->assertViewHas('single_post')
            ->assertViewHas('posts_belonging_to_category')
            ->assertSeeText('In This Category')
            ->assertSeeText('News Category')
           ;

        $posts_belonging_to_category = $response->viewData('posts_belonging_to_category');

        // Assert only 5 posts are returned
        $this->assertCount(5, $posts_belonging_to_category);

        // Assert none of the returned posts is the current post
        foreach ($posts_belonging_to_category as $related_post) {
            $this->assertNotEquals($this->post->id, $related_post->id);
            $this->assertEquals('1', $related_post->status);
        }
        /////////////////////////////////////////////////////////////////////////////////////
    }

    public function test_show_post_comments_are_limited_and_have_user()
    {
        // Create 5 active comments with users for $this->post
        $user = User::factory()->create();
        Comment::factory()->count(5)->create([
            'post_id' => $this->post->id,
            'user_id' => $user->id,
            'status' => 1,
        ]);
        /////////////////////////////////////////////////////////////////////////////////
        $response = $this->get(route('frontend.post.show', ['slug' => $this->post->slug]));
        /////////////////////////////////////////////////////////////////////////////////

        $response->assertStatus(200);
        $single_post = $response->viewData('single_post');
        $comments = $single_post->comments;

        // Assert only 3 comments are loaded
        $this->assertLessThanOrEqual(3, $comments->count());

        // Assert each comment has a user relation
        foreach ($comments as $comment) {
            $this->assertNotNull($comment->user);
        }
    }

    public function test_show_post_increments_view_count()
    {
        $this->get(route('frontend.post.show', ['slug' => $this->post->slug]));

        $this->post->refresh();
        $this->assertEquals(1, $this->post->num_of_views);
    }

    public function test_show_post_has_latest_posts()
    {
        Post::factory()->count(3)->create(['status' => '1'])->each(function($post) {
            Image::factory()->create(['post_id' => $post->id]);
        });
        //////////////////////////////////////////////////////////////////////////////////
        $response = $this->get(route('frontend.post.show', ['slug' => $this->post->slug]));
        /////////////////////////////////////////////////////////////////////////////////

        $response->assertViewHas('latest_posts');
        $latest_posts = $response->viewData('latest_posts');
        $this->assertNotNull($latest_posts);
    }

    public function test_show_post_has_popular_posts()
    {
        $popular_post = Post::factory()->create([
            'status' => '1',
            'num_of_views' => 100
        ]);
        Image::factory()->create(['post_id' => $popular_post->id]);
        //////////////////////////////////////////////////////////////////////////////////
        $response = $this->get(route('frontend.post.show', ['slug' => $this->post->slug]));
        /////////////////////////////////////////////////////////////////////////////////
        $response->assertViewHas('popular_posts');
        $popular_posts = $response->viewData('popular_posts');
        $this->assertNotNull($popular_posts);
    }
}
