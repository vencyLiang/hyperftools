<?php


namespace Vency\Tools\Notification;


Interface NotifyServiceInterface
{
    public function sendContent(string $destination,array $content,string $notifyServiceType,array $apiConfig): ?array;
}