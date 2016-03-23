<?php

namespace Lilocon\WechatBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class WechatMessageEvent extends Event
{
    private $message;

    /**
     * WechatMessageEvent constructor.
     * @param $message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

}