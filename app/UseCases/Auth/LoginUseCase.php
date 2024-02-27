<?php

namespace App\UseCases\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoginUseCase
{
    public function execute(Request $request): string
    {
        //validate OTP code

        //validate user password
        if (Auth::attempt($request->only(['phone', 'password'])) === false) {
            return response()->json([
                'status' => 'error',
                'message' => __('auth.failed'),
            ], Response::HTTP_UNAUTHORIZED);
        }

        /** @var User $user */
        $user = User::query()->where('phone', $request->input('phone'))->firstOrFail();
        return $user->createToken('Personal Access Token')->plainTextToken;
    }
}