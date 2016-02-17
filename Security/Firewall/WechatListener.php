<?php

namespace Lilocon\WechatBundle\Security\Firewall;

use Lilocon\WechatBundle\Security\Authentication\Token\WechatUserToken;
use Lilocon\WechatBundle\Security\Authentication\User\SnsapiBase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

class WechatListener implements ListenerInterface
{
    protected $tokenStorage;
    protected $authenticationManager;

    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationManagerInterface $authenticationManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
    }

    public function handle(GetResponseEvent $event)
    {
//        $request = $event->getRequest();

        $user = new SnsapiBase();
        $user->setOpenid(1);

        $token = new WechatUserToken(array('ROLE_ADMIN'));
        $token->setUser($user);

        try {
            $authToken = $this->authenticationManager->authenticate($token);
            $this->tokenStorage->setToken($authToken);
            return;
        } catch (AuthenticationException $failed) {
            // ... you might log something here

            // To deny the authentication clear the token. This will redirect to the login page.
            // Make sure to only clear your token, not those of other authentication listeners.
            // $token = $this->tokenStorage->getToken();
            // if ($token instanceof WsseUserToken && $this->providerKey === $token->getProviderKey()) {
            //     $this->tokenStorage->setToken(null);
            // }
            // return;
        }

        // By default deny authorization
//        $response = new Response();
//        $response->setStatusCode(Response::HTTP_FORBIDDEN);
//        $event->setResponse($response);
    }
}