<?php
namespace Vency\Tools\Notification;
use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;
class NotifyServiceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        $notifyServiceConfig = $config->get('tools.verification.myApiConfig')?:$config->get('tools.verification.default')  ;
        //return new NotifyService($notifyServiceConfig);
        return make(NotifyService::class,compact('notifyServiceConfig'));
    }
}