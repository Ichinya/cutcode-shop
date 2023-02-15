<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\SignInController;
use App\Http\Controllers\Auth\SignUpController;
use App\Listeners\SendEmailNewUserListener;
use App\Notifications\NewUserNotification;
use Database\Factories\UserFactory;
use Domain\Auth\Models\User;
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
        $this->get(action([SignInController::class, 'page']))
            ->assertOk()
            ->assertSee('Вход в аккаунт')
            ->assertViewIs('auth.login');
    }

    /**
     * @test
     * @return void
     */
    public function it_signup_page_success(): void
    {
        $this->get(action([SignUpController::class, 'page']))
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
        $this->get(action([ForgotPasswordController::class, 'page']))
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
        $user = UserFactory::new()->create([
            'email' => 'test@ya.ru',
            'password' => Hash::make($password),
        ]);
        $request = [
            'email' => $user->email,
            'password' => $password,
        ];

        $response = $this->post(action([SignInController::class, 'handle']), $request);
        $response->assertValid()->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * @test
     * @return void
     */
    public function is_sign_up_success(): void
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

        $response = $this->post(route('register.handle'), $request);
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
        $user = UserFactory::new()->create([
            'email' => 'test@ya.ru',
        ]);

        $this->actingAs($user)->delete(action([SignInController::class, 'logout']));
        $this->assertGuest();

    }
}
