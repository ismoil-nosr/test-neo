<?php

namespace App\Services\Otp;

use App\Models\Otp;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class OtpService
{
    public function generate(string $key): string
    {
        // we dont need to resend otp until it expires
        if (Cache::has($key)) {
            throw new \Exception(__('auth.otp_already_sent'));
        }

        $otp = '123456';
        $unique = false;
        while (!$unique) {
            $otp = '123456';
            $value = md5($key. "_" . $otp);

            // check if the value already exists in the database
            $exists = Otp::query()->where('hash', $value)->exists();

            if (!$exists) {
                $unique = true;
            }
        }

        // store the value in the cache for 1min
        Cache::remember($key, now()->addSeconds(60), function () use ($value) {
            $otp = new Otp();
            $otp->hash = $value;
            $otp->save();

            return $value;
        });

        return $otp;
    }

    public function check(string $key, string $otp): bool
    {
        if (Cache::has($key)) {
            //check if user already tried 5 times
            // if so then throw en exception

            $passedHash = md5($key . "_" . $otp);
            $otpModel = Otp::query()->where('hash', $passedHash)->first();

            if ($passedHash === $otpModel->hash) {
                Cache::forget($passedHash);
                return true;
            }
        }

        throw new \Exception(__('auth.otp_not_sent'));
    }
}
