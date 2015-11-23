<?php

/**
 * Class Marceli_NCC_Block_Adminhtml_Codes_Edit
 */
class Marceli_NCC_Block_Adminhtml_Codes_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
        parent::__construct();
        $this->_objectId = "code_id";
        $this->_blockGroup = "marceli_ncc";
        $this->_controller = "adminhtml_codes";
        $this->_updateButton("save", "label", Mage::helper("marceli_ncc")->__("Save Item"));
        $this->_updateButton("delete", "label", Mage::helper("marceli_ncc")->__("Delete Item"));

        $this->_addButton("saveandcontinue", array(
            "label"     => Mage::helper("marceli_ncc")->__("Save And Continue Edit"),
            "onclick"   => "saveAndContinueEdit()",
            "class"     => "save",
        ), -100);

        $this->_formScripts[] = "
		    function saveAndContinueEdit(){
			    editForm.submit($('edit_form').action+'back/edit/');
			}
		";
    }

    public function getHeaderText()
    {
        if( Mage::registry("codes_data") && Mage::registry("codes_data")->getId() ){
            return Mage::helper("marceli_ncc")->__("Edit Item '%s'", $this->escapeHtml(Mage::registry("codes_data")->getId()));

        } else {
            return Mage::helper("marceli_ncc")->__("Add Item");
        }
    }
}