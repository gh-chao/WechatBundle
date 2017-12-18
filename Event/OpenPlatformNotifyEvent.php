<?php

namespace Lilocon\WechatBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class OpenPlatformNotifyEvent extends Event
{
    /**
     * @var array
     */
    private $event;

    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * @return array
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param array $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

}