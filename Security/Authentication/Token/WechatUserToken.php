<?php

namespace Lilocon\WechatBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class WechatUserToken extends AbstractToken
{

    public function __construct($openid, array $roles = array())
    {
        parent::__construct($roles);

        $this->setAttribute('openid', $openid);
        $this->setAuthenticated(count($roles) > 0);
    }

    public function getOpenid()
    {
        return $this->getAttribute('openid');
    }

    public function getCredentials()
    {
        return '';
    }
}