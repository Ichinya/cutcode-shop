<?php

namespace Tests\Feature\App\Http\Controllers\Auth;


use App\Http\Controllers\Auth\SignInController;
use Database\Factories\UserFactory;
use Domain\Auth\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SignInControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return void
     */
    public function it_page_success(): void
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
    public function it_handle_success(): void
    {
        $password = '123456789';

        $user = UserFactory::new()->create([
            'email' => 'testing@cutcode.ru',
            'password' => bcrypt($password)
        ]);

        $request = [
            'email' => $user->email,
            'password' => $password
        ];

        $response = $this->post(action([SignInController::class, 'handle']), $request);

        $response->assertValid()
            ->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }

    /**
     * @test
     * @return void
     */
    public function it_handle_fail(): void
    {
        $request = [
            'email' => 'notfound@cutcode.ru',
            'password' => str()->random(10)
        ];

        $this->post(action([SignInController::class, 'handle']), $request)
            ->assertInvalid(['email']);

        $this->assertGuest();
    }


    /**
     * @test
     * @return void
     */
    public function it_logout_success(): void
    {
        UserFactory::new()->create([
            'email' => 'testing@cutcode.ru',
        ]);
        $user = User::query()->where('email', 'testing@cutcode.ru')->first();

        $this->actingAs($user)
            ->delete(action([SignInController::class, 'logout']));


        $this->assertGuest();
    }

    /**
     * @test
     * @return void
     */
    public function it_logout_guest_middleware_fail(): void
    {
        $this->delete(action([SignInController::class, 'logout']))
            ->assertRedirect(route('home'));
    }

}
