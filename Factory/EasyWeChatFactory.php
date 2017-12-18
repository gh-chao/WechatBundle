<?php

namespace Lilocon\WechatBundle\Factory;

use EasyWeChat\Foundation\Application;
use EasyWeChat\Support\Log;
use Psr\Log\LoggerInterface;

/**
 * easywechat 工厂类
 * Class EasyWeChatFactory
 * @package Lilocon\WechatBundle\Factory
 */
class EasyWeChatFactory
{

    /**
     * @param $config
     * @param $logger
     * @param $cache
     * @return Application
     */
    public static function createNewInstance(array $config, LoggerInterface $logger, $cache = null)
    {
        Log::setLogger($logger);

        if ($cache) {
            $config['cache'] = $cache;
        }

        $application = new Application($config);

        return $application;
    }
}
