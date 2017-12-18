<?php

namespace Lilocon\WechatBundle\Security\Firewall;

use EasyWeChat\Foundation\Application;
use Lilocon\WechatBundle\Event\Events;
use Lilocon\WechatBundle\Event\AuthorizeEvent;
use Lilocon\WechatBundle\Exception\UserNotFoundException;
use Lilocon\WechatBundle\Security\Authentication\Token\WechatUserToken;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Http\HttpUtils;

class WechatListener implements ListenerInterface
{

    const REDIRECT_URL_KEY = '_wechat.redirect_url';

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var \Lilocon\WechatBundle\Security\Authentication\Provider\WechatProvider
     */
    protected $authenticationManager;

    /**
     * @var array
     */
    protected $options = array(
        'authorize_path' => '/wechat/authorize',
        'default_redirect' => '/wechat',
    );

    /**
     * @var HttpUtils
     */
    protected $httpUtils;

    /**
     * @var Application
     */
    private $sdk;

    /**
     * @var EventDispatcherInterface
     */
    private $event_dispatcher;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        AuthenticationManagerInterface $authenticationManager,
        HttpUtils $httpUtils,
        Application $sdk,
        EventDispatcherInterface $event_dispatcher,
        array $options
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->options = array_merge($this->options, $options);
        $this->httpUtils = $httpUtils;
        $this->sdk = $sdk;
        $this->event_dispatcher = $event_dispatcher;
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $session = $request->getSession();

        $oauth = $this->sdk->oauth;

        // 授权页面
        if ($this->httpUtils->checkRequestPath($request, $this->options['authorize_path'])) {
            // 获取 OAuth 授权结果用户信息
            $user = $oauth->user()->getOriginal();

            $wechatAuthorizeEvent = new AuthorizeEvent($user);
            $this->event_dispatcher->dispatch(Events::AUTHORIZE, $wechatAuthorizeEvent);

            $token = new WechatUserToken($user['openid'], array('ROLE_USER', 'ROLE_WECHAT_USER'));

            $this->tokenStorage->setToken($token);
            $this->event_dispatcher->dispatch(
                AuthenticationEvents::AUTHENTICATION_SUCCESS,
                new AuthenticationEvent($token)
            );

            $redirect_url = $session->get(self::REDIRECT_URL_KEY) ?: $request->getUriForPath($this->options['default_redirect']);

            $session->remove(self::REDIRECT_URL_KEY);
            $event->setResponse(new RedirectResponse($redirect_url));
            return;
        }

        do {
            $token = $this->tokenStorage->getToken();
            if ($token === null) {
                break;
            }
            try {
                $token = $this->authenticationManager->authenticate($token);
            } catch(UserNotFoundException $e) {
                break;
            }
            $this->tokenStorage->setToken($token);
            return;
        } while(false);

        // 未授权, 重定向到微信授权页面
        $session->set(self::REDIRECT_URL_KEY, $request->getUri());
        $target_url = $request->getUriForPath($this->options['authorize_path']);
        $response = $oauth->scopes(['snsapi_userinfo'])->redirect($target_url);
        $event->setResponse($response);
    }

}