<?php

namespace Lilocon\WechatBundle\Twig\Extension;

use EasyWeChat\Foundation\Application;

class EasyWechat extends \Twig_Extension
{
    /**
     * @var Application
     */
    private $wechat_sdk;

    /**
     * EasyWechat constructor.
     * @param Application $sdk
     */
    public function __construct(Application $sdk)
    {
        $this->wechat_sdk = $sdk;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'easy_wechat';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('lilocon_wechat_config', array($this, 'jsConfig')),
        );
    }

    public function jsConfig($apis = array())
    {
        return $this->wechat_sdk->js->config($apis);
    }
}
