<?php

namespace App\UseCases\Auth;

use App\Jobs\Auth\ProcessOtpJob;
use Illuminate\Http\Request;

class RequestRegisterUseCase
{
    public function execute(Request $request): void
    {
        ProcessOtpJob::dispatch($request->input('phone') . '_register');
    }
}