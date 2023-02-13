<?php

namespace Tests\Feature\Http\Controllers;

use App\Listeners\SendEmailNewUserListener;
use App\Models\User;
use App\Notifications\NewUserNotification;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return void
     */
    public function is_store_success(): void
    {
        Event::fake();
        Notification::fake();

        $request = [
            'name' => 'Test',
            'email' => 'test@test.com',
            'password' => '123456789',
            'password_confirmation' => '123456789',
        ];

        $response = $this->post(route('store'), $request);
        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
            'email' => $request['email'],
        ]);

        /** @var Authenticatable $user */
        $user = User::query()->where('email', $request['email'])->first();

        Event::assertDispatched(Registered::class);
        Event::assertListening(Registered::class, SendEmailNewUserListener::class);

        $event = new Registered($user);
        $listener = new SendEmailNewUserListener();
        $listener->handle($event);

        Notification::assertSentTo($user, NewUserNotification::class);

        $response->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);

    }
}
