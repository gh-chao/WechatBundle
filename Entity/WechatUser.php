<?php

namespace Lilocon\WechatBundle\Entity;

/**
 * WechatUser
 */
abstract class WechatUser
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $openid;

    /**
     * @var string
     */
    protected $nickname;

    /**
     * @var int
     */
    protected $sex;

    /**
     * @var string
     */
    protected $province;

    /**
     * @var string
     */
    protected $country;

    /**
     * @var string
     */
    protected $headimgurl;

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
     * @param integer $sex
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
     * @return int
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
     * Set headimgurl
     *
     * @param string $headimgurl
     *
     * @return WechatUser
     */
    public function setHeadimgurl($headimgurl)
    {
        $this->headimgurl = $headimgurl;

        return $this;
    }

    /**
     * Get headimgurl
     *
     * @return string
     */
    public function getHeadimgurl()
    {
        return $this->headimgurl;
    }

    public function getRules()
    {
        
    }
}

