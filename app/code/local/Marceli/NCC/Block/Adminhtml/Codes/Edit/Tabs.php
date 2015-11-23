<?php

/**
 * Class Marceli_NCC_Block_Adminhtml_Codes_Edit_Tabs
 */
class Marceli_NCC_Block_Adminhtml_Codes_Edit_Tabs
    extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId("codes_tabs");
        $this->setDestElementId("edit_form");
        $this->setTitle(Mage::helper("marceli_ncc")->__("Item Information"));
    }

    protected function _beforeToHtml()
    {
        $this->addTab("form_section", array(
            "label" => Mage::helper("marceli_ncc")->__("Item Information"),
            "title" => Mage::helper("marceli_ncc")->__("Item Information"),
            "content" => $this->getLayout()->createBlock("marceli_ncc/adminhtml_codes_edit_tab_form")->toHtml(),
        ));

        return parent::_beforeToHtml();
    }

}
