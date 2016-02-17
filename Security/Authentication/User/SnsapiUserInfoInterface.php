<?php

namespace Lilocon\WechatBundle\Security\Authentication\User;

interface SnsapiUserInfoInterface
{
    public function getOpenid();
    public function getNickname();
    public function getSex();
    public function getProvince();
    public function getCity();
    public function getCountry();
    public function getHeadimgurl();
    public function setOpenid($openid);
    public function setNickname($nickname);
    public function setSex($sex);
    public function setProvince($province);
    public function setCity($city);
    public function setCountry($country);
    public function setHeadimgurl($headimgurl);
}