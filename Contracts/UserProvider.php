<?php

namespace Lilocon\WechatBundle\Contracts;

interface UserProvider
{
    public function find($openid);
}