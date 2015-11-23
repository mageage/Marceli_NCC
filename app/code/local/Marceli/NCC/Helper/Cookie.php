<?php

/**
 * Class Marceli_NCC_Helper_Cookie
 */
class Marceli_NCC_Helper_Cookie
    extends Mage_Core_Helper_Abstract
{

    /**
     * Cookie name
     */
    const COOKIE_NAME = 'marceli_ncc';

    /**
     * Cookie value
     */
    const COOKIE_VALUE = true;

    /**
     * Cookie lifetime
     *
     * @var string
     */
    private $_cookieTime = '';

    /**
     * Class constructor
     */
    public function __construct()
    {
        $time = time() + (10 * 365 * 24 * 60 * 60);
        $this->setCookieTime($time);
    }

    /**
     * @return string
     */
    public function getCookieTime()
    {
        return $this->_cookieTime;
    }

    /**
     * @param string $cookieTime
     */
    public function setCookieTime($cookieTime)
    {
        $this->_cookieTime = $cookieTime;
    }

    /**
     * @return Mage_Core_Model_Cookie
     */
    public function setCookie()
    {
        return Mage::getModel('core/cookie')->set(
            self::COOKIE_NAME,
            self::COOKIE_VALUE,
            $this->getCookieTime()
        );
    }

    /**
     * @return mixed
     */
    public function getCookie()
    {
        return Mage::getModel('core/cookie')->get(self::COOKIE_NAME);
    }

}