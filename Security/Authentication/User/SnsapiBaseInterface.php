<?php

namespace Lilocon\WechatBundle\Security\Authentication\User;

interface SnsapiBaseInterface
{
    public function getOpenid();
    public function setOpenid($openid);

}