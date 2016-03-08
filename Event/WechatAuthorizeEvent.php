<?php

namespace Lilocon\WechatBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class WechatAuthorizeEvent extends Event
{
    /**
     * @var array
     */
    private $user;

    /**
     * WechatAuthorizeEvent constructor.
     * @param array $user
     */
    public function __construct(array $user)
    {
        $this->user = $user;
    }

    /**
     * @return array
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param array $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

}