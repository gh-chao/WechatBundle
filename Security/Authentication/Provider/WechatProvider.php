<?php

namespace Lilocon\WechatBundle\Security\Authentication\Provider;

use Lilocon\WechatBundle\Security\Authentication\Token\WechatUserToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class WechatProvider implements AuthenticationProviderInterface
{

    public function authenticate(TokenInterface $token)
    {
        return $token;
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof WechatUserToken;
    }
}