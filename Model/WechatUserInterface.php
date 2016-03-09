<?php

namespace Lilocon\WechatBundle\Model;

/**
 * WechatUser
 */
interface WechatUserInterface
{
    public function getOpenid();
    public function load(array $data);
    public function __toString();
}