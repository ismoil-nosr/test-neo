<?php

namespace App\UseCases\Auth;

use App\Enums\UserRoleEnum;
use App\Enums\UserStatusEnum;
use App\Models\User;
use App\Services\Otp\OtpService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterUseCase
{
    public function __construct(private OtpService $otpService)
    {
    }

    public function execute(Request $request): string
    {
        $otpCheck = $this->otpService->check($request->input('phone') . '_register', $request->input('otp_code'));
        if ($otpCheck === false) {
            //TODO: make an custom exception for otp
            throw new Exception(__('auth.otp_wrong'));
        }

        $user = new User();
        $user->name = $request->input('name');
        $user->phone = $request->input('phone');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->status = UserStatusEnum::ACTIVE;

        $user = DB::transaction(function () use ($user) {
            $user->save();
            $user->assignRole(UserRoleEnum::VIEWER);

            return $user;
        });

        return $user->createToken('Personal Access Token')->plainTextToken;
    }
}