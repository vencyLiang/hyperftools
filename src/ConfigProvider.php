<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace Vency\Tools;
use Vency\Tools\Notification\{NotifyServiceFactory,NotifyService};

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                NotifyServiceInterface::class => NotifyServiceFactory::class
            ],
            'commands' => [
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'config of this tools file.', // 描述
                    // 建议默认配置放在 publish 文件夹中，文件命名和组件名称相同
                    'source' => __DIR__ . '/../publish/tools.php',  // 对应的配置文件路径
                    'destination' => BASE_PATH . '/config/autoload/tools.php', // 复制为这个路径下的该文件
                ],
            ],
        ];
    }
}
