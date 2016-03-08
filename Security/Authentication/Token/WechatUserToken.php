<?php

namespace Lilocon\WechatBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class WechatUserToken extends AbstractToken
{

    /**
     * @var string
     */
    protected $openid;

    public function __construct($openid, array $roles = array())
    {
        parent::__construct($roles);

        $this->openid = $openid;
        $this->setAuthenticated(count($roles) > 0);
    }

    public function getCredentials()
    {
        return '';
    }
}