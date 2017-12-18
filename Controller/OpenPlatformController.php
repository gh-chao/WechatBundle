<?php

namespace Lilocon\WechatBundle\Controller;

use EasyWeChat\Foundation\Application;
use EasyWeChat\OpenPlatform\Guard;
use Lilocon\WechatBundle\Event\Events;
use Lilocon\WechatBundle\Event\OpenPlatformNotifyEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class OpenPlatformController
 * @package Lilocon\WechatBundle\Controller
 */
class OpenPlatformController extends Controller
{
    /**
     * @var Application
     */
    private $application;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * MessageController constructor.
     * @param Application $application
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(Application $application, EventDispatcherInterface $eventDispatcher)
    {
        $this->application = $application;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * 第三方平台微信服务器推送事件
     * @throws \EasyWeChat\Core\Exceptions\InvalidArgumentException
     */
    public function notify()
    {
        $server = $this->application->open_platform->server;

        $server->setMessageHandler(function ($event) {
            $openPlatformNotifyEvent = new OpenPlatformNotifyEvent($event);
            switch ($event->InfoType) {
                case Guard::EVENT_AUTHORIZED: // 授权成功
                    $this->eventDispatcher->dispatch(Events::OPEN_PLATFORM_AUTHORIZED, $openPlatformNotifyEvent);
                    break;
                case Guard::EVENT_UPDATE_AUTHORIZED: // 更新授权
                    $this->eventDispatcher->dispatch(Events::OPEN_PLATFORM_AUTHORIZED, $openPlatformNotifyEvent);
                    break;
                case Guard::EVENT_UNAUTHORIZED: // 授权取消
                    $this->eventDispatcher->dispatch(Events::OPEN_PLATFORM_AUTHORIZED, $openPlatformNotifyEvent);
                    break;
            }

        });

        return $server->serve();
    }

}
