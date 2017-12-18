<?php

namespace Lilocon\WechatBundle\Exception;

class UserNotFoundException extends \Exception
{

    protected $message = "用户未找到";

}