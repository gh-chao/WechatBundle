<?php

namespace Lilocon\WechatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MessageController extends Controller
{

    public function entryAction()
    {
        $server         = $this->get('wechat_sdk')->server;
        $messageHandler = $this->get('lilocon_wechat.message.handler');

        $server->setMessageHandler(function($message) use ($messageHandler) {
            $messageHandler->handle($message);
        });
        $response = $server->serve();

        return $response;
    }

}
