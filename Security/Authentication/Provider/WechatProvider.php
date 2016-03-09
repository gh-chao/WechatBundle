<?php

namespace Lilocon\WechatBundle\Security\Authentication\Provider;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Lilocon\WechatBundle\Exception\UserNotFoundException;
use Lilocon\WechatBundle\Security\Authentication\Token\WechatUserToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class WechatProvider implements AuthenticationProviderInterface
{
    /**
     * @var string
     */
    private $userClass;
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * WechatProvider constructor.
     * @param string $userClass
     * @param ObjectManager $objectManager
     */
    public function __construct($userClass, ObjectManager $objectManager)
    {
        $this->userClass = $userClass;
        $this->objectManager = $objectManager;
        $this->repository = $objectManager->getRepository($userClass);
    }

    /**
     * @param TokenInterface $token
     * @return WechatUserToken
     * @throws UserNotFoundException
     */
    public function authenticate(TokenInterface $token)
    {
        /** @var WechatUserToken $token */
        $user = $this->repository->findOneBy(array('openid' => $token->getOpenid()));
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