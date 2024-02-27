<?php

namespace App\Services\Notify;

class NotifyService
{
    public static function createSender(string $senderType): NotifySender
    {
        $className = 'App\\Services\\Notify\\' . ucfirst(strtolower($senderType)) . 'Notify';

        if (class_exists($className)) {
            return new $className();
        } else {
            throw new \InvalidArgumentException("Unknown sender type: $senderType");
        }
    }
}
