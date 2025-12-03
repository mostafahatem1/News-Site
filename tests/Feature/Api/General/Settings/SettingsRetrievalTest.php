<?php

namespace Tests\Feature\Api\General\Settings;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsRetrievalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:wipe');
        $this->artisan('migrate');
    }

    /** @test */
    public function it_can_retrieve_settings_successfully()
    {
        Setting::create([
            'site_name' => 'News-EveryDay',
            'logo' => 'http://news-site.test/logo.png',
            'favicon' => 'http://news-site.test/favicon.ico',
            'email' => 'news@gmail.com',
            'facebook' => 'https://www.facebook.com/',
            'twitter' => 'https://www.twitter.com/',
            'insagram' => 'https://www.instagram.com/',
            'youtupe' => 'https://www.youtupe.com/',
            'phone' => '01222333434',
            'small_desc' => '23 of PARAGE is equality of condition',
            'country' => 'Egypt',
            'city' => 'Alex',
            'street' => 'Elsharawy'
        ]);

        $response = $this->getJson('/api/settings');

        $response->assertOk()
            ->assertJson([
                'message' => 'Settings retrieved successfully',
                'status' => 200,
                'data' => [
                    'setting' => [
                        'site_name' => 'News-EveryDay',
                        'logo' => 'http://news-site.test/logo.png',
                        'favicon' => 'http://news-site.test/favicon.ico',
                        'email' => 'news@gmail.com',
                        'facebook' => 'https://www.facebook.com/',
                        'twitter' => 'https://www.twitter.com/',
                        'insagram' => 'https://www.instagram.com/',
                        'youtupe' => 'https://www.youtupe.com/',
                        'phone' => '01222333434',
                        'small_desc' => '23 of PARAGE is equality of condition',
                        'address' => [
                            'country' => 'Egypt',
                            'city' => 'Alex',
                            'street' => 'Elsharawy'
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_returns_404_when_settings_not_found()
    {
        // Ensure settings table is empty
        Setting::truncate();
        $this->assertDatabaseEmpty('settings');

        $response = $this->getJson('/api/settings');

        $response->assertNotFound()
            ->assertJson([
                'message' => 'Settings not found',
                'status' => 404
            ]);
    }
}
