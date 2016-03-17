<?php

namespace Lilocon\WechatBundle\Factory;

use EasyWeChat\Foundation\Application;
use EasyWeChat\Support\Log;

class EasyWeChatFactory
{
    public static function createNewInstance($config, $cache, $logger)
    {
        $application = new Application($config);
        $application->offsetSet('cache', $cache);
        Log::setLogger($logger);

        return $application;
    }
}