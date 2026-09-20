<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $user = User::query()->create($request->safe()->only([
            'name',
            'email',
            'password',
        ]));

        return response()->json([
            'user' => UserResource::make($user)->resolve(),
            'token' => $user->createToken('auth')->plainTextToken,
        ], 201);
    }
}
