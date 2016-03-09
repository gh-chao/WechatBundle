<?php
namespace Lilocon\WechatBundle\Event;

use Doctrine\ORM\EntityManager;
use Lilocon\WechatBundle\Model\WechatUserInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WechatEventSubscriber implements EventSubscriberInterface
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var string
     */
    private $userClass;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repository;

    /**
     * @param string $userClass
     * @param EntityManager $entityManager
     */
    public function __construct($userClass, EntityManager $entityManager)
    {
        $this->em = $entityManager;
        $this->repository = $entityManager->getRepository($userClass);
        $this->userClass = $userClass;
    }

    public function onWechatAuthorize(WechatAuthorizeEvent $event)
    {
        $wx_user = $event->getUser();

        $openid = $wx_user['openid'];

        /** @var WechatUserInterface $user */
        $user = $this->repository->findOneBy(array('openid' => $openid));

        if (!$user) {
            $user = new $this->userClass;
            $user->load($wx_user);

            $this->em->persist($user);
            $this->em->flush();
        }
    }

    public static function getSubscribedEvents()
    {
        return array('lilocon.wechat.authorize' => 'onWechatAuthorize');
    }
}