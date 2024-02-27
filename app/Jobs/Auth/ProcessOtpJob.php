<?php

namespace App\Jobs\Auth;

use App\Services\Notify\NotifyService;
use App\Services\Otp\OtpService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessOtpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private string $key,
    )
    {
    }

    /**
     * Execute the job.
     */
    public function handle(OtpService $otpService, NotifyService $notifyService): void
    {
        $otpCode = $otpService->generate($this->key);

        $notify = $notifyService->createSender('sms');
        $notify->sendMessage($otpCode);
    }
}
