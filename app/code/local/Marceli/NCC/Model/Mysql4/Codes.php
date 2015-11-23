<?php

/**
 * Class Marceli_NCC_Model_Mysql4_Codes
 */
class Marceli_NCC_Model_Mysql4_Codes
    extends Mage_Core_Model_Mysql4_Abstract
{

    protected function _construct()
    {
        $this->_init("marceli_ncc/codes", "code_id");
    }

}