<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function requestRegister(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|int|unique:users',
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

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|int|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'otp_code' => 'required|digits:6',
        ]);

        // check if there is hashed (phone+otp) through the OTP service
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

            $user->assignRole('viewer');

            return $user;
        });

        return response()->json([
            'accessToken' => $user->createToken('Personal Access Token')->plainTextToken,
            'token_type' => 'Bearer',
        ]);
    }

    public function requestLogin(Request $request)
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

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|int|exists:users',
            'password' => 'required|string',
            'otp_code' => 'required|digits:6',
        ]);

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
