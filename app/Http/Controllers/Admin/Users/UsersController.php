<?php

namespace App\Http\Controllers\Admin\Users;

use App\Enums\UserRoleEnum;
use App\Enums\UserStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index() 
    {
        $query = User::query()->with(['roles']);

        //Add filters/sorting etc.

        return response()->json($query->paginate());
    }

    public function show(string $userId)
    {
        $user = User::query()->find($userId);
        if ($user === null) {
            
            return response()->json([
                'status' => 'error',
                'message' => 'User not found!',
                'code' => 'not_found'
            ]);
        }

        return response($user);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|int|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'status' => 'required|string|in:' . implode(',', UserStatusEnum::values()),
            'role' => 'required|string|in:' . implode(',', UserRoleEnum::values())
        ]);

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

    public function update(Request $request, string $userId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|int|unique:users,phone,' . $userId,
            'email' => 'required|string|email|max:255|unique:users,email,' . $userId,
            'password' => 'string|min:8|confirmed',
            'status' => 'required|string|in:' . implode(',', UserStatusEnum::values()),
            'role' => 'required|string|in:' . implode(',', UserRoleEnum::values())
        ]);

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

    public function destroy(string $userId)
    {
        $user = User::query()->findOrFail($userId);
        $user->delete();

        return response()->json([
            'message' => 'success'
        ]);
    }
}
