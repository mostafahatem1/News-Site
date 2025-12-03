<?php

namespace Tests\Feature\Frontend\Dashboard\Notification;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class NotificationRetrievalTest extends TestCase
{
     use RefreshDatabase;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** test */
    public function user_can_view_notification_page()
    {
        $this->actingAs($this->user)
            ->get(route('frontend.dashboard.notification'))
            ->assertStatus(200)
            ->assertViewIs('frontend.dashboard.notification');
    }

    private function createNotification(array $data = []): array
    {
        return array_merge([
            'id' => Str::uuid(),
            'type' => 'App\Notifications\TestNotification',
            'data' => ['message' => 'Test notification']
        ], $data);
    }

    /** test */
    public function unread_notifications_are_marked_as_read_when_viewing_page()
    {
        // Create unread notifications
        $this->user->notifications()->createMany([
            $this->createNotification(['data' => ['message' => 'Test 1']]),
            $this->createNotification(['data' => ['message' => 'Test 2']])
        ]);

        $this->assertEquals(2, $this->user->unreadNotifications->count());

        $this->actingAs($this->user)
            ->get(route('frontend.dashboard.notification'));

        $this->assertEquals(0, $this->user->fresh()->unreadNotifications->count());
    }

    /** test */
    public function guest_cannot_access_notification_page()
    {
        $this->get(route('frontend.dashboard.notification'))
            ->assertRedirect(route('frontend.login'));
    }

    /** test */
    public function notifications_are_displayed_on_page()
    {
        $this->user->notifications()->create(
            $this->createNotification(['data' => ['message' => 'Test notification message']])
        );

        $response = $this->actingAs($this->user)
            ->get(route('frontend.dashboard.notification'));

        $response->assertSee('Test notification message');
    }
}
