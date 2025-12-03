<?php

namespace Tests\Feature\Frontend\Dashboard\Notification;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;
use Tests\TestCase;

class NotificationDeleteTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    private function createNotification(array $data = []): array
    {
        return array_merge([
            'id' => Str::uuid(),
            'type' => 'App\Notifications\TestNotification',
            'data' => ['message' => 'Test notification']
        ], $data);
    }

    /** @test */
    public function user_can_delete_single_notification()
    {
        $notification = $this->user->notifications()->create($this->createNotification());

        $response = $this->actingAs($this->user)
            ->delete(route('frontend.dashboard.notification.delete', $notification->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('notifications', ['id' => $notification->id]);
    }

    /** @test */
    public function user_can_delete_all_notifications()
    {
        $this->user->notifications()->createMany([
            $this->createNotification(),
            $this->createNotification()
        ]);

        $this->assertEquals(2, $this->user->notifications()->count());

        $response = $this->actingAs($this->user)
            ->post(route('frontend.dashboard.notification.delete_all'));

        $response->assertRedirect();
        $this->assertEquals(0, $this->user->notifications()->count());
    }

    /** @test */
    public function user_cannot_delete_others_notification()
    {
        $otherUser = User::factory()->create();
        $notification = $otherUser->notifications()->create($this->createNotification());

        $response = $this->actingAs($this->user)
            ->delete(route('frontend.dashboard.notification.delete', $notification->id));

        $response->assertStatus(404);
        $this->assertDatabaseHas('notifications', ['id' => $notification->id]);
    }

    /** @test */
    public function guest_cannot_delete_notifications()
    {
        $notification = $this->user->notifications()->create($this->createNotification());

        $response = $this->delete(route('frontend.dashboard.notification.delete', $notification->id));
        $response->assertRedirect(route('frontend.login'));

        $response = $this->post(route('frontend.dashboard.notification.delete_all'));
        $response->assertRedirect(route('frontend.login'));
    }
}
