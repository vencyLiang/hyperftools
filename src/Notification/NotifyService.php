<?php
namespace Vency\Tools\Notification;

class NotifyService implements NotifyServiceInterface
{
    /**
     * @var array
     */
    private $notifyServiceConfig;

    /**
     * NotifyService constructor.
     * @param $notifyServiceConfig ['sms'=> SmsServiceApiNamespace\SmsServiceApiClassName::class,
     *                              'email'=> EmailServiceApiNamespace\EmailServiceApiClassName::class]
     */
    public function __construct($notifyServiceConfig)
    {
        $this->notifyServiceConfig = $notifyServiceConfig;
    }

    //'apiConfig'是服务提供商的配置,如apiKey, apisecret等参数
    public function sendContent(string $destination,array $content,string $notifyServiceType='sms',array $apiConfig = []): ?array {
        $apiServerClass =  $this->notifyServiceConfig[$notifyServiceType];
        return (new $apiServerClass($apiConfig))->send($destination,$content);
    }
}