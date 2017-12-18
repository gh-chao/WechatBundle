# wechat-bundle

> 注意：此版本为 3.x 版本，不兼容 2.x 与 1.x，与 [overtrue/wechat 3.x](https://github.com/overtrue/wechat) 同步

微信 SDK for Symfony， 基于 [overtrue/wechat](https://github.com/overtrue/wechat)

本项目只适用于，只有一个固定的账号，如果是开发微信公众号管理系统就不要使用了，直接用 [overtrue/wechat](https://github.com/overtrue/wechat) 更方便些。

## 安装

1. 安装包文件

  ```shell
  composer require "lilocon/wechat-bundle"
  ```

## 配置

1. 注册 `Bundle`:

```php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            // 在这里添加一行
            new Lilocon\WechatBundle\LiloconWechatBundle(),
            // ...
        );
        // ...
    }
    // ...
}
```

2. 更新配置文件：

```yaml
# app/config/config.yml
lilocon_wechat:

    # Debug 模式，bool 值：true/false
    # 当值为 false 时，所有的日志都不会记录
    debug: "%kernel.debug%"

    # 账号基本信息，请从微信公众平台/开放平台获取
    app_id: "%wechat_app_id%"
    secret: "%wechat_secret%"
    token: "%wechat_token%"
    aes_key: ""

    # 开放平台第三方平台配置信息
    open_platform:
        app_id: ""
        secret: ""
        token: ""
        aes_key: ""

    # 小程序配置信息
    mini_program:
        app_id: ""
        secret: ""
        token: ""
        aes_key: ""

    # 微信支付
    payment:
        merchant_id: "wechat_payment_merchant_id"
        key: "wechat_payment_key"
        cert_path: "path/to/your/cert.pem"
        key_path: "path/to/your/key"
        device_info: ""
        sub_app_id: ""
        sub_merchant_id: ""

    # 其他配置

    # sdk缓存配置
    cache:
      overwrite: false # 是否使用自定义缓存
      cache_id: wechat_cache # 自定义缓存service_id 若不填则使用easy_wechat默认缓存

    # 防火墙配置
    security:
      enabled: false # 是否使用微信授权功能
      user_provider_id: app_wechat_user_provider  # 用户提供者 service_id

    # sdk 服务别名
    service_alias: wechat_sdk
```
将配置文件对应参数改为自己的配置


## 基本使用
经过以上配置, 该bundle已经可以运行起来了

在控制器调用案例

```php
$wechat = $this->get('wechat_sdk');
```

下面以接收普通消息为例写一个例子：

```php
class WechatController extends Controller
{
    /**
     * 处理微信的请求消息
     *
     * @return string
     * @throws \EasyWeChat\Core\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Server\BadRequestException
     */
    public function serve()
    {
        $wechat = $this->get('wechat_sdk');
        $wechat->server->setMessageHandler(function($message){
            return "欢迎关注 overtrue！";
        });

        return $wechat->server->serve();
    }
}
```

## 使用自定义缓存

在 SDK 中的所有缓存默认使用文件缓存，缓存路径取决于 PHP 的临时目录，如果你需要自定义缓存，那么你需要做如下的事情：

修改配置

```php
    # sdk缓存配置
    cache:
      overwrite: true # false => true
      cache_id: wechat_cache # 自定义缓存service_id
```

其中cache_id是你自己定义的service_id

以下是使用doctrine-cache-bundle的配置案例

```yaml
doctrine_cache:
    aliases:
        wechat_cache: wechat_cache
    providers:
        wechat_cache:
            type: file_system
            file_system:
                directory: "%kernel.cache_dir%"
```


## OAuth

微信授权登录, 本模块扩展了symfony security 使其支持微信登录

##### 在配置中开启此功能

```yaml
    # 防火墙配置
    security:
      enabled: true # 是否使用微信授权功能 false => true
      user_provider_id: app_wechat_user_provider  # 用户提供者 service_id, 由下面进行讲解
```

##### 定义授权事件监听器

```php
// src/AppBundle/Events/WechatEventSubscriber.php
<?php

namespace AppBundle\Events;

use AppBundle\Entity\User;
use Lilocon\WechatBundle\Event\AuthorizeEvent;
use Lilocon\WechatBundle\Event\Events;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WechatEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * AuthenticationHandler constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::AUTHORIZE => 'authorize',
        ];
    }

    /**
     * 处理微信用户授权
     * @param AuthorizeEvent $event
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function authorize(AuthorizeEvent $event)
    {
        $wx_user = $event->getUser();

        $manager = $this->container->get('doctrine.orm.default_entity_manager');
        $repository = $manager->getRepository('AppBundle:User');

        $user = $repository->findOneBy(['openid' => $wx_user['openid']]);

        // 若无此用户则写入数据库
        if (!$user) {
            $user = new User();
            $user->setOpenid($wx_user['openid']);
            $user->setNickname($wx_user['nickname']);
            $manager->persist($user);
            $manager->flush();
        }
    }
}
```

##### 定义用户提供者

```php
// src/AppBundle/Providers/UserProvider.php
<?php

namespace AppBundle\Providers;

use Symfony\Component\DependencyInjection\ContainerInterface;

class UserProvider implements \Lilocon\WechatBundle\Contracts\UserProvider
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * AuthenticationHandler constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * 根据openid获取用户
     * @param $openid
     * @return \AppBundle\Entity\User|null|object
     */
    public function find($openid)
    {
        return $this->container->get('doctrine.orm.default_entity_manager')
            ->getRepository('AppBundle:User')
            ->findOneBy(['openid' => $openid]);
    }
}
```

##### 注册监听器

```yaml
#app/config/services.yml
services:
    # ... 其他配置
    app_wechat_user_provider:
        class: AppBundle\UserProvider
        arguments: ['@service_container']
    app.events.wechat_event_subscriber:
        class: AppBundle\Events\WechatEventSubscriber
        arguments: ['@service_container']
        tags:
            - { name: kernel.event_subscriber }
```

##### 添加防火墙

```yaml
#app/config/security.yml
security:
    firewalls:
        # ... 其他配置
        wechat:
            anonymous: ~
            pattern: ^/wechat
            wechat_login:
                default_redirect: /wechat #授权后默认跳转地址
                authorize_path: /wechat/authorize  #授权地址

    access_control:
        # ... 其他配置
        - { path: ^/wechat/authorize, roles: IS_AUTHENTICATED_ANONYMOUSLY } # 授权地址不需要登录
        - { path: ^/wechat, roles: ROLE_WECHAT_USER } # 需要微信登录的url前缀
```

##### 定义授权路由

```yaml
#app/config/routing.yml
# ... 其他路由
wechat_authorize:
    path: /wechat/authorize
```

微信浏览器打开  http://domain/wechat/ 体验


## 开放平台支持

添加开放平台授权事件接收url的路由
```yaml
wechat_open_platform_authorize:
    resource: '@LiloconWechatBundle/Resources/config/routing.xml'
    prefix: /wechat/open-platfom-notify
```
然后监听对应事件就可以了

## 事件

> 你可以监听相应的事件，并对事件发生后执行相应的操作。

- OAuth 网页授权：`Lilocon\WechatBundle\Event\Events::AUTHORIZE`
- 开放平台授权成功：`Lilocon\WechatBundle\Event\Events::OPEN_PLATFORM_AUTHORIZED`
- 开放平台授权更新：`Lilocon\WechatBundle\Event\Events::OPEN_PLATFORM_UPDATE_AUTHORIZED`
- 开放平台授权取消：`Lilocon\WechatBundle\Event\Events::OPEN_PLATFORM_UNAUTHORIZED`

更多 SDK 的具体使用请参考：https://easywechat.org

## License

MIT