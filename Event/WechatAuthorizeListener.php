<?php

namespace Lilocon\WechatBundle\Event;

use Doctrine\ORM\EntityManager;
use Lilocon\WechatBundle\Entity\WechatUser;

class WechatAuthorizeListener
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var string
     */
    private $entityName;

    private $repository;

    /**
     * @param string $entity
     * @param EntityManager $entityManager
     */
    public function __construct($entity, EntityManager $entityManager)
    {
        $this->em = $entityManager;
        $this->repository = $entityManager->getRepository($entity);
        $this->entityName = $entity;
    }

    public function onWechatAuthorize(WechatAuthorizeEvent $event)
    {
        $wx_user = $event->getUser();

        $openid = $wx_user['openid'];

        /** @var WechatUser $user */
        $user = $this->repository->findOneBy(array('openid' => $openid));

        if (!$user) {
            $user = new $this->entityName;
            $user->setOpenid($wx_user['openid']);
            $user->setNickname($wx_user['nickname']);
            $user->setSex($wx_user['sex']);
            $user->setHeadimgurl($wx_user['headimgurl']);
            $user->setCountry($wx_user['country']);
            $user->setProvince($wx_user['province']);
            $this->em->persist($user);
            $this->em->flush();
        }
    }

}