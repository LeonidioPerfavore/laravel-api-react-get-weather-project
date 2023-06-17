<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\TokenService;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Socialite to run the Google authorization driver and create a stateless (stateless) instance.
     * The redirect() method is then called, which will cause the user to be redirected to the Google login page.
     * The getTargetUrl() function returns the redirect URL. This URL is returned in JSON format.
     **/
    public function getUrl(): JsonResponse
    {
        return response()->json(['url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl()]);
    }

    /**
    * Login or register using data from google
    **/
    public function loginWithGoogleData(): JsonResponse
    {
        try {
            $socialiteUser = Socialite::driver('google')->stateless()->user();
        } catch (ClientException $e) {
            return response()->json(['error' => 'Invalid credentials provided.'], 422);
        }

        /** @var User $user */
        $user = User::query()->firstOrCreate(
                [
                    'email' => $socialiteUser->getEmail(),
                ],
                [
                    'name' => $socialiteUser->getName(),
                    'password' => Hash::make($socialiteUser->getName().'@'.$socialiteUser->getId())
                ]
            );

        $jwt = TokenService::generateToken(['user_id' => $user->id, 'email' => $user->email]);

        return response()->json(['user' => $user, 'token' => $jwt]);
    }
}