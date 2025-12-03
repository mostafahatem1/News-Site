<?php

namespace Tests\Feature\Api\General\Settings;

use App\Models\RelatedSite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelateSiteRetrievalTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_retrieve_related_sites()
    {
        RelatedSite::create([
            'name' => 'Test Site',
            'url' => 'https://test-site.com'
        ]);

        $response = $this->getJson('/api/related/sites');

        $response->assertOk()
            ->assertJsonStructure([
                '*' => [
                    'name',
                    'url'
                ]
            ]);
    }

    /** @test */
    public function it_returns_404_when_no_related_sites_exist()
    {
        $response = $this->getJson('/api/related/sites');

        $response->assertNotFound()
            ->assertJson([
                'message' => 'No related sites found',
                'status' => 404
            ]);
    }
}
