<?php

namespace Tests\Feature\Frontend;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeRetrievalTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_home_page(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200)
            ->assertViewIs('frontend.index')
            ->assertSeeText('Subscribe');
    }

    public function test_home_page_has_posts_data()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response->assertViewHas('posts', function ($posts) {
            $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $posts);
            $this->assertEquals(6, $posts->perPage());
            // Optionally, you can also assert the count of items on the current page:
            // $this->assertLessThanOrEqual(6, $posts->count());
            return true;
        });
    }

    public function test_home_page_has_latest_posts_data()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response->assertViewHas('latest_posts');

        $latestPosts = $response->viewData('latest_posts');
        $this->assertLessThanOrEqual(3, $latestPosts->count());
    }

    public function test_home_page_has_num_of_views_data()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response->assertViewHas('num_of_views');

        $numOfViews = $response->viewData('num_of_views');
        $this->assertLessThanOrEqual(3, $numOfViews->count());
    }

    public function test_home_page_has_oldest_post_data()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response->assertViewHas('oldest_post');

        $oldest_post = $response->viewData('oldest_post');
        $this->assertLessThanOrEqual(3, $oldest_post->count());
    }

    public function test_home_page_has_popular_posts_data()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response->assertViewHas('popular_posts');

        $popularPosts = $response->viewData('popular_posts');
        $this->assertLessThanOrEqual(3, $popularPosts->count());
    }

    public function test_home_page_has_categories_data()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response->assertViewHas('categories');
        $categories = $response->viewData('categories');

        foreach ($categories as $category) {
            $this->assertLessThanOrEqual(4, $category->posts->count());
        }
    }
}
