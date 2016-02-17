<?php

namespace Lilocon\WechatBundle\Security\Authentication\User;

use Symfony\Component\Security\Core\User\UserInterface;

class SnsapiUserInfo implements UserInterface, SnsapiUserInfoInterface
{

    private $openid;
    private $nickname;
    private $sex;
    private $province;
    private $city;
    private $country;
    private $headimgurl;

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     * @return array (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return array('RULE_USER', 'RULE_WECHAT_USER');
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return null;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->nickname;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
    }

    public function getOpenid()
    {
        $this->openid;
    }

    public function getNickname()
    {
        return $this->nickname;
    }

    public function getSex()
    {
        return $this->sex;
    }

    public function getProvince()
    {
        return $this->province;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function getHeadimgurl()
    {
        return $this->headimgurl;
    }

    public function setOpenid($openid)
    {
        $this->openid = $openid;
    }

    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
    }

    public function setSex($sex)
    {
        $this->sex = $sex;
    }

    public function setProvince($province)
    {
        $this->province = $province;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    public function setCountry($country)
    {
        $this->country = $country;
    }

    public function setHeadimgurl($headimgurl)
    {
        $this->headimgurl = $headimgurl;
    }
}