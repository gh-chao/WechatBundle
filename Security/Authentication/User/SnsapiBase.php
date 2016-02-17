<?php

namespace Lilocon\WechatBundle\Security\Authentication\User;

class SnsapiBase implements SnsapiBaseInterface
{
    private $openid;

    public function getOpenid()
    {
        return $this->openid;
    }

    public function setOpenid($openid)
    {
        $this->openid = $openid;
    }

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
        return array('RULE_USER', 'RULE_WECHAT_BASE_USER');
    }

    public function __toString()
    {
        return (string)$this->openid;
    }
}