<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRoleEnum;
use App\Enums\UserStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\RequestLoginRequest;
use App\Http\Requests\Auth\RequestRegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function requestRegister(RequestRegisterRequest $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|int|unique:users',
        ]);

        //check if user already tried 5 times
        // if so then throw en exception

        //Send an OTP code through event()
            //Generate unique OTP code that wasn't sent earlier
            //Send it through Notify service to subject
            //Save to otp_codes hash(phone+code) so we ensure security compliance and won't send same otp in the future

        return response()->json([
            'message' => __('auth.otp_sent')
        ]);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|int|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'otp_code' => 'required|digits:6',
        ]);

        // check if there is hashed (phone+otp)
        // if ok then
            // create User,
            // assign role Viewer,
            // generate token and return it

        /** @var User $user */
        $user = DB::transaction(function () use ($request) {
            $user = new User();
            $user->name = $request->input('name');
            $user->phone = $request->input('phone');
            $user->email = $request->input('email');
            $user->password = $request->input('password');
            $user->status = UserStatusEnum::ACTIVE;
            $user->save();

            $user->assignRole(UserRoleEnum::VIEWER);

            return $user;
        });

        return response()->json([
            'accessToken' => $user->createToken('Personal Access Token')->plainTextToken,
            'token_type' => 'Bearer',
        ]);
    }

    public function requestLogin(RequestLoginRequest $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|int|exists:users',
        ]);

        //check if user already tried 5 times
        // if so then throw en exception

        //Send an OTP code through event()
        //Generate unique OTP code that wasn't sent earlier
        //Send it through Notify service to subject
        //Save to otp_codes hash so we ensure security compliance and won't send same otp in the future

        return response()->json([
            'message' => __('auth.otp_sent')
        ]);
    }

    public function login(LoginRequest $request): JsonResponse
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

        //return token
        return response()->json([
            'accessToken' => $user->createToken('Personal Access Token')->plainTextToken,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => __('auth.logout')
        ]);
    }
}
