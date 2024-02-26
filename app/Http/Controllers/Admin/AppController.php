<?php

namespace App\Http\Controllers\Admin;

use App\Enums\NewsStatusEnum;
use App\Enums\UserRoleEnum;
use App\Enums\UserStatusEnum;
use App\Http\Controllers\Controller;

class AppController extends Controller
{
    public function app()
    {
        return response()->json([
            'user_roles' => UserRoleEnum::values(),
            'user_statuses' => UserStatusEnum::values(),
            'news_statuses' => NewsStatusEnum::values(),
        ]);
    }
}
