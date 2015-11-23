<?php

/**
 * Class Marceli_NCC_Helper_Config
 */
class Marceli_NCC_Helper_Config
    extends Mage_Core_Helper_Abstract
{

    /**
     * @return int
     */
    protected function _getStoreId()
    {
        return Mage::app()->getStore()->getId();
    }

    /**
     * @return string
     */
    public function getIsEnabled()
    {
        return Mage::getStoreConfig(
            'newsletter/coupon_code/enabled',
            $this->_getStoreId()
        );
    }

    /**
     * @return string
     */
    public function getHeaderTitle()
    {
        return Mage::getStoreConfig(
            'newsletter/coupon_code/header_title',
            $this->_getStoreId()
        );
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return Mage::getStoreConfig(
            'newsletter/coupon_code/description',
            $this->_getStoreId()
        );
    }

    /**
     * @return string
     */
    public function getBackgroundImg()
    {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) .
            'theme' .
            DS .
            Mage::getStoreConfig('newsletter/coupon_code/background_img', $this->_getStoreId()
        );
    }

    /**
     * @return string
     */
    public function getPromotion()
    {
        return Mage::getStoreConfig(
            'newsletter/coupon_code/promotion',
            $this->_getStoreId()
        );
    }

}