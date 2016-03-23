Setting up the bundle
=============================

A: Download the Bundle
----------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require lilocon/wechat-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Easywechat

Instructions for installing and deploying Easywechat may be found [here](http://easywechat.org/docs/).

B: Enable the Bundle
-------------------------

Then, enable the bundle by adding the following line in the `app/AppKernel.php`
file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Lilocon\WechatBundle\LiloconWechatBundle(),
        );

        // ...
    }
}
```

C: Create your User class
-----------------------------

```php
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lilocon\WechatBundle\Model\WechatUserInterface;

/**
 * WechatUser
 *
 * @ORM\Table(name="wechat_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WechatUserRepository")
 */
class WechatUser implements WechatUserInterface
{

    static $sex_choices = array(
        0 => '未知',
        1 => '男',
        2 => '女',
    );

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="openid", type="string", length=255, unique=true)
     */
    private $openid;

    /**
     * @var string
     *
     * @ORM\Column(name="nickname", type="string", length=255)
     */
    private $nickname;

    /**
     * @var string
     *
     * @ORM\Column(name="sex", type="string", length=255)
     */
    private $sex;

    /**
     * @var string
     *
     * @ORM\Column(name="province", type="string", length=255)
     */
    private $province;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=255)
     */
    private $avatar;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set openid
     *
     * @param string $openid
     *
     * @return WechatUser
     */
    public function setOpenid($openid)
    {
        $this->openid = $openid;

        return $this;
    }

    /**
     * Get openid
     *
     * @return string
     */
    public function getOpenid()
    {
        return $this->openid;
    }

    /**
     * Set nickname
     *
     * @param string $nickname
     *
     * @return WechatUser
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set sex
     *
     * @param string $sex
     *
     * @return WechatUser
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set province
     *
     * @param string $province
     *
     * @return WechatUser
     */
    public function setProvince($province)
    {
        $this->province = $province;

        return $this;
    }

    /**
     * Get province
     *
     * @return string
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return WechatUser
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return WechatUser
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return WechatUser
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    public function load(array $data)
    {
        $this->setOpenid($data['openid']);
        $this->setNickname($data['nickname']);
        $this->setSex($data['sex']);
        $this->setProvince($data['province']);
        $this->setCity($data['city']);
        $this->setCountry($data['country']);
        $this->setAvatar($data['headimgurl']);
    }

    public function __toString()
    {
        return $this->getNickname();
    }
}
```


D: Basic Bundle Configuration
-----------------------------


```yaml
#app/config/config.yml
lilocon_wechat:
    app_id: "%app_id%"
    app_secret: "%app_secret%"
    token: "%token%"
    cache_provider_id: wechat_cache
    user_class: AppBundle\Entity\WechatUser
    alias: wechat_sdk
    payment:
        merchant_id: %merchant_id%
        key: %key%
        cert_path: "%kernel.root_dir%/../data/%kernel.environment%/cert/apiclient_cert.pem"
        key_path: "%kernel.root_dir%/../data/%kernel.environment%/cert/apiclient_key.pem"
```

```yaml
#app/config/security.yml
security:
    firewalls:
        # ...
        wechat:
            anonymous: ~
            pattern: ^/wechat
            wechat_login:
                authorize_path: /wechat/authorize

    access_control:
        # ...
        - { path: ^/wechat/authorize, roles: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/wechat, roles: ROLE_WECHAT_USER }
    
```

```yaml
#app/config/routing.yml
wechat_authorize:
    path: /wechat/authorize
```


LiloconWechatBundle Usage
=======================

```php
$sdk = $container->get('wechat_sdk');
$payment = $sdk->payment;
```
