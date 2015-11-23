<?php

/**
 * Class Marceli_NCC_Block_Adminhtml_Codes
 */
class Marceli_NCC_Block_Adminhtml_Codes
    extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_controller     = "adminhtml_codes";
        $this->_blockGroup     = "marceli_ncc";
        $this->_headerText     = Mage::helper("marceli_ncc")->__("Codes Manager");
        //$this->_addButtonLabel = Mage::helper("marceli_ncc")>__("Add New Item");

        parent::__construct();

        $this->removeButton('add');
    }

}