<?php

/**
 * Class Marceli_NCC_Block_Form
 */
class Marceli_NCC_Block_Form
    extends Mage_Core_Block_Template
{

    /**
     * @return string
     */
    public function getFormActionUrl()
    {
        return $this->getUrl('newsletter/subscriber/new', array('_secure' => true));
    }

    /**
     * @return string
     */
    public function getCloseFormUrl()
    {
        return $this->getUrl('ncc/index/closeForm');
    }

}
