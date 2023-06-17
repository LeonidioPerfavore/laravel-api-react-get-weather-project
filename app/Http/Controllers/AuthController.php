<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repositories\UserRepositoryInterface;
use App\Services\TokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->userRepository->create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password'))
        ]);

        $jwt = TokenService::generateToken(['user_id' => $user->id, 'email' => $user->email]);

        return response()->json(['token' => $jwt], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
            $user = $this->userRepository->findByEmail($request->input('email'));
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            if (!Hash::check($request->input('password'), $user->password)) {
                return response()->json(['message' => 'Invalid password'], 401);
            }

            $jwt = TokenService::generateToken(['user_id' => $user->id, 'email' => $user->email]);

            return response()->json(['token' => $jwt, 'user' => $user]);
    }

    public function getUser(Request $request): JsonResponse
    {
        return response()->json(['user' => $request->get('user')]);
    }
}