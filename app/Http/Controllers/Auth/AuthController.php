<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\RequestLoginRequest;
use App\Http\Requests\Auth\RequestRegisterRequest;
use App\UseCases\Auth\LoginUseCase;
use App\UseCases\Auth\LogoutUseCase;
use App\UseCases\Auth\RegisterUseCase;
use App\UseCases\Auth\RequestLoginUseCase;
use App\UseCases\Auth\RequestRegisterUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function requestRegister(RequestRegisterRequest $request, RequestRegisterUseCase $requestRegisterUseCase): JsonResponse
    {
        //TODO: make DTO from request and pass it
        $requestRegisterUseCase->execute($request);

        return response()->json([
            'message' => __('auth.otp_sent')
        ]);
    }

    public function register(RegisterRequest $request, RegisterUseCase $registerUseCase): JsonResponse
    {
        //TODO: make DTO from request and pass it
        $accessToken = $registerUseCase->execute($request);

        return response()->json([
            'accessToken' => $accessToken,
            'token_type' => 'Bearer',
        ]);
    }

    public function requestLogin(RequestLoginRequest $request, RequestLoginUseCase $requestLoginUseCase): JsonResponse
    {
        $requestLoginUseCase->execute($request);

        return response()->json([
            'message' => __('auth.otp_sent')
        ]);
    }

    public function login(LoginRequest $request, LoginUseCase $loginUseCase): JsonResponse
    {
        $accessToken = $loginUseCase->execute($request);

        return response()->json([
            'accessToken' => $accessToken,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request, LogoutUseCase $logoutUseCase): JsonResponse
    {
        $logoutUseCase->execute($request);

        return response()->json([
            'message' => __('auth.logout')
        ]);
    }
}
