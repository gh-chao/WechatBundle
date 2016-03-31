<?php

namespace Lilocon\WechatBundle\Export\Source;

use EasyWeChat\User\User;
use Exporter\Source\SourceIteratorInterface;

class OpenId implements SourceIteratorInterface
{
    /**
     * @var User
     */
    private $user;

    private $pool = array();
    private $key = 0;
    private $next_openid = null;

    /**
     * OpenId constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return array('openid'=>array_shift($this->pool));
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        if (empty($this->pool)) {
            $result = $this->user->lists($this->next_openid);
            if ($result && $result['count']>0) {
                $this->pool = $result['data']['openid'];
                $this->next_openid = $result['next_openid'];
                return true;
            }
            return false;
        }
        return true;
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->pool = array();
        $this->next_openid = null;
        $this->key = 0;
    }
}