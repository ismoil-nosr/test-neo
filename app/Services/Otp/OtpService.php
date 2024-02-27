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
            return;
        }

        $unique = false;
        while (!$unique) {
            $otp = random_int(100000, 999999);
            $value = Hash::make($key. "_" . $otp);
        
            // check if the value already exists in the database
            $exists = Otp::query()->where('hash', $value)->exists();
        
            if (!$exists) {
                $unique = true;
            }
        }
        
        // store the value in the cache for 5min
        Cache::remember($key, now()->addSeconds(60), function () use ($value) {
            $otp = new Otp();
            $otp->hash = $value;
            return $value;
        });

        return $otp;
    }

    public function check(string $key, string $otp): bool
    {
        if (Cache::has($key)) {
            //check if user already tried 5 times
            // if so then throw en exception

            $passedHash = Hash::make($key . "_" . $otp);
            $storedHash = Otp::query()->where('hash', $passedHash);

            if ($storedHash === $passedHash) {
                Cache::forget($passedHash);
                return true;
            } 

            return false;
        }
    }
}
