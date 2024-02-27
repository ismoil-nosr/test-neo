<?php 

namespace App\Services\Notify;

interface NotifySender
{
    public function sendMessage(string $message): void;
}