<?php

namespace App\UseCases\Auth;

use App\Models\User;
use App\Services\Otp\OtpService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoginUseCase
{
    public function __construct(private OtpService $otpService)
    {
    }

    public function execute(Request $request): string
    {
        $otpCheck = $this->otpService->check($request->input('phone') . '_login', $request->input('otp_code'));
        if ($otpCheck === false) {
            //TODO: make an custom exception for otp
            throw new Exception(__('auth.otp_wrong'));
        }

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