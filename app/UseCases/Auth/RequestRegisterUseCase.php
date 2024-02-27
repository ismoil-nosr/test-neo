<?php

namespace App\UseCases\Auth;

use Illuminate\Http\Request;

class RequestRegisterUseCase
{
    public function execute(Request $request): void
    {
        //check if user already tried 5 times
        // if so then throw en exception

        //Send an OTP code through event()
            //Generate unique OTP code that wasn't sent earlier
            //Send it through Notify service to subject
            //Save to otp_codes hash(phone+code) so we ensure security compliance and won't send same otp in the future

    }
}