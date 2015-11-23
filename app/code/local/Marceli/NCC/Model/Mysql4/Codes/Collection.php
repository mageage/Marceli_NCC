<?php

/**
 * Class Marceli_NCC_Model_Mysql4_Codes_Collection
 */
class Marceli_NCC_Model_Mysql4_Codes_Collection
    extends Mage_Core_Model_Mysql4_Collection_Abstract
{

    public function _construct()
    {
        $this->_init("marceli_ncc/codes");
    }

}
