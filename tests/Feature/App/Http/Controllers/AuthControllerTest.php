<?php

namespace App\Http\Controllers;

use App\Listeners\SendEmailNewUserListener;
use App\Models\User;
use App\Notifications\NewUserNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return void
     */
    public function it_login_page_success(): void
    {
        $this->get(action([AuthController::class, 'index']))
            ->assertOk()
            ->assertSee('Вход в аккаунт')
            ->assertViewIs('auth.index');
    }

    /**
     * @test
     * @return void
     */
    public function it_signup_page_success(): void
    {
        $this->get(action([AuthController::class, 'signUp']))
            ->assertOk()
            ->assertSee('Регистрация')
            ->assertViewIs('auth.sign-up');
    }

    /**
     * @test
     * @return void
     */
    public function it_forgot_page_success(): void
    {
        $this->get(action([AuthController::class, 'forgot']))
            ->assertOk()
            ->assertSee('Забыли пароль')
            ->assertViewIs('auth.forgot-password');
    }

    /**
     * @test
     * @return void
     */
    public function it_sign_in_success(): void
    {
        $password = '1234567890';
        $user = User::factory()->create([
            'email' => 'test@ya.ru',
            'password' => Hash::make($password),
        ]);
        $request = [
            'email' => $user->email,
            'password' => $password,
        ];

        $response = $this->post(action([AuthController::class, 'signIn']), $request);
        $response->assertValid()->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

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

        $this->assertDatabaseMissing('users', ['email' => $request['email']]);

        $response = $this->post(route('store'), $request);
        $response->assertValid()->assertRedirect();

        $this->assertDatabaseHas('users', [
            'email' => $request['email'],
        ]);

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

    /**
     * @test
     * @return void
     */
    public function is_logout_success(): void
    {
        $password = '1234567890';
        $user = User::factory()->create([
            'email' => 'test@ya.ru',
        ]);

        $this->actingAs($user)->delete(action([AuthController::class, 'logout']));
        $this->assertGuest();

    }
}
