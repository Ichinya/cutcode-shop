<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Domain\Auth\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{

    public function redirect(string $driver): \Symfony\Component\HttpFoundation\RedirectResponse|RedirectResponse
    {
        try {
            return Socialite::driver($driver)->redirect();

        } catch (\Throwable $e) {
            throw new \DomainException('Social auth error', $e->getCode(), $e);
        }
    }

    public function callback(string $driver): Redirector|Application|RedirectResponse
    {
        if ($driver !== 'github') {
            throw new \DomainException('Social auth error');
        }

        $driverUser = Socialite::driver($driver)->user();

        $user = User::query()->firstOrCreate([
            $driver . '_id' => $driverUser->getId(),
        ], [
            'name' => $driverUser->getName() ?? $driverUser->getNickname() ?? 'NoName',
            'email' => $driverUser->getEmail(),
            'password' => Hash::make(str()->random(20))
        ]);

        Auth::login($user);

        return redirect()->intended(route('home'));
    }

}
