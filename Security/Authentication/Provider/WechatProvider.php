<?php

namespace Lilocon\WechatBundle\Security\Authentication\Provider;

use Lilocon\WechatBundle\Contracts\UserProvider;
use Lilocon\WechatBundle\Exception\UserNotFoundException;
use Lilocon\WechatBundle\Security\Authentication\Token\WechatUserToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class WechatProvider implements AuthenticationProviderInterface
{

    /**
     * @var UserProvider
     */
    private $userProvider;

    /**
     * WechatProvider constructor.
     * @param UserProvider $userProvider
     */
    public function __construct(UserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    /**
     * @param TokenInterface $token
     * @return WechatUserToken
     * @throws UserNotFoundException
     */
    public function authenticate(TokenInterface $token)
    {
        /** @var WechatUserToken $token */
        $user = $this->userProvider->find($token->getOpenid());

        if (!$user) {
            throw new UserNotFoundException();
        }

        $token->setUser($user);

        return $token;
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof WechatUserToken;
    }
}