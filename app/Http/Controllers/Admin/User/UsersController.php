<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserCreateRequest;
use App\Http\Requests\Admin\User\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index(): JsonResponse
    {
        $query = User::query()->with(['roles']);

        //Add filters/sorting etc.

        return response()->json($query->paginate());
    }

    public function show(string $userId): JsonResponse
    {
        $user = User::query()->find($userId);
        if ($user === null) {

            return response()->json([
                'status' => 'error',
                'message' => 'User not found!',
                'code' => 'not_found'
            ]);
        }

        return response()->json($user);
    }

    public function create(UserCreateRequest $request): JsonResponse
    {
        $user = new User();
        $user->name = $request->input('name');
        $user->phone = $request->input('phone');
        $user->email = $request->input('email');
        $user->status = $request->input('status');
        $user->password = Hash::make($request->input('password'));

        $user = DB::transaction(function () use ($request, $user) {
            $user->save();
            $user->assignRole([$request->input('role')]);
            return $user;
        });

        return response()->json($user);
    }

    public function update(UserUpdateRequest $request, string $userId): JsonResponse
    {
        $user = User::query()->findOrFail($userId);
        $user->name = $request->input('name');
        $user->phone = $request->input('phone');
        $user->email = $request->input('email');

        if ($password = $request->input('password')) {
            $user->password = Hash::make($password);
        }

        $user = DB::transaction(function () use ($request, $user) {
            $user->save();
            $user->assignRole([$request->input('role')]);
            return $user;
        });

        return response()->json($user);
    }

    public function destroy(string $userId): JsonResponse
    {
        $user = User::query()->findOrFail($userId);
        $user->delete();

        return response()->json([
            'message' => 'success'
        ]);
    }
}
