<?php

/**
 * Class Marceli_NCC_IndexController
 */
class Marceli_NCC_IndexController
    extends Mage_Core_Controller_Front_Action
{

    /**
     * @return Mage_Core_Controller_Varien_Action
     */
    public function indexAction()
    {
        return $this->_redirect('/');
    }

    /**
     * @return Mage_Core_Controller_Varien_Action
     */
    public function closeFormAction()
    {
        $this->_getCookieHelper()->setCookie();

        return $this->_redirectReferer();
    }

    /**
     * @return Marceli_NCC_Helper_Cookie
     */
    protected function _getCookieHelper()
    {
        return Mage::helper('marceli_ncc/cookie');
    }

}