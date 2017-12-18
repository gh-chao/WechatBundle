<?php

namespace Lilocon\WechatBundle\Event;

final class Events
{

    const AUTHORIZE                              = 'lilocon.wechat.authorize';

    const OPEN_PLATFORM_AUTHORIZED        = 'lilocon.wechat.open_platform.authorized';
    const OPEN_PLATFORM_UPDATE_AUTHORIZED = 'lilocon.wechat.open_platform.update_authorized';
    const OPEN_PLATFORM_UNAUTHORIZED      = 'lilocon.wechat.open_platform.unauthorized';


    final private function __construct()
    {
    }
}